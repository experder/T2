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
* Autoloader? (Debug.php:38)
* Module dev tools: Format TODOs(DONE), Passwordgenerator (, MD5, Base64) (index.php:21)
* class D\_example extends DBtable/\*TODO\*/{ (D\_example.php:5)
* New module: Multiselection: Possible templates (Tools.php:47)
* Create new module (Tools.php:65)

### High
* Error class for admins and developers (-> notify) and Error class for users (warnings, don't notify) (Error\_.php:25)
* Was ist mit SCRIPT\_URI? (Error\_.php:152)
* Accept associative array for options (Formfield\_radio.php:21)
* Move to includes (Page.php:271)
* Move service\\Html to t2\\core\\Html (Html.php:22)
* namespace t2\\core\\service (Js.php:10)
* Warning, not error. (Login.php:88)
* https://www.markdownguide.org/basic-syntax/#characters-you-can-escape (Strings.php:47)
* submit unchecked checkboxes! (cssdemo.php:141)
* submit unchecked radios! (cssdemo.php:142)

### (Unknown)
* Überarbeiten (Database.php:103)
* include jquery (Includes.php:29)
* not set->error (Install\_wizard.php:119)

### Medium
* Admin features class (Admin.php:15)
* Submission of a form on a page called with "?key=val" results in an $\_REQUEST array that contains key=val instead of submitted value (Form.php:27)
* Check, if class already exists (Html.php:73)
* $page->set\_focusFieldId (Page.php:25)
* Der Typ muss PAGE heissen (AJAX kann auch HTML sein) (Page.php:234)
* Detect platform! (Config.php:41)
* AJAX: URL can get too long! (Js.php:30)
* Logout (Login.php:25)
* href\_internal\_mod (index.php:22)
* register module in Config::$MODULES (Tools.php:46)
* Create module id from module name (Tools.php:62)
* Create absolute\_path (Tools.php:63)
* Create relative\_path (Tools.php:64)
* Configure PROJECT\_ROOT (Start.php:73)
* init rights (Start.php:116)

### Low
* namespace (index.php:9)
* Updater: Get the (two) outputs by ajax (update.php:52)
* subroutines für debug\_info und fehler-handling (Database.php:217)
* OR ADMIN (!$minimalistic) (Error\_.php:100)
* i18n(!$minimalistic) (Error\_.php:101)
* //Write to errorlog-file:TODO:Write to errorlog-file (Error\_.php:117)
* Build full request (Error\_.php:154)
* make private? (catch recursion?) (Error\_.php:184)
* i18n (Form.php:49)
* Mark label if has tooltip (Formfield.php:103)
* Make private (\\t2\\core\\Page::$compiler\_messages) (Page.php:56)
* Make modules configuration an object! (Config.php:72)
* Determine Default\_values for given module (Config.php:187)
* Database::destroy();//Make Page Standalone (TODO-Necessary?) (Config.php:252)
* file\_exists($filename, $fatal=true) (Templates.php:32)
* backtrace: Trenner nach der ersten Zeile (Debug.php:293)
* backtrace: Funktion mit anzeigen (Debug.php:294)
* Install wizard: Prompt all field in one form (Install\_wizard.php:38)
* Database::destroy();//TODO-$prompting\_coreUser (Install\_wizard.php:109)
* Describe regex projectwide (convert\_todos.php:65)
* i18n (Tools.php:26)
* onclick zoom-in als objekt (core.js:19)

### Deprecated
* Use Error Class instead (\\t2\\core\\Database::$exception) (Database.php:55)
* s.o. (Database.php:84)
* Replace "Error\_quit" with "new Error\_()" (Error\_.php:170)
* Use only $compiler\_messages (Page.php:50)
* \\t2\\core\\service\\Config::get\_default\_value (Page.php:142)
* onclick zoom-in als objekt (core.js:9)
