You can contribute to NetWatcher and add more translations, or update the existing ones.
We use gettext, which makes really easy to implement more languages to NetWatcher:

* Create a new folder under `./locale/` named with the regional code ([list here](https://gist.github.com/jacobbubu/1836273)), and then a folder `LC_MESSAGES` inside it
* Copy the `messages.po` file from an existing translation folder into this new folder
* Edit it with a `.po` files editor (e.g. [POEdit](http://poedit.net)), choosing the new language for the catalog
* Translate all the strings and save the new catalog
* Edit `config/languages.json` adding in the array the new language (_Name => regional_code.utf8_)
* Finally, if you want your changes reflected in the repository, submit a pull request
