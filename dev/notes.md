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
* Module dev tools: Format TODOs(DONE), Passwordgenerator (, MD5, Base64) (index.php:20)
* class D_example extends DBtable/*TODO*/{ (D_example.php:5)
* New module: Multiselection: Possible templates (Tools.php:47)
* Create new module (Tools.php:65)
* download third party packages (\t2\core\Includes, includes/Includes.php) (Includes.php:12)
* class Includes {//TODO (Includes.php:13)

### High
* Error class for admins and developers (-> notify) and Error class for users (warnings, don't notify) (Error_.php:25)
* Was ist mit SCRIPT_URI? (Error_.php:152)
* Accept associative array for options (Formfield_radio.php:21)
* Move service\Html to t2\core\Html (Html.php:22)
* Warning, not error. (Login.php:88)
* init_updater (Windows) (Install_wizard.php:134)
* submit unchecked checkboxes! (cssdemo.php:123)
* submit unchecked radios! (cssdemo.php:124)

### (Unknown)
* CSS(bare): Schnickschnack (border-radius) raus! (all.css:90)

### Medium
* Admin features class (Admin.php:15)
* Submission of a form on a page called with "?key=val" results in an $_REQUEST array that contains key=val instead of submitted value (Form.php:26)
* Check, if class already exists (Html.php:73)
* $page->set_focusFieldId (Page.php:25)
* Der Typ muss PAGE heissen (AJAX kann auch HTML sein) (Page.php:238)
* Detect platform! (Config.php:41)
* AJAX: URL can get too long! (Js.php:30)
* Logout (Login.php:25)
* href_internal_mod (index.php:21)
* register module in Config::$MODULES (Tools.php:46)
* Create module id from module name (Tools.php:62)
* Create absolute_path (Tools.php:63)
* Create relative_path (Tools.php:64)
* init rights (Start.php:109)

### Low
* namespace (index.php:9)
* Updater: Get the (two) outputs by ajax (update.php:51)
* subroutines für debug_info und fehler-handling (Database.php:217)
* OR ADMIN (!$minimalistic) (Error_.php:100)
* i18n(!$minimalistic) (Error_.php:101)
* //Write to errorlog-file:TODO:Write to errorlog-file (Error_.php:117)
* Build full request (Error_.php:154)
* make private? (catch recursion?) (Error_.php:184)
* i18n (Form.php:48)
* Mark label if has tooltip (Formfield.php:103)
* Make private (\t2\core\Page::$compiler_messages) (Page.php:53)
* Move to includes (Page.php:275)
* Move to includes (Page.php:279)
* Make modules configuration an object! (Config.php:72)
* Determine Default_values for given module (Config.php:187)
* Database::destroy();//Make Page Standalone (TODO-Necessary?) (Config.php:252)
* file_exists($filename, $fatal=true) (Templates.php:32)
* backtrace: Trenner nach der ersten Zeile (Debug.php:293)
* backtrace: Funktion mit anzeigen (Debug.php:294)
* Install wizard: Prompt all field in one form (Install_wizard.php:35)
* Database::destroy();//TODO-$prompting_coreUser (Install_wizard.php:97)
* Describe regex projectwide (convert_todos.php:64)
* i18n (Tools.php:26)
* onclick zoom-in als objekt (core.js:19)
* Describe caller (update_template.cmd:10)
* Describe caller (update_template.sh:15)

### Deprecated
* Use Error Class instead (\t2\core\Database::$exception) (Database.php:55)
* s.o. (Database.php:84)
* Replace "Error_quit" with "new Error_()" (Error_.php:170)
* Use only $compiler_messages (Page.php:47)
* \t2\core\service\Config::get_default_value (Page.php:139)
* onclick zoom-in als objekt (core.js:9)
