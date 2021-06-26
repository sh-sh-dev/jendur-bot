# Jendur-bot

Keep CLI (Tabchi) Bots away from your group!

## Usage

* Put prohibited words in the `db/prohibitedWords.json` file.
 
* Run `src/update.php`.

* Make `src/config.php` and the `db/` folder accessible by your webserver user (eg. `www-data`). Don't forget to give write access.

* Point webhook to the `src/main.php` file.

## How works

You can put utf-8 texts, emojis, and even regexes (like `09[0-9]{9}`) in the list of the prohibited words.

Each time someone joins your group, Jendur will check their bio and name. If they contain one of the prohibited words, Bot will log the kick reason (bio/name, and matched word) and then kicks the user. 
