$ErrorActionPreference = "Stop"

function Write-Step($message) {
    Write-Host "==> $message"
}

Write-Step "Detecting PHP environment"
$phpVersion = & php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION;"
$phpMajorMinor = & php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;"
$phpZts = & php -r "echo (PHP_ZTS ? 'enabled' : 'disabled');"
$phpArch = & php -r "echo (PHP_INT_SIZE === 8 ? 'x64' : 'x86');"
$phpIni = & php -r "echo php_ini_loaded_file();"
$phpExtDir = & php -r "echo ini_get('extension_dir');"
$phpCompilerLine = (& php -i | Select-String -Pattern "^Compiler =>").Line

if (-not $phpIni) { throw "Unable to detect php.ini path." }
if (-not $phpExtDir) { throw "Unable to detect extension_dir." }

$vs = if ($phpCompilerLine -match "2019|2022") { "vs16" } elseif ($phpCompilerLine -match "2017") { "vs15" } else { "vs16" }
$tsFlag = if ($phpZts -eq "enabled") { "ts" } else { "nts" }

Write-Host "PHP $phpVersion ($phpMajorMinor) | TS=$phpZts | Arch=$phpArch | Compiler=$phpCompilerLine"
Write-Host "php.ini: $phpIni"
Write-Host "extension_dir: $phpExtDir"

Write-Step "Installing MongoDB Server (winget)"
try {
    $svc = Get-Service -Name "MongoDB" -ErrorAction SilentlyContinue
    if (-not $svc) {
        & winget install --id MongoDB.Server -e --source winget --accept-package-agreements --accept-source-agreements --silent
    } else {
        Write-Host "MongoDB service already present. Skipping server install."
    }
} catch {
    Write-Warning "MongoDB Server install may have failed or is already installed."
}

Write-Step "Installing MongoDB Shell (mongosh) (winget)"
try {
    $mongosh = Get-Command mongosh -ErrorAction SilentlyContinue
    if (-not $mongosh) {
        & winget install --id MongoDB.Shell -e --source winget --accept-package-agreements --accept-source-agreements --silent
    } else {
        Write-Host "mongosh already available. Skipping shell install."
    }
} catch {
    Write-Warning "MongoDB Shell install may have failed or is already installed."
}

Write-Step "Starting MongoDB service (if present)"
try {
    $svc = Get-Service -Name "MongoDB" -ErrorAction Stop
    if ($svc.Status -ne "Running") {
        Start-Service -Name "MongoDB"
    }
    Write-Host "MongoDB service status: $((Get-Service -Name 'MongoDB').Status)"
} catch {
    Write-Warning "MongoDB service not found or could not be started."
}

Write-Step "Downloading PHP MongoDB extension"
$base = "https://downloads.php.net/~windows/pecl/releases/mongodb/"
$html = (Invoke-WebRequest -Uri $base -UseBasicParsing).Content
$versions = [regex]::Matches($html, '(?i)href="(\d+\.\d+\.\d+)/"') |
    ForEach-Object { $_.Groups[1].Value } |
    Sort-Object { [version]$_ } -Descending -Unique

if (-not $versions) { throw "No mongodb extension versions found at $base" }

$selectedVersion = $null
$downloadUrl = $null
foreach ($ver in $versions) {
    $zipName = "php_mongodb-$ver-$phpMajorMinor-$tsFlag-$vs-$phpArch.zip"
    $url = "$base$ver/$zipName"
    try {
        $resp = Invoke-WebRequest -Uri $url -Method Head -UseBasicParsing
        if ($resp.StatusCode -ge 200 -and $resp.StatusCode -lt 300) {
            $selectedVersion = $ver
            $downloadUrl = $url
            break
        }
    } catch {
        # try next version
    }
}

if (-not $downloadUrl) {
    throw "Could not find a compatible php_mongodb zip for PHP $phpMajorMinor ($tsFlag, $vs, $phpArch)."
}

Write-Host "Selected php_mongodb version: $selectedVersion"
$tmpZip = Join-Path $env:TEMP "php_mongodb-$selectedVersion-$phpMajorMinor-$tsFlag-$vs-$phpArch.zip"
$tmpDir = Join-Path $env:TEMP "php_mongodb-$selectedVersion"

Invoke-WebRequest -Uri $downloadUrl -OutFile $tmpZip -UseBasicParsing
if (Test-Path $tmpDir) { Remove-Item $tmpDir -Recurse -Force }
Expand-Archive -Path $tmpZip -DestinationPath $tmpDir -Force

$dllPath = Join-Path $tmpDir "php_mongodb.dll"
if (-not (Test-Path $dllPath)) {
    throw "php_mongodb.dll not found in archive."
}

Copy-Item -Path $dllPath -Destination $phpExtDir -Force
Write-Host "Copied php_mongodb.dll to $phpExtDir"

Write-Step "Updating php.ini to load mongodb extension"
$iniContent = Get-Content -Path $phpIni
$pattern = '^\s*;?\s*extension\s*=\s*php_mongodb\.dll\s*$'
if ($iniContent -match $pattern) {
    $iniContent = $iniContent -replace $pattern, 'extension=php_mongodb.dll'
} else {
    $iniContent += ""
    $iniContent += "; MongoDB extension"
    $iniContent += "extension=php_mongodb.dll"
}
Set-Content -Path $phpIni -Value $iniContent

Write-Step "Verifying PHP extension load"
try {
    $mods = & php -m
    if ($mods -match "mongodb") {
        Write-Host "OK: mongodb extension loaded."
    } else {
        Write-Warning "mongodb extension not listed. Restart PHP/Apache and re-check."
    }
} catch {
    Write-Warning "Unable to verify php modules."
}

Write-Step "Done"
Write-Host "If you use WAMP/Apache, restart it to load the new extension."
