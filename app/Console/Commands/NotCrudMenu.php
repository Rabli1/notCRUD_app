<?php

namespace App\Console\Commands;

use App\Models\Ouvrage;
use Illuminate\Console\Command;

class NotCrudMenu extends Command
{
    protected $signature = 'notcrud:menu';
    protected $description = 'Menu console pour la base MongoDB NotCRUD';

    public function handle(): int
    {
        $this->info('NotCRUD - Menu');

        while (true) {
            $choice = $this->choice('Choisissez une action', [
                'Inserer un ouvrage',
                'Rechercher par type (prix desc)',
                'Rechercher BD par dessinateur',
                'Prix moyen par type',
                'Seeder donnees de base',
                'Quitter',
            ], 0);

            switch ($choice) {
                case 'Inserer un ouvrage':
                    $this->insertOuvrage();
                    break;
                case 'Rechercher par type (prix desc)':
                    $this->searchByType();
                    break;
                case 'Rechercher BD par dessinateur':
                    $this->searchBdByDessinateur();
                    break;
                case 'Prix moyen par type':
                    $this->averagePriceByType();
                    break;
                case 'Seeder donnees de base':
                    $this->seedData();
                    break;
                case 'Quitter':
                    $this->info('Au revoir.');
                    return self::SUCCESS;
            }
        }
    }

    private function insertOuvrage(): void
    {
        $id = (int) $this->ask('Identifiant unique (_id)');
        if (Ouvrage::where('_id', $id)->exists()) {
            $this->error('Cet identifiant existe deja.');
            return;
        }

        $titre = (string) $this->ask('Titre');
        $prix = (float) $this->ask('Prix');
        $dispo = (int) $this->choice('Disponible ?', ['1', '0'], 0);
        $type = $this->choice('Type', ['livre', 'BD', 'periodique'], 0);

        $details = [];
        $exemplaires = null;

        if ($type === 'livre') {
            $details = [
                'annee' => (int) $this->ask('Annee d edition'),
                'maison_edition' => (string) $this->ask('Maison d edition'),
                'auteur' => (string) $this->ask('Auteur principal'),
            ];
            $exemplaires = ['ex1', 'ex2', 'ex3'];
        } elseif ($type === 'BD') {
            $details = [
                'annee' => (int) $this->ask('Annee d edition'),
                'maison_edition' => (string) $this->ask('Maison d edition'),
                'auteur' => (string) $this->ask('Auteur'),
                'dessinateur' => (string) $this->ask('Dessinateur'),
            ];
        } else {
            $details = [
                'date' => (string) $this->ask('Date de parution (YYYY-MM-DD)'),
                'periodicite' => (string) $this->ask('Periodicite (hebdomadaire, mensuel, journalier)'),
            ];
        }

        $data = [
            '_id' => $id,
            'titre' => $titre,
            'dispo' => $dispo,
            'prix' => $prix,
            'type' => $type,
            'details' => $details,
        ];

        if ($exemplaires !== null) {
            $data['exemplaires'] = $exemplaires;
        }

        Ouvrage::create($data);
        $this->info('Ouvrage insere.');
    }

    private function searchByType(): void
    {
        $type = $this->choice('Type a rechercher', ['livre', 'BD', 'periodique'], 0);
        $items = Ouvrage::where('type', $type)
            ->orderBy('prix', 'desc')
            ->get();

        $this->renderOuvrages($items);
    }

    private function searchBdByDessinateur(): void
    {
        $dessinateur = (string) $this->ask('Nom du dessinateur');
        $items = Ouvrage::where('type', 'BD')
            ->where('details.dessinateur', $dessinateur)
            ->orderBy('prix', 'desc')
            ->get();

        $this->renderOuvrages($items);
    }

    private function averagePriceByType(): void
    {
        $type = $this->choice('Type', ['livre', 'BD', 'periodique'], 0);
        $average = Ouvrage::where('type', $type)->avg('prix');

        if ($average === null) {
            $this->warn('Aucun ouvrage pour ce type.');
            return;
        }

        $this->info('Prix moyen (' . $type . ') : ' . number_format((float) $average, 2) . ' $');
    }

    private function seedData(): void
    {
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\OuvrageSeeder']);
    }

    private function renderOuvrages($items): void
    {
        if ($items->isEmpty()) {
            $this->warn('Aucun ouvrage trouve.');
            return;
        }

        $rows = $items->map(function (Ouvrage $ouvrage) {
            $details = $ouvrage->details ?? [];
            $detailsStr = $this->formatDetails($ouvrage->type, $details);

            return [
                'id' => (string) $ouvrage->_id,
                'titre' => $ouvrage->titre,
                'dispo' => (string) $ouvrage->dispo,
                'prix' => (string) $ouvrage->prix,
                'type' => $ouvrage->type,
                'details' => $detailsStr,
            ];
        })->all();

        $this->table(['id', 'titre', 'dispo', 'prix', 'type', 'details'], $rows);
    }

    private function formatDetails(string $type, array $details): string
    {
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
    }
}
