<?php

declare(strict_types=1);

use App\Models\Ouvrage;

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$countPerType = isset($argv[1]) ? (int) $argv[1] : 5;
if ($countPerType < 1) {
    fwrite(STDERR, "Usage: php scripts/bulk-insert-ouvrages.php [countPerType]\n");
    exit(1);
}

$authors = ['Alain Durand', 'Claire Morel', 'Samir N.', 'Julie Perrin', 'Rene G.'];
$publishers = ['Ici', 'Nova', 'Sigma', 'Orion', 'Hydra', 'Dargaud', 'Dupuis'];
$drawers = ['Albert U.', 'Herge', 'Morris', 'Fournier', 'Jacobs'];
$periods = ['hebdomadaire', 'mensuel', 'journalier'];

$maxId = (int) (Ouvrage::max('_id') ?? 0);
$nextId = $maxId + 1;

$docs = [];

for ($i = 0; $i < $countPerType; $i++) {
    $docs[] = [
        '_id' => $nextId++,
        'titre' => 'Livre auto ' . $nextId,
        'dispo' => 1,
        'prix' => 30 + ($i * 2),
        'type' => 'livre',
        'exemplaires' => ['ex1', 'ex2', 'ex3'],
        'details' => [
            'annee' => 2000 + $i,
            'maison_edition' => $publishers[array_rand($publishers)],
            'auteur' => $authors[array_rand($authors)],
        ],
    ];

    $docs[] = [
        '_id' => $nextId++,
        'titre' => 'BD auto ' . $nextId,
        'dispo' => 1,
        'prix' => 25 + ($i * 3),
        'type' => 'BD',
        'details' => [
            'annee' => 1990 + $i,
            'maison_edition' => $publishers[array_rand($publishers)],
            'auteur' => $authors[array_rand($authors)],
            'dessinateur' => $drawers[array_rand($drawers)],
        ],
    ];

    $docs[] = [
        '_id' => $nextId++,
        'titre' => 'Periodique auto ' . $nextId,
        'dispo' => 1,
        'prix' => 10 + $i,
        'type' => 'periodique',
        'details' => [
            'date' => date('Y-m-d', strtotime("+$i days")),
            'periodicite' => $periods[array_rand($periods)],
        ],
    ];
}

Ouvrage::insert($docs);

echo "Inserted " . count($docs) . " ouvrages.\n";
