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

### High
* Get title from DB \(Page\.php:143\)
* Autoloader: Look into modules folder \(Autoloader\.php:14\)
* Prompt HTTP\_PROJECT \(Config\.php:85\)
* Make modules configuration an object\! \(Config\.php:90\)
* submit unchecked checkboxes\! \(cssdemo\.php:179\)
* submit unchecked radios\! \(cssdemo\.php:180\)
* Register modules\! \(dev\_tools\) \(index\.php:28\)

### (Unknown)
* Move more functions here \(Database\_Service\.php:12\)
* Determine current module \(Html\.php:290\)
* It's all deprecated/going to config FILES\. Database shall only store db\-specific configuration \(Admin\.php:177\)
* Tooltip\! \(Admin\.php:189\)
* Just for demonstration: \(Core\_navigation\.php:72\)
* move to custom\_apis to module specific config \(db\) \(Core\_values\.php:16\)
* set table name with core prefix overriding getter\-method get\_table\_name \(D\_User\.php:15\)
* \- template replace: abfangen:":dev"=>"a",":dev1"=>"b" \(Strings\.php:72\)
* Look for errors that should be warnings \(Warning\.php:12\)
* $core\_includes: Plus navigation of each module \(Debug\.php:48\)
* Should ALWAYS be the redirect option\. User should store the config in his repo\. \(except the password\) \(see config layer concept\) \(Install\_wizard\.php:67\)
* call config\_server\_exclude \(config\.php:16\)
* project title, skin, modules, login\_html, session expires \(config\.php:28\)
* module\_root, module\_path, default\_api \(config\.php:30\)
* Wizard to create this file if it doesn't exist \(config\_server\_exclude\.php:17\)
* Server\-specific configuration \(config\_server\_exclude\.php:20\)
* Prompt for devmode in installer \(config\_server\_exclude\.php:23\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:29\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:31\)
* extension, http\_root, platform \(config\_server\_exclude\.php:37\)
* \* TODO \(My\_Navigation\.php:5\)
* Function in class Config for that conversion \(Tools\.php:60\)
* All classes\! \(Tools\.php:67\)

### Medium
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:14\)
* Check, if class already exists \(Html\.php:82\)
* $page\->set\_focusFieldId \(Page\.php:19\)
* Der Typ muss PAGE heissen \(AJAX kann auch HTML sein\) \(Page\.php:242\)
* Detect platform\! \(Config\.php:35\)
* AJAX: URL can get too long\! \(Js\.php:30\)
* Logout \(Login\.php:20\)
* Better description? \(Strings\.php:25\)
* Better description? \(Strings\.php:35\)
* Configure PROJECT\_ROOT \(Start\.php:114\)
* init rights \(Start\.php:170\)

### Low
* onclick zoom\-in als objekt \(core\.js:24\)
* //Write to errorlog\-/warnings\-file:TODO:Write to errorlog\-/warnings\-file \(Error\.php:118\)
* Mark label if has tooltip \(Formfield\.php:97\)
* Make private \(\\t2\\core\\Page::$compiler\_messages\) \(Page\.php:50\)
* Determine Default\_values for given module \(Config\.php:204\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-Necessary?\) \(Config\.php:282\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:26\)
* backtrace: Trenner nach der ersten Zeile \(Debug\.php:295\)
* backtrace: Funktion mit anzeigen \(Debug\.php:296\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:28\)
* Database::destroy\(\);//TODO\-$prompting\_coreUser \(Install\_wizard\.php:100\)
* jsdemo \(stub\_io\_js\.php:18\)
* formdemo \(stub\_io\_php\.php:19\)

### Deprecated
* onclick zoom\-in als objekt \(core\.js:14\)
* Use Database\_Service instead\. \(Database\.php:127\)
* \* @deprecated TODO \(Database\.php:295\)
* Use only $compiler\_messages \(Page\.php:44\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:151\)
* \* @deprecated TODO Stattdessen: Includes::js\_jquery341\($page\); \(Page\.php:296\)
* \* @deprecated TODO \(Config\.php:66\)
* Module as a class\! \(Config\.php:327\)
* \* @deprecated TODO \(Strings\.php:77\)
* \* @deprecated TODO \(Strings\.php:88\)
* Include media queries in global file \(Stylesheet\.php:18\)
* Kann weg, es bleibt nur die URL \(Stylesheet\.php:28\)
