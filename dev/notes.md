Developers
==========

[[Regex](../help/dev_regex.md)]

Release
-------
Before merging `dev` to `master`:
* Check for compiler warnings, PHPdoc
* Check for out commented and unused code
* Check code format
* Check language (translate german to english), comment code
* Check if there's license note in each file
* Paste remaining TODOs into this file
* Update $core_queries (/dev/Debug.php:22) and $core_includes (/dev/Debug.php:31)
* Is $DEVMODE (/core/service/Config.php:23) set to FALSE?
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------
### Feature requests
* File upload \(Includes\.php:22\)
* class D\_example extends DBtable/\*TODO\*/\{ \(D\_example\.php:5\)
* Create new module \(Tools\.php:60\)

### High
* Prompt HTTP\_PROJECT \(Config\.php:73\)
* submit unchecked checkboxes\! \(cssdemo\.php:179\)
* submit unchecked radios\! \(cssdemo\.php:180\)
* Register modules\! \(dev\_tools\) \(index\.php:24\)

### Medium
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:14\)
* Check, if class already exists \(Html\.php:77\)
* $page\->set\_focusFieldId \(Page\.php:20\)
* Der Typ muss PAGE heissen \(AJAX kann auch HTML sein\) \(Page\.php:230\)
* Detect platform\! \(Config\.php:37\)
* AJAX: URL can get too long\! \(Js\.php:30\)
* Logout \(Login\.php:22\)
* Better description? \(Strings\.php:25\)
* Better description? \(Strings\.php:35\)
* Better description? \(Strings\.php:75\)
* Configure PROJECT\_ROOT \(Start\.php:75\)
* init rights \(Start\.php:116\)

### Low
* namespace of admin index \(index\.php:9\)
* ajax\_post\_to\_id \(update\.php:49\)
* ajax\_post\_to\_id \(update\.php:64\)
* onclick zoom\-in als objekt \(core\.js:24\)
* subroutines für debug\_info und fehler\-handling \(Database\.php:207\)
* OR ADMIN \(\!$minimalistic\) \(Error\.php:90\)
* //Write to errorlog\-/warnings\-file:TODO:Write to errorlog\-/warnings\-file \(Error\.php:112\)
* make private? \(catch recursion?\) \(Error\.php:203\)
* Mark label if has tooltip \(Formfield\.php:97\)
* Make private \(\\t2\\core\\Page::$compiler\_messages\) \(Page\.php:51\)
* replace everywhere\! \(Config\.php:68\)
* Make modules configuration an object\! \(Config\.php:78\)
* Determine Default\_values for given module \(Config\.php:191\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-Necessary?\) \(Config\.php:254\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:27\)
* backtrace: Trenner nach der ersten Zeile \(Debug\.php:294\)
* backtrace: Funktion mit anzeigen \(Debug\.php:295\)
* Namespace of dev index \(index\.php:9\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:31\)
* Database::destroy\(\);//TODO\-$prompting\_coreUser \(Install\_wizard\.php:102\)
* jsdemo \(stub\_io\_js\.php:18\)
* formdemo \(stub\_io\_php\.php:19\)

### Deprecated
* onclick zoom\-in als objekt \(core\.js:14\)
* Use Error Class instead \(\\t2\\core\\Database::$exception\) \(Database\.php:51\)
* s\.o\. \(Database\.php:79\)
* Replace "Error\_quit" with "new Error\(\)" \(Error\.php:189\)
* Use only $compiler\_messages \(Page\.php:45\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:139\)
* \* @deprecated TODO Stattdessen: Includes::js\_jquery341\($page\); \(Page\.php:280\)
* Include media queries in global file \(Stylesheet\.php:18\)
* Kann weg, es bleibt nur die URL \(Stylesheet\.php:28\)
