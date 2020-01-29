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
* class D\_example extends DBtable/\*TODO\*/\{ \(D\_example\.php:5\)
* Create new module \(Tools\.php:60\)

### High
* namespace t2\\core\\service \(Js\.php:10\)
* submit unchecked checkboxes\! \(cssdemo\.php:137\)
* submit unchecked radios\! \(cssdemo\.php:138\)
* Register modules\! \(dev\_tools\) \(index\.php:22\)

### (Unknown)
* require\_once ROOT\_DIR \. '/core/service/Js\.php';//TODO \(update\.php:12\)
* Service Function for ScrollToBottom \(update\.php:35\)
* Correction of escaping for inner ajax functions\! \(update\.php:36\)
* Use of "//" ends all functions in inner functions \(update\.php:37\)
* sleep\(3\); \(Core\_ajax\.php:25\)
* replace everywhere\! \(Config\.php:68\)
* return self::cfg\_http\_root\(\)\.'/'\.Files::relative\_path\(ROOT\_DIR, PROJECT\_ROOT\);//TODO\!\!\!\!\!\!\!\!\! \(Config\.php:73\)
* https://github\.com/blueimp/jQuery\-File\-Upload \(Includes\.php:24\)
* Better description? \(Strings\.php:25\)
* Better description? \(Strings\.php:35\)
* Better description? \(Strings\.php:45\)
* Better description? \(Strings\.php:73\)
* Namespace \(index\.php:9\)

### Medium
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:14\)
* Check, if class already exists \(Html\.php:73\)
* $page\->set\_focusFieldId \(Page\.php:21\)
* Der Typ muss PAGE heissen \(AJAX kann auch HTML sein\) \(Page\.php:229\)
* Detect platform\! \(Config\.php:37\)
* AJAX: URL can get too long\! \(Js\.php:30\)
* Logout \(Login\.php:22\)
* Not "Core\_database" but "Update\_database" \(in respective namespace\) \(Update\_database\.php:17\)
* Configure PROJECT\_ROOT \(Start\.php:75\)
* init rights \(Start\.php:116\)

### Low
* namespace \(index\.php:9\)
* subroutines für debug\_info und fehler\-handling \(Database\.php:207\)
* OR ADMIN \(\!$minimalistic\) \(Error\_\.php:92\)
* //Write to errorlog\-/warnings\-file:TODO:Write to errorlog\-/warnings\-file \(Error\_\.php:108\)
* make private? \(catch recursion?\) \(Error\_\.php:199\)
* Mark label if has tooltip \(Formfield\.php:97\)
* Make private \(\\t2\\core\\Page::$compiler\_messages\) \(Page\.php:52\)
* Make modules configuration an object\! \(Config\.php:78\)
* Determine Default\_values for given module \(Config\.php:191\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-Necessary?\) \(Config\.php:254\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:27\)
* backtrace: Trenner nach der ersten Zeile \(Debug\.php:293\)
* backtrace: Funktion mit anzeigen \(Debug\.php:294\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:31\)
* Database::destroy\(\);//TODO\-$prompting\_coreUser \(Install\_wizard\.php:102\)
* onclick zoom\-in als objekt \(core\.js:19\)

### Deprecated
* Use Error Class instead \(\\t2\\core\\Database::$exception\) \(Database\.php:51\)
* s\.o\. \(Database\.php:79\)
* Replace "Error\_quit" with "new Error\_\(\)" \(Error\_\.php:185\)
* Use only $compiler\_messages \(Page\.php:46\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:138\)
* \* @deprecated TODO Stattdessen: Includes::js\_jquery341\($page\); \(Page\.php:269\)
* onclick zoom\-in als objekt \(core\.js:9\)
