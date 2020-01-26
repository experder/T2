Developers
==========

[[Regex](https://github.com/experder/T2/blob/master/help/dev_regex.md)]

Release
-------
Before merging `dev` to `master`:
* Check for compiler warnings, PHPdoc
* Check for out commented and unused code
* Check code format
* Check language (translate german to english), comment code
* Check if there's license note in each file
* Paste remaining TODOs into this file
* Is $DEVMODE (/core/service/Config.php:28) set to FALSE?
* Update $core_queries (/dev/Debug.php:27)
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------
### Feature requests
* Module dev tools: Format TODOs\(DONE\), Passwordgenerator \(, MD5, Base64\) \(index\.php:22\)
* class D\_example extends DBtable/\*TODO\*/\{ \(D\_example\.php:5\)
* Create new module \(Tools\.php:60\)

### High
* Move to includes \(Page\.php:267\)
* Move service\\Html to t2\\core\\Html \(Html\.php:12\)
* include jquery \(Includes\.php:25\)
* namespace t2\\core\\service \(Js\.php:10\)
* submit unchecked checkboxes\! \(cssdemo\.php:137\)
* submit unchecked radios\! \(cssdemo\.php:138\)
* Register modules\! \(dev\_tools\) \(index\.php:20\)

### Medium
* Admin features class \(Admin\.php:15\)
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:14\)
* Check, if class already exists \(Html\.php:67\)
* $page\->set\_focusFieldId \(Page\.php:20\)
* Der Typ muss PAGE heissen \(AJAX kann auch HTML sein\) \(Page\.php:228\)
* Detect platform\! \(Config\.php:37\)
* AJAX: URL can get too long\! \(Js\.php:30\)
* Logout \(Login\.php:21\)
* Configure PROJECT\_ROOT \(Start\.php:72\)
* init rights \(Start\.php:112\)

### Low
* Make tools available for everyone \(convert\_todos\.php:22\)
* Describe regex projectwide \(convert\_todos\.php:70\)
* namespace \(index\.php:9\)
* Updater: Get the \(two\) outputs by ajax \(update\.php:49\)
* subroutines für debug\_info und fehler\-handling \(Database\.php:207\)
* OR ADMIN \(\!$minimalistic\) \(Error\_\.php:94\)
* //Write to errorlog\-/warnings\-file:TODO:Write to errorlog\-/warnings\-file \(Error\_\.php:110\)
* make private? \(catch recursion?\) \(Error\_\.php:201\)
* Mark label if has tooltip \(Formfield\.php:96\)
* Make private \(\\t2\\core\\Page::$compiler\_messages\) \(Page\.php:51\)
* Make modules configuration an object\! \(Config\.php:68\)
* Determine Default\_values for given module \(Config\.php:181\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-Necessary?\) \(Config\.php:244\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:27\)
* backtrace: Trenner nach der ersten Zeile \(Debug\.php:293\)
* backtrace: Funktion mit anzeigen \(Debug\.php:294\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:31\)
* Database::destroy\(\);//TODO\-$prompting\_coreUser \(Install\_wizard\.php:102\)
* onclick zoom\-in als objekt \(core\.js:19\)

### Deprecated
* Use Error Class instead \(\\t2\\core\\Database::$exception\) \(Database\.php:51\)
* s\.o\. \(Database\.php:79\)
* Replace "Error\_quit" with "new Error\_\(\)" \(Error\_\.php:187\)
* Use only $compiler\_messages \(Page\.php:45\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:137\)
* onclick zoom\-in als objekt \(core\.js:9\)
