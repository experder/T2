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
* onclick zoom\-in als objekt \(core\.js:24\)
* Reporting for client\-side errors \(core\.js:78\)
* //Write to errorlog\-/warnings\-file \(TODO\) \(Error\.php:118\)
* $page\->set\_focusFieldId \(Page\.php:19\)
* Feature: Wizard: Prompt HTTP\_PROJECT \(Config\.php:79\)
* Make modules configuration an object? \(Config\.php:84\)
* Logout \(Login\.php:20\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:26\)
* backtrace: Trenner nach der ersten Zeile/highlight passed depth \(Debug\.php:305\)
* backtrace: Funktion mit anzeigen \(Debug\.php:306\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:28\)
* Feature: Install wizard: Configure PROJECT\_ROOT \(Start\.php:117\)
* init rights \(Start\.php:177\)

### High
* project title, skin, modules, login\_html, session expires \(config\.php:25\)
* module\_root, module\_path, default\_api \(config\.php:27\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:21\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:23\)
* extension, http\_root, platform \(config\_server\_exclude\.php:29\)
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:11\)
* It's all deprecated/going to config FILES\. Database shall only store db\-specific configuration \(Admin\.php:177\)
* move to custom\_apis to module specific config \(Core\_values\.php:16\)
* Get title from navigation \(Page\.php:124\)
* Autoloader: Look into modules folder \(Autoloader\.php:14\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-check out\!\) \(Config\.php:305\)
* Class config\_core with getters for every cfg\-value \(Config\_core\.php:12\)
* \- template replace: abfangen:":dev"=>"a",":dev1"=>"b" \(Strings\.php:69\)
* Look for errors that should be warnings \(Warning\.php:12\)
* $core\_includes: Plus navigation of each module \(Debug\.php:53\)
* submit unchecked checkboxes\! \(cssdemo\.php:179\)
* submit unchecked radios\! \(cssdemo\.php:180\)
* Database::destroy\(\);//TODO\-check out\! $prompting\_coreUser \(Install\_wizard\.php:100\)
* module\_root, module\_path, default\_api \(config\.php:36\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:29\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:31\)
* extension, http\_root, platform \(config\_server\_exclude\.php:37\)
* Start Wizard \(config\.php:10\)
* Start Wizard \(config\.php:19\)

### Medium
* Move more functions here \(Database\_Service\.php:12\)
* add css class: Check, if class already exists \(Html\.php:80\)
* Determine current module \(Html\.php:289\)
* Detect platform\! \(Config\.php:38\)
* There must be native functions\! \(Strings\.php:22\)
* There must be native functions\! \(Strings\.php:32\)

### Low
* Mark label if has tooltip \(Formfield\.php:94\)
* set table name with core prefix overriding getter\-method get\_table\_name \(D\_User\.php:15\)
* Determine Default\_values for given module \(Config\.php:198\)
* replace t2 specific path variablesFunction in class Config for that conversion \(Tools\.php:60\)
* create new module: copy all api classes \(Tools\.php:67\)

### Deprecated
* /\*\* @deprecated TODO \*/ \(config\_server\_exclude\.php:18\)
* onclick zoom\-in als objekt \(core\.js:14\)
* Use Database\_Service instead\. \(Database\.php:127\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:132\)
* \* @deprecated TODO Stattdessen: Includes::js\_jquery341\($page\); \(Page\.php:307\)
* \* @deprecated TODO \(Config\.php:69\)
* Module as a class\! \(Config\.php:350\)
* \* @deprecated TODO \(Strings\.php:74\)
* \* @deprecated TODO \(Strings\.php:85\)
* /\*\* @deprecated TODO \*/ \(config\_server\_exclude\.php:26\)
