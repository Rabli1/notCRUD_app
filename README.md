# NotCRUD - Laravel + MongoDB

Projet Laravel avec MongoDB pour la base NotCRUD (collection `ouvrages`) et un menu console.

## Prerequis

- PHP 8.2+
- MongoDB Server (local)
- Extension PHP `mongodb` active
- Composer

Pour activer l extension, ajoutez/activez dans `php.ini` :

```ini
extension=mongodb
```

Puis redemarrez votre serveur PHP (WAMP/XAMPP) et verifiez avec :

```bash
php -m | findstr mongodb
```

## Configuration

Les variables MongoDB sont deja pretes dans `.env` :

```
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=NotCRUD
DB_USERNAME=
DB_PASSWORD=
```

Si vous preferez, vous pouvez utiliser un URI :

```
MONGODB_URI=mongodb://127.0.0.1:27017/NotCRUD
```

## Commandes utiles

- Lancer le menu console :

```bash
php artisan notcrud:menu
```

- Charger les donnees de base (5 livres, 5 BD, 5 periodiques) :

```bash
php artisan db:seed --class=Database\\Seeders\\OuvrageSeeder
```

## Structure des documents

Les documents utilisent des cles ASCII :

- commun : `_id`, `titre`, `dispo`, `prix`, `type`, `details`
- `livre` : `details.annee`, `details.maison_edition`, `details.auteur`, `exemplaires`
- `BD` : `details.annee`, `details.maison_edition`, `details.auteur`, `details.dessinateur`
- `periodique` : `details.date`, `details.periodicite`

## Modele relationnel 3FN (CRUD)

Voir `docs/crud-3nf.md`.
