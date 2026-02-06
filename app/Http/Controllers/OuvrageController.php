<?php

namespace App\Http\Controllers;

use App\Models\Ouvrage;
use Illuminate\Http\Request;

class OuvrageController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $dessinateur = $request->query('dessinateur');
        $avgType = $request->query('avgType');

        $itemsByType = null;
        $itemsByDessinateur = null;
        $avg = null;

        $counts = [
            'livre' => Ouvrage::where('type', 'livre')->where('dispo', 1)->count(),
            'BD' => Ouvrage::where('type', 'BD')->where('dispo', 1)->count(),
            'periodique' => Ouvrage::where('type', 'periodique')->where('dispo', 1)->count(),
        ];

        if (in_array($type, ['livre', 'BD', 'periodique'], true)) {
            $itemsByType = Ouvrage::where('type', $type)
                ->where('dispo', 1)
                ->orderBy('prix', 'desc')
                ->get();
        }

        if (!empty($dessinateur)) {
            $itemsByDessinateur = Ouvrage::where('type', 'BD')
                ->where('dispo', 1)
                ->where('details.dessinateur', $dessinateur)
                ->orderBy('prix', 'desc')
                ->get();
        }

        if (in_array($avgType, ['livre', 'BD', 'periodique'], true)) {
            $avg = Ouvrage::where('type', $avgType)->where('dispo', 1)->avg('prix');
        }

        return view('notcrud', [
            'itemsByType' => $itemsByType,
            'itemsByDessinateur' => $itemsByDessinateur,
            'avg' => $avg,
            'counts' => $counts,
            'queryType' => $type,
            'queryDessinateur' => $dessinateur,
            'queryAvgType' => $avgType,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'prix' => ['required', 'numeric', 'min:0'],
            'dispo' => ['required', 'in:0,1'],
            'type' => ['required', 'in:livre,BD,periodique'],
            'annee' => ['required_if:type,livre,BD', 'integer', 'min:0'],
            'maison_edition' => ['required_if:type,livre,BD', 'string', 'max:255'],
            'auteur' => ['required_if:type,livre,BD', 'string', 'max:255'],
            'dessinateur' => ['required_if:type,BD', 'string', 'max:255'],
            'date' => ['required_if:type,periodique', 'date'],
            'periodicite' => ['required_if:type,periodique', 'string', 'max:50'],
        ]);
        $nextId = (int) (Ouvrage::max('_id') ?? 0) + 1;

        $type = $data['type'];
        $details = [];
        $exemplaires = null;

        if ($type === 'livre') {
            $details = [
                'annee' => (int) $data['annee'],
                'maison_edition' => (string) $data['maison_edition'],
                'auteur' => (string) $data['auteur'],
            ];
            $exemplaires = ['ex1', 'ex2', 'ex3'];
        } elseif ($type === 'BD') {
            $details = [
                'annee' => (int) $data['annee'],
                'maison_edition' => (string) $data['maison_edition'],
                'auteur' => (string) $data['auteur'],
                'dessinateur' => (string) $data['dessinateur'],
            ];
        } else {
            $details = [
                'date' => (string) $data['date'],
                'periodicite' => (string) $data['periodicite'],
            ];
        }

        $payload = [
            '_id' => $nextId,
            'titre' => $data['titre'],
            'dispo' => (int) $data['dispo'],
            'prix' => (float) $data['prix'],
            'type' => $type,
            'details' => $details,
        ];

        if ($exemplaires !== null) {
            $payload['exemplaires'] = $exemplaires;
        }

        Ouvrage::create($payload);

        return redirect()->route('notcrud.index')->with('status', 'Ouvrage insere.');
    }

}
