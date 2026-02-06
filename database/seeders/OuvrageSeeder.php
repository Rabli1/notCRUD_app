<?php

namespace Database\Seeders;

use App\Models\Ouvrage;
use Illuminate\Database\Seeder;

class OuvrageSeeder extends Seeder
{
    public function run(): void
    {
        Ouvrage::query()->delete();

        $ouvrages = [
            [
                '_id' => 1,
                'titre' => 'Les chemins qui montent',
                'dispo' => 1,
                'prix' => 60,
                'type' => 'livre',
                'exemplaires' => ['ex1', 'ex2', 'ex3'],
                'details' => [
                    'annee' => 1980,
                    'maison_edition' => 'Ici',
                    'auteur' => 'Alain Patoche',
                ],
            ],
            [
                '_id' => 2,
                'titre' => 'La nuit des étoiles',
                'dispo' => 1,
                'prix' => 45,
                'type' => 'livre',
                'exemplaires' => ['ex1', 'ex2', 'ex3'],
                'details' => [
                    'annee' => 1995,
                    'maison_edition' => 'Orion',
                    'auteur' => 'Marie Leduc',
                ],
            ],
            [
                '_id' => 3,
                'titre' => 'Histoire de l eau',
                'dispo' => 0,
                'prix' => 35,
                'type' => 'livre',
                'exemplaires' => ['ex1', 'ex2', 'ex3'],
                'details' => [
                    'annee' => 2004,
                    'maison_edition' => 'Hydra',
                    'auteur' => 'Yanis B.',
                ],
            ],
            [
                '_id' => 4,
                'titre' => 'Le temps des machines',
                'dispo' => 1,
                'prix' => 70,
                'type' => 'livre',
                'exemplaires' => ['ex1', 'ex2', 'ex3'],
                'details' => [
                    'annee' => 2012,
                    'maison_edition' => 'Nova',
                    'auteur' => 'Claire M.',
                ],
            ],
            [
                '_id' => 5,
                'titre' => 'Le code secret',
                'dispo' => 1,
                'prix' => 55,
                'type' => 'livre',
                'exemplaires' => ['ex1', 'ex2', 'ex3'],
                'details' => [
                    'annee' => 2018,
                    'maison_edition' => 'Sigma',
                    'auteur' => 'Omar S.',
                ],
            ],
            [
                '_id' => 6,
                'titre' => 'Asterix et Cleopatre',
                'dispo' => 1,
                'prix' => 50,
                'type' => 'BD',
                'details' => [
                    'annee' => 1968,
                    'maison_edition' => 'Ici',
                    'auteur' => 'Rene Goscinny',
                    'dessinateur' => 'Albert Uderzo',
                ],
            ],
            [
                '_id' => 7,
                'titre' => 'Tintin au Tibet',
                'dispo' => 1,
                'prix' => 48,
                'type' => 'BD',
                'details' => [
                    'annee' => 1960,
                    'maison_edition' => 'Casterman',
                    'auteur' => 'Herge',
                    'dessinateur' => 'Herge',
                ],
            ],
            [
                '_id' => 8,
                'titre' => 'Lucky Luke: Daisy Town',
                'dispo' => 0,
                'prix' => 42,
                'type' => 'BD',
                'details' => [
                    'annee' => 1983,
                    'maison_edition' => 'Dupuis',
                    'auteur' => 'Morris',
                    'dessinateur' => 'Morris',
                ],
            ],
            [
                '_id' => 9,
                'titre' => 'Spirou et Fantasio',
                'dispo' => 1,
                'prix' => 46,
                'type' => 'BD',
                'details' => [
                    'annee' => 1978,
                    'maison_edition' => 'Dupuis',
                    'auteur' => 'Fournier',
                    'dessinateur' => 'Fournier',
                ],
            ],
            [
                '_id' => 10,
                'titre' => 'Blake et Mortimer',
                'dispo' => 1,
                'prix' => 52,
                'type' => 'BD',
                'details' => [
                    'annee' => 1987,
                    'maison_edition' => 'Dargaud',
                    'auteur' => 'Edgar P. Jacobs',
                    'dessinateur' => 'Edgar P. Jacobs',
                ],
            ],
            [
                '_id' => 11,
                'titre' => 'Le journal de minuit',
                'dispo' => 1,
                'prix' => 20,
                'type' => 'periodique',
                'details' => [
                    'date' => '2025-01-02',
                    'periodicite' => 'mensuel',
                ],
            ],
            [
                '_id' => 12,
                'titre' => 'Science Hebdo',
                'dispo' => 1,
                'prix' => 15,
                'type' => 'periodique',
                'details' => [
                    'date' => '2025-02-10',
                    'periodicite' => 'hebdomadaire',
                ],
            ],
            [
                '_id' => 13,
                'titre' => 'Economie Aujourd hui',
                'dispo' => 0,
                'prix' => 18,
                'type' => 'periodique',
                'details' => [
                    'date' => '2025-03-01',
                    'periodicite' => 'mensuel',
                ],
            ],
            [
                '_id' => 14,
                'titre' => 'Le Quotidien',
                'dispo' => 1,
                'prix' => 10,
                'type' => 'periodique',
                'details' => [
                    'date' => '2025-01-15',
                    'periodicite' => 'journalier',
                ],
            ],
            [
                '_id' => 15,
                'titre' => 'Tech Minute',
                'dispo' => 1,
                'prix' => 12,
                'type' => 'periodique',
                'details' => [
                    'date' => '2025-02-01',
                    'periodicite' => 'hebdomadaire',
                ],
            ],
        ];

        Ouvrage::insert($ouvrages);
    }
}
