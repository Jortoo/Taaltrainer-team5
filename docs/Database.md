# Database Plan – Taalleer App

## Soorten data

* Gebruiker: naam, wachtwoord (Account)
* Score / XP / Level
* Vragen en antwoorden (multiple choice)

---

## Users (Gebruikers)

| veld              | type    | beschrijving               |
| ----------------- | ------- | -------------------------- |
| user_id           | INT     | Unieke gebruiker           |
| username          | VARCHAR | Username van de gebruiker  |
| email             | VARCHAR | Email gebruiker            |
| wachtwoord        | VARCHAR | Versleuteld wachtwoord     |
| datum_registratie | DATE    | Datum van registratie      |
| total_score       | INT     | Totaal behaalde score      |
| xp                | INT     | Aantal XP van de gebruiker |
| level             | INT     | De level van de speler     |

---

## Levels

| veld            | type | beschrijving               |
| --------------- | ---- | -------------------------- |
| level_id        | INT  | Uniek level                |
| question_amount | INT  | Aantal vragen in dit level |
| xp_reward       | INT  | XP reward voor het level   |

---

## Questions (Vragen)

| veld          | type    | beschrijving                  |
| ------------- | ------- | ----------------------------- |
| question_id   | INT     | Unieke vraag                  |
| level_id      | INT     | Bij welk level de vraag hoort |
| difficulty    | VARCHAR | De moeilijkheid van de vraag  |
| question_text | TEXT    | De vraag zelf                 |
| xp_reward     | INT     | XP voor correcte antwoord     |

---

## Answers (Antwoordopties)

| veld        | type    | beschrijving                  |
| ----------- | ------- | ----------------------------- |
| answer_id   | INT     | Uniek antwoord                |
| question_id | INT     | Verwijzing naar de vraag      |
| answer_text | VARCHAR | Antwoordoptie                 |
| is_correct  | BOOLEAN | Of dit het juiste antwoord is |

---

