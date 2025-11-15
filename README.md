# Documentation – Script de Migration et Insertion MySQL

## Objectif

Créer les tables de l’application et insérer des exemples de données pour tester le fonctionnement : `User`, `Admin`, `Challenge`, `Comment`, `Experience`, `Donations`, `Approvments`, `Quotation`.

---

## Connexion à la base

```php
$conn = new mysqli($servername, $username, $password, $dbname);
```

Vérification de la connexion et gestion des erreurs.

---

## Tables principales

| Table         | Clés primaires  | Relations principales                                                 |
| ------------- | --------------- | --------------------------------------------------------------------- |
| `User`        | `User_id`       | FK dans `Comment`, `Experience`, `Donations`                          |
| `Admin`       | `Admin_id`      | FK dans `Approvments`                                                 |
| `Challenge`   | `Challenge_id`  | FK dans `Comment`, `Experience`, `Approvments`, référence `Quotation` |
| `Comment`     | `Comment_id`    | FK vers `User`, `Challenge`                                           |
| `Experience`  | `Experience_id` | FK vers `User`, `Challenge`                                           |
| `Donations`   | `Donation_id`   | FK vers `User`                                                        |
| `Approvments` | `Approve_id`    | FK vers `Comment`, `Challenge`, `Admin`                               |
| `Quotation`   | `Quotation_id`  | FK dans `Challenge`                                                   |

---

## Particularités

* **UUID** pour toutes les clés primaires (`CHAR(36)`).
* `NeedHelp` dans `Experience` pour gérer les demandes d’aide par expérience.
* Valeurs par défaut pour les dates et les booléens.
* Génération d’UUID en PHP via `guidv4()`.
* Relations entre tables respectent l’intégrité référentielle via `FOREIGN KEY`.

---

## Insertion d’exemple

* Création d’un **User** avec mot de passe hashé (`password_hash`).
* Création d’un **Challenge**.
* Création d’une **Experience** associée au User et au Challenge.

---

## Notes

* Les UUID sont générés côté PHP.
* Les `FOREIGN KEY` assurent la cohérence des relations.
* Prévoir d’ajouter des insertions pour `Admin`, `Comment`, `Donations`, `Approvments`, `Quotation` selon les besoins.
