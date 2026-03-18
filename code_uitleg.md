# Code Uitleg – Noorse Taaltrainer

Een overzicht van hoe de applicatie werkt, bestand voor bestand.

---

## Mappenstructuur

```
Website/noorse-taaltrainer/
├── css/
│   └── style.css          ← Alle opmaak / styling
├── pages/
│   ├── index.html          ← Startpagina (links naar login & register)
│   ├── login.html          ← Inlogformulier
│   ├── register.html       ← Registratieformulier
│   ├── dashboard.php       ← Hoofdscherm na inloggen
│   ├── profile.php         ← Profielpagina
│   ├── score.html          ← Scoreoverzicht na een oefening
│   └── logout.html         ← Uitlogpagina
└── php/
    ├── config.php          ← Databaseinstellingen
    ├── db.php              ← PDO-verbinding aanmaken
    ├── functions.php       ← Alle herbruikbare functies
    ├── login.php           ← Verwerkt het inlogformulier
    ├── register.php        ← Verwerkt het registratieformulier
    ├── exercise.php        ← De oefening zelf (vragen & antwoorden)
    ├── logout.php          ← Sessie vernietigen & uitloggen
    ├── change_level.php    ← Level handmatig aanpassen
    └── verwijder_account.php ← Account verwijderen
```

---

## Stroom van de app

```
index.html
  ├── → login.html  → login.php  → dashboard.php
  └── → register.html → register.php → dashboard.php

dashboard.php
  ├── → exercise.php  → score.html
  └── → profile.php  → exercise.php
                      → logout.php
```

---

## Bestand voor bestand

### `config.php`
Bevat de databasegegevens als constanten (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).  
Wordt ingeladen door `db.php`.

---

### `db.php`
Maakt één PDO-verbinding met de MySQL-database.  
Gebruik de functie `get_db()` in andere bestanden om de verbinding op te halen.  
De verbinding wordt maar één keer aangemaakt (via `static $pdo`).

---

### `register.php`
1. Controleert of het formulier via POST is verstuurd.
2. Valideert de invoer (gebruikersnaam ≥ 3 tekens, geldig e-mail, wachtwoord ≥ 6 tekens).
3. Controleert of de gebruikersnaam of het e-mailadres al bestaat.
4. Slaat het wachtwoord op als een **bcrypt-hash** (`password_hash`).
5. Maakt een sessie aan en stuurt door naar het dashboard.

---

### `login.php`
1. Controleert of het formulier via POST is verstuurd.
2. Zoekt de gebruiker op in de database op gebruikersnaam.
3. Vergelijkt het ingevoerde wachtwoord met de hash via `password_verify`.
4. Bij succes: sessie aanmaken met `user_id`, `username`, `level` en doorsturen naar het dashboard.
5. Bij fout: terugsturen naar de loginpagina met een foutmelding in de URL.

---

### `dashboard.php`
- Controleert of de gebruiker is ingelogd (anders → loginpagina).
- Haalt van de database op: gebruikersnaam, XP, level en totale score.
- Berekent het aantal sterren (`$stars = $level`, max 5).
- Berekent de voortgangsbalk: `($xp % 50) * 2` → percentage binnen het huidige level.
- Toont: welkomstbegroeting, voortgangsbalk, sterren, statistieken (level, XP, rondes).

---

### `profile.php`
- Zelfde logica als het dashboard voor gebruikersdata en sterren.
- Toont extra statistieken: totaal correct beantwoorde woorden, aantal gespeelde rondes.
- Bevat knoppen voor:
  - **Niveau aanpassen** (opent een modal → `change_level.php`)
  - **Account verwijderen** (opent een modal → `verwijder_account.php`)
  - **Uitloggen** → `logout.php`

---

### `functions.php`
Bevat alle herbruikbare PHP-functies:

| Functie | Wat het doet |
|---|---|
| `haal_level_van_gebruiker($user_id)` | Haalt het huidige level op uit de database (altijd tussen 1–5) |
| `haal_vragen_van_level($level)` | Haalt alle vragen én antwoordopties op voor een level, shufflet de antwoorden |
| `geef_totaal_vragen_van_level($level)` | Telt hoeveel vragen er in een level zitten |
| `sla_score_op($user_id, $score, $totaal)` | Schrijft de score van een ronde naar de `scores`-tabel |
| `update_level_na_ronde($user_id, $score, $totaal)` | Verhoogt het level +1 als alle vragen goed waren, en voegt XP toe (`score × 10`) |
| `toon_feedback($isGoed, $goedAntwoord)` | Geeft HTML-feedback terug (groen = goed, rood = fout + juist antwoord) |
| `genereer_antwoorden($keuzes, $geselecteerd)` | Genereert de radiobuttons voor de meerkeuze-antwoorden |

---

### `exercise.php`
Dit is het kernbestand van de app. Het beheert de oefening volledig via **PHP-sessies**.

**Sessievariabelen die gebruikt worden:**

| Variabele | Betekenis |
|---|---|
| `$_SESSION['q_level']` | Level waarvoor de vragen zijn geladen |
| `$_SESSION['q_vragen']` | Array van alle vragen van dit level |
| `$_SESSION['q_index']` | Welke vraag je nu ziet (0 = eerste) |
| `$_SESSION['q_score']` | Aantal goede antwoorden tot nu toe |
| `$_SESSION['q_wrong']` | Indices van fout beantwoorde vragen |
| `$_SESSION['q_phase']` | `'main'` = normale ronde, `'retry'` = herhaling van foute vragen |
| `$_SESSION['q_answered']` | Totaal aantal beantwoorde vragen (voor nauwkeurigheid%) |

**Verloop van een oefening:**

1. **Start** – Vragen worden geladen voor het huidige level en opgeslagen in de sessie.
2. **Vraag tonen** – De huidige vraag en antwoordopties worden weergegeven.
3. **Antwoord indienen** (POST `answer`) – Antwoord wordt gecheckt:
   - Goed → `q_score++`
   - Fout → vraagindex toegevoegd aan `q_wrong`
   - Feedback wordt getoond.
4. **Volgende vraag** (POST `volgende`) – `q_index` gaat omhoog.
5. **Einde hoofdronde** – Als er foute vragen zijn, start automatisch de **retry-fase** (`q_phase = 'retry'`), anders:
6. **Einde oefening** – Score wordt opgeslagen, level wordt bijgewerkt, doorgestuurd naar `score.html`.

---

### `score.html`
Toont de eindscore via URL-parameters: `?score=8&total=10`.  
Puur HTML, geen PHP.

---

### `logout.php`
Vernietigt de sessie en stuurt de gebruiker terug naar de loginpagina.

---

### `change_level.php`
Laat de gebruiker zijn eigen level handmatig aanpassen (via een modal op de profielpagina).  
Schrijft het nieuwe level direct naar de database.

---

### `verwijder_account.php`
Verwijdert het account van de ingelogde gebruiker uit de database en vernietigt daarna de sessie.

---

## Database tabellen (kort)

| Tabel | Inhoud |
|---|---|
| `gebruikers` | user_id, username, email, wachtwoord (hash), xp, level, total_score |
| `vragen` | question_id, question_text, level_id, xp_reward |
| `antwoordopties` | answer_id, question_id, answer_text, is_correct |
| `scores` | score_id, user_id, score, totaal, datum |
