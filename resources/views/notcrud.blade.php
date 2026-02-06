<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NotCRUD - Bibliotheque</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap');

        :root {
            --bg: #0f1115;
            --panel: #161b22;
            --panel-2: #14181f;
            --text: #e6e9ee;
            --muted: #9aa4b2;
            --accent: #d97706;
            --accent-2: #b45309;
            --good: #19c37d;
            --bad: #f7666c;
            --border: #232a33;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Space Grotesk", "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 80px;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
        }

        .title {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--accent);
            color: #0f1115;
            font-weight: 700;
            font-size: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }


        .category-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 18px;
        }

        .category-card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .category-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .category-count {
            color: var(--muted);
            font-size: 12px;
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
            width: fit-content;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 18px;
            font-weight: 600;
        }

        label {
            display: block;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #0f1318;
            color: var(--text);
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.2);
        }

        .row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 12px;
        }
        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 8px;
        }

        .type-menu {
            display: inline-flex;
            gap: 6px;
            background: #0f1318;
            padding: 6px;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .type-fields {
            display: inline-block;
            width: fit-content;
            max-width: 100%;
        }

        .type-option {
            position: relative;
        }

        .type-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .type-option label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--muted);
            cursor: pointer;
            margin: 0;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .type-option input:checked + label {
            background: var(--accent);
            color: #0f1115;
            font-weight: 700;
        }

        button {
            background: var(--accent);
            border: none;
            color: #0f1115;
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .status {
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(25, 195, 125, 0.15);
            border: 1px solid rgba(25, 195, 125, 0.4);
            color: var(--good);
            margin-bottom: 14px;
        }

        .errors {
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(247, 102, 108, 0.1);
            border: 1px solid rgba(247, 102, 108, 0.4);
            color: var(--bad);
            margin-bottom: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th, td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }

        th {
            color: var(--muted);
            font-weight: 600;
        }

        .muted {
            color: var(--muted);
            font-size: 13px;
        }

        .hide {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <div class="title">NotCRUD - Bibliotheque
        </div>
        <div class="badge">Laravel + MongoDB</div>
    </header>

    @if (session('status'))
        <div class="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="errors">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="category-grid">
        <div class="category-card">
            <div class="category-title">Livres</div>
            <div class="category-count">{{ $counts['livre'] ?? 0 }} ouvrages</div>
        </div>
        <div class="category-card">
            <div class="category-title">BD</div>
            <div class="category-count">{{ $counts['BD'] ?? 0 }} ouvrages</div>
        </div>
        <div class="category-card">
            <div class="category-title">Periodiques</div>
            <div class="category-count">{{ $counts['periodique'] ?? 0 }} ouvrages</div>
        </div>
    </div>

    @php
        $formatDetails = function ($type, $details) {
            if ($type === 'livre') {
                return 'annee=' . ($details['annee'] ?? '') .
                    ', maison=' . ($details['maison_edition'] ?? '') .
                    ', auteur=' . ($details['auteur'] ?? '');
            }
            if ($type === 'BD') {
                return 'annee=' . ($details['annee'] ?? '') .
                    ', maison=' . ($details['maison_edition'] ?? '') .
                    ', auteur=' . ($details['auteur'] ?? '') .
                    ', dessinateur=' . ($details['dessinateur'] ?? '');
            }
            return 'date=' . ($details['date'] ?? '') .
                ', periodicite=' . ($details['periodicite'] ?? '');
        };
    @endphp

    <div class="grid">
        <div class="card">
            <h2>Inserer un ouvrage</h2>
            <form method="POST" action="{{ route('notcrud.store') }}">
                @csrf
                <div class="row">
                    <div>
                        <label for="titre">Titre</label>
                        <input id="titre" name="titre" type="text" value="{{ old('titre') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="prix">Prix</label>
                        <input id="prix" name="prix" type="number" step="0.01" min="0" value="{{ old('prix') }}" required>
                    </div>
                    <div>
                        <label for="dispo">Disponible</label>
                        <select id="dispo" name="dispo" required>
                            <option value="1" @selected(old('dispo') === '1')>Oui</option>
                            <option value="0" @selected(old('dispo') === '0')>Non</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div>
                        <label for="type">Type</label>
                        <div class="type-menu" role="tablist" aria-label="Type d ouvrage">
                            <div class="type-option">
                                <input type="radio" id="type_livre" name="type" value="livre" @checked(old('type', 'livre') === 'livre')>
                                <label for="type_livre">Livre</label>
                            </div>
                            <div class="type-option">
                                <input type="radio" id="type_bd" name="type" value="BD" @checked(old('type') === 'BD')>
                                <label for="type_bd">BD</label>
                            </div>
                            <div class="type-option">
                                <input type="radio" id="type_periodique" name="type" value="periodique" @checked(old('type') === 'periodique')>
                                <label for="type_periodique">Periodique</label>
                            </div>
                        </div>
                    </div>
                    <div class="muted">Les livres ajoutent automatiquement 3 exemplaires.</div>
                </div>

                <div id="fields-livre" class="type-fields">
                    <div class="row">
                        <div>
                            <label for="annee_livre">Annee</label>
                            <input id="annee_livre" name="annee" type="number" min="0" value="{{ old('annee') }}">
                        </div>
                        <div>
                            <label for="maison_livre">Maison d edition</label>
                            <input id="maison_livre" name="maison_edition" type="text" value="{{ old('maison_edition') }}">
                        </div>
                    </div>
                    <div>
                        <label for="auteur_livre">Auteur</label>
                        <input id="auteur_livre" name="auteur" type="text" value="{{ old('auteur') }}">
                    </div>
                </div>

                <div id="fields-bd" class="type-fields hide">
                    <div class="row">
                        <div>
                            <label for="annee_bd">Annee</label>
                            <input id="annee_bd" name="annee" type="number" min="0" value="{{ old('annee') }}">
                        </div>
                        <div>
                            <label for="maison_bd">Maison d edition</label>
                            <input id="maison_bd" name="maison_edition" type="text" value="{{ old('maison_edition') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <label for="auteur_bd">Auteur</label>
                            <input id="auteur_bd" name="auteur" type="text" value="{{ old('auteur') }}">
                        </div>
                        <div>
                            <label for="dessinateur">Dessinateur</label>
                            <input id="dessinateur" name="dessinateur" type="text" value="{{ old('dessinateur') }}">
                        </div>
                    </div>
                </div>

                <div id="fields-periodique" class="type-fields hide">
                    <div class="row">
                        <div>
                            <label for="date">Date de parution</label>
                            <input id="date" name="date" type="date" value="{{ old('date') }}">
                        </div>
                        <div>
                            <label for="periodicite">Periodicite</label>
                            <input id="periodicite" name="periodicite" type="text" placeholder="hebdomadaire, mensuel, journalier" value="{{ old('periodicite') }}">
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit">Inserer</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h2>Rechercher par type (prix desc)</h2>
            <form method="GET" action="{{ route('notcrud.index') }}">
                <div class="row">
                    <div>
                        <label for="type_search">Type</label>
                        <select id="type_search" name="type">
                            <option value="">Selectionner</option>
                            <option value="livre" @selected($queryType === 'livre')>Livre</option>
                            <option value="BD" @selected($queryType === 'BD')>BD</option>
                            <option value="periodique" @selected($queryType === 'periodique')>Periodique</option>
                        </select>
                    </div>
                    <div class="actions">
                        <button type="submit">Rechercher</button>
                    </div>
                </div>
            </form>

            @if ($itemsByType !== null)
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Prix</th>
                        <th>Type</th>
                        @if ($queryType === 'livre')
                            <th>Annee</th>
                            <th>Maison edition</th>
                            <th>Auteur</th>
                            <th>Exemplaires</th>
                        @elseif ($queryType === 'BD')
                            <th>Annee</th>
                            <th>Maison edition</th>
                            <th>Auteur</th>
                            <th>Dessinateur</th>
                        @elseif ($queryType === 'periodique')
                            <th>Date</th>
                            <th>Periodicite</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($itemsByType as $item)
                        <tr>
                            <td>{{ $item->_id }}</td>
                            <td>{{ $item->titre }}</td>
                            <td>{{ $item->prix }}</td>
                            <td>{{ $item->type }}</td>
                            @if ($queryType === 'livre')
                                <td>{{ $item->details['annee'] ?? '' }}</td>
                                <td>{{ $item->details['maison_edition'] ?? '' }}</td>
                                <td>{{ $item->details['auteur'] ?? '' }}</td>
                                <td>{{ isset($item->exemplaires) ? implode(', ', $item->exemplaires) : '' }}</td>
                            @elseif ($queryType === 'BD')
                                <td>{{ $item->details['annee'] ?? '' }}</td>
                                <td>{{ $item->details['maison_edition'] ?? '' }}</td>
                                <td>{{ $item->details['auteur'] ?? '' }}</td>
                                <td>{{ $item->details['dessinateur'] ?? '' }}</td>
                            @elseif ($queryType === 'periodique')
                                <td>{{ $item->details['date'] ?? '' }}</td>
                                <td>{{ $item->details['periodicite'] ?? '' }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if ($itemsByType->isEmpty())
                    <div class="muted">Aucun ouvrage trouve.</div>
                @endif
            @endif
        </div>

        <div class="card">
            <h2>Rechercher BD par dessinateur</h2>
            <form method="GET" action="{{ route('notcrud.index') }}">
                <div class="row">
                    <div>
                        <label for="dessinateur_search">Dessinateur</label>
                        <input id="dessinateur_search" name="dessinateur" type="text" value="{{ $queryDessinateur }}">
                    </div>
                    <div class="actions">
                        <button type="submit">Rechercher</button>
                    </div>
                </div>
            </form>

            @if ($itemsByDessinateur !== null)
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Prix</th>
                        <th>Type</th>
                        <th>Annee</th>
                        <th>Maison edition</th>
                        <th>Auteur</th>
                        <th>Dessinateur</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($itemsByDessinateur as $item)
                        <tr>
                            <td>{{ $item->_id }}</td>
                            <td>{{ $item->titre }}</td>
                            <td>{{ $item->prix }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->details['annee'] ?? '' }}</td>
                            <td>{{ $item->details['maison_edition'] ?? '' }}</td>
                            <td>{{ $item->details['auteur'] ?? '' }}</td>
                            <td>{{ $item->details['dessinateur'] ?? '' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if ($itemsByDessinateur->isEmpty())
                    <div class="muted">Aucune BD trouvee.</div>
                @endif
            @endif
        </div>

        <div class="card">
            <h2>Prix moyen par type</h2>
            <form method="GET" action="{{ route('notcrud.index') }}">
                <div class="row">
                    <div>
                        <label for="avg_type">Type</label>
                        <select id="avg_type" name="avgType">
                            <option value="">Selectionner</option>
                            <option value="livre" @selected($queryAvgType === 'livre')>Livre</option>
                            <option value="BD" @selected($queryAvgType === 'BD')>BD</option>
                            <option value="periodique" @selected($queryAvgType === 'periodique')>Periodique</option>
                        </select>
                    </div>
                    <div class="actions">
                        <button type="submit">Calculer</button>
                    </div>
                </div>
            </form>

            @if ($avg !== null)
                <div class="muted">Prix moyen: {{ number_format((float) $avg, 2) }} $</div>
            @endif
        </div>
    </div>
</div>

<script>
    const fieldsLivre = document.getElementById('fields-livre');
    const fieldsBd = document.getElementById('fields-bd');
    const fieldsPeriodique = document.getElementById('fields-periodique');
    const typeInputs = document.querySelectorAll('input[name="type"]');

    function setSection(section, enabled) {
        section.classList.toggle('hide', !enabled);
        section.querySelectorAll('input, select, textarea').forEach((el) => {
            el.disabled = !enabled;
        });
    }

    function updateFields() {
        const selected = document.querySelector('input[name="type"]:checked');
        const value = selected ? selected.value : 'livre';
        setSection(fieldsLivre, value === 'livre');
        setSection(fieldsBd, value === 'BD');
        setSection(fieldsPeriodique, value === 'periodique');
    }

    typeInputs.forEach((input) => input.addEventListener('change', updateFields));
    updateFields();
</script>
</body>
</html>
