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
* Check license notes
* Paste remaining TODOs into this file
* Update $core_queries (/dev/Debug.php:21) and $core_includes (/dev/Debug.php:31)
* Is $DEVMODE (/core/service/Config.php:22) set to FALSE?
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------
### Feature requests
* Reporting for client\-side errors \(core\.js:60\)
* //Write to errorlog\-/warnings\-file \(TODO\) \(Error\.php:118\)
* $page\->set\_focusFieldId \(Page\.php:20\)
* Feature: Wizard: Prompt HTTP\_PROJECT \(Config\.php:91\)
* Make modules configuration an object? \(Config\.php:98\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:26\)
* backtrace: Funktion mit anzeigen \(Debug\.php:322\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:27\)
* Feature: Install wizard: Configure PROJECT\_ROOT \(Start\.php:121\)
* init rights \(Start\.php:181\)

### High
* Start Wizard \(config\.php:10\)
* project title, skin, modules, login\_html, session expires \(config\.php:25\)
* module\_root, module\_path, default\_api \(config\.php:27\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:22\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:24\)
* extension, http\_root, platform \(config\_server\_exclude\.php:30\)
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:11\)
* Formfield\_file \!\! \(Form\.php:17\)
* It's all deprecated/going to config FILES\. Database shall only store db\-specific configuration \(Admin\.php:177\)
* move to custom\_apis to module specific config \(Core\_values\.php:16\)
* Autoloader: Look into modules folder \(Autoloader\.php:14\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-check out\!\) \(Config\.php:329\)
* Class config\_core with getters for every cfg\-value \(Config\_core\.php:12\)
* \- template replace: abfangen:":dev"=>"a",":dev1"=>"b" \(Strings\.php:66\)
* $core\_includes: Plus navigation of each module \(Debug\.php:56\)
* submit unchecked checkboxes\! \(formdemo\.php:40\)
* submit unchecked radios\! \(formdemo\.php:41\)
* Database::destroy\(\);//TODO\-check out\! $prompting\_coreUser \(Install\_wizard\.php:99\)
* Start Wizard \(config\.php:19\)
* module\_root, module\_path, default\_api \(config\.php:34\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:29\)
* dev\_tools: t2 is not a submodule of a module\! \(config\_server\_exclude\.php:30\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:32\)
* extension, http\_root, platform \(config\_server\_exclude\.php:38\)

### (Unknown)
* Default/Login Title \(Page\.php:138\)
* Require weg\! \(cssdemo\.php:14\)
* Page should not be instantiated multiple times\! \(cssdemo\.php:15\)
* Warum findet der Autoloader das nicht? \(cssdemo\.php:16\)

### Medium
* Better throw an exception \(Database\.php:200\)
* Move more functions here \(DB\.php:12\)
* add css class: Check, if class already exists \(Html\.php:80\)
* Determine current module \(Html\.php:303\)
* There must be native functions\! \(Strings\.php:22\)
* There must be native functions\! \(Strings\.php:32\)
* Navigation:devCSS \(Start\.php:57\)

### Low
* Mark label if has tooltip \(Formfield\.php:98\)
* Determine Default\_values for given module \(Config\.php:215\)
* replace t2 specific path variablesFunction in class Config for that conversion \(Tools\.php:60\)
* create new module: copy all api classes \(Tools\.php:67\)

### Deprecated
* /\*\* @deprecated TODO \*/ \(config\_server\_exclude\.php:19\)
* Use Database\_Service instead\. \(Database\.php:129\)
* \* @deprecated TODO \(Page\.php:116\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:163\)
* \* @deprecated TODO \(Config\.php:81\)
* Module as a class\! \(Config\.php:374\)
* \* @deprecated TODO \(Strings\.php:71\)
* \* @deprecated TODO \(Strings\.php:82\)
* /\*\* @deprecated TODO \*/ \(config\_server\_exclude\.php:26\)
