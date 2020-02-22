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
* onclick zoom\-in als objekt \(core\.js:24\)
* //Write to errorlog\-/warnings\-file \(TODO\) \(Error\.php:118\)
* $page\->set\_focusFieldId \(Page\.php:19\)
* Feature: Wizard: Prompt HTTP\_PROJECT \(Config\.php:77\)
* Make modules configuration an object? \(Config\.php:82\)
* Logout \(Login\.php:20\)
* file\_exists\($filename, $fatal=true\) \(Templates\.php:26\)
* backtrace: Trenner nach der ersten Zeile/highlight passed depth \(Debug\.php:295\)
* backtrace: Funktion mit anzeigen \(Debug\.php:296\)
* Install wizard: Prompt all field in one form \(Install\_wizard\.php:28\)
* Feature: Install wizard: Configure PROJECT\_ROOT \(Start\.php:118\)
* init rights \(Start\.php:174\)

### High
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value \(Form\.php:11\)
* It's all deprecated/going to config FILES\. Database shall only store db\-specific configuration \(Admin\.php:177\)
* move to custom\_apis to module specific config \(Core\_values\.php:16\)
* Make private \(\\t2\\core\\Page::$compiler\_messages\) \(Page\.php:50\)
* Get title from navigation \(Page\.php:143\)
* Der Typ muss PAGE heissen \(AJAX kann auch HTML sein\) \(Page\.php:242\)
* Autoloader: Look into modules folder \(Autoloader\.php:14\)
* Database::destroy\(\);//Make Page Standalone \(TODO\-check out\!\) \(Config\.php:285\)
* Class config\_core with getters for every cfg\-value \(Config\_core\.php:12\)
* \- template replace: abfangen:":dev"=>"a",":dev1"=>"b" \(Strings\.php:72\)
* Look for errors that should be warnings \(Warning\.php:12\)
* $core\_includes: Plus navigation of each module \(Debug\.php:48\)
* submit unchecked checkboxes\! \(cssdemo\.php:179\)
* submit unchecked radios\! \(cssdemo\.php:180\)
* Register modules\! \(dev\_tools\) \(index\.php:28\)
* Should ALWAYS be the redirect option\. User should store the config in his repo\. \(except the password\) \(see config layer concept\) \(Install\_wizard\.php:67\)
* Database::destroy\(\);//TODO\-check out\! $prompting\_coreUser \(Install\_wizard\.php:100\)
* call config\_server\_exclude \(config\.php:16\)
* project title, skin, modules, login\_html, session expires \(config\.php:28\)
* module\_root, module\_path, default\_api \(config\.php:30\)
* Wizard to create this file if it doesn't exist \(config\_server\_exclude\.php:17\)
* Server\-specific configuration \(config\_server\_exclude\.php:20\)
* T2 is a SUBmodule, so the first root is the parent project's root \(config\_server\_exclude\.php:29\)
* The directory pointing to T2 is PROJECT SPECIFIC relative to this root\. See HDDPATH\_T2 in config\.php \(config\_server\_exclude\.php:31\)
* extension, http\_root, platform \(config\_server\_exclude\.php:37\)

### Medium
* Move more functions here \(Database\_Service\.php:12\)
* add css class: Check, if class already exists \(Html\.php:80\)
* Determine current module \(Html\.php:289\)
* Detect platform\! \(Config\.php:36\)
* There must be native functions\! \(Strings\.php:25\)
* There must be native functions\! \(Strings\.php:35\)
* Prompt for devmode in installer \(config\_server\_exclude\.php:23\)

### Low
* Mark label if has tooltip \(Formfield\.php:97\)
* Just for demonstration: \(Core\_navigation\.php:72\)
* set table name with core prefix overriding getter\-method get\_table\_name \(D\_User\.php:15\)
* Determine Default\_values for given module \(Config\.php:196\)
* replace t2 specific path variablesFunction in class Config for that conversion \(Tools\.php:60\)
* create new module: copy all api classes \(Tools\.php:67\)

### Deprecated
* onclick zoom\-in als objekt \(core\.js:14\)
* Use Database\_Service instead\. \(Database\.php:127\)
* \* @deprecated TODO \(Database\.php:295\)
* Use only $compiler\_messages \(Page\.php:44\)
* \\t2\\core\\service\\Config::get\_default\_value \(Page\.php:151\)
* \* @deprecated TODO Stattdessen: Includes::js\_jquery341\($page\); \(Page\.php:296\)
* \* @deprecated TODO \(Config\.php:67\)
* Module as a class\! \(Config\.php:330\)
* \* @deprecated TODO \(Strings\.php:77\)
* \* @deprecated TODO \(Strings\.php:88\)
