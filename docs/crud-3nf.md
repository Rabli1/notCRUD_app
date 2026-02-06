# Modele relationnel 3FN - Base CRUD

Objectif : migrer la base NotCRUD (MongoDB) vers une base relationnelle CRUD.

## Relations proposees (3FN)

1. **Ouvrage**(`ouvrage_id`, titre, dispo, prix, type)
2. **Livre**(`ouvrage_id` PK/FK -> Ouvrage, annee_edition, maison_edition, auteur)
3. **BD**(`ouvrage_id` PK/FK -> Ouvrage, annee_edition, maison_edition, auteur, dessinateur)
4. **Periodique**(`ouvrage_id` PK/FK -> Ouvrage, periodicite, date_parution)
5. **Exemplaire**(`exemplaire_id`, ouvrage_id FK -> Ouvrage, code_exemplaire)

## Notes

- `type` dans Ouvrage contraint les sous-types (`livre`, `BD`, `periodique`).
- Les attributs specifiques aux sous-types sont separes dans leurs tables.
- Les exemplaires sont normalises dans une table a part pour les livres (relation 1..N).
