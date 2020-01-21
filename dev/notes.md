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
* Is $DEVMODE (tethys/service/Config.php:30) set to FALSE?
* Update $core_queries (tethys/dev/Debug.php:24)
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------
### Feature requests
* download third party packages (\t2\core\Includes, includes/Includes.php)
* Module dev tools: Format TODOs(DONE), Passwordgenerator (, MD5, Base64)
* Autoloader? (-> \t2\dev\Debug::$core_includes, dev/Debug.php:36)
* Updater: git pull (admin/update.php)

### High
* Accept associative array for options (Formfield_radio.php:21)

### (Unknown)
* class Admin {//TODO (Admin.php:15)
* //TODO (update.php:30)
* Get the two outputs by ajax (update.php:40)
* check/reorg namespaces (Service.php:11)
* Error class for admins and developers (-> notify) and Error class for users (warnings, don't notify) (Error_.php:25)
* OR ADMIN (!$minimalistic) (Error_.php:100)
* i18n(!$minimalistic) (Error_.php:101)
* //Write to errorlog-file(TODO): (Error_.php:117)
* Was ist mit SCRIPT_URI? (Error_.php:152)
* Build full request (Error_.php:154)
* Mark label if has tooltip (Formfield.php:103)
* check/reorg namespaces (Arrays.php:12)
* Warning, not Error. (Config.php:59)
* aufräumen bei get config und default Berechnung für modules, http root und platform (Config.php:154)
* Necessary? (Config.php:198)
* Move service\Html to t2\core\Html (Html.php:22)
* move all namespaces to t2 (Login.php:12)
* Logout (Login.php:25)
* Warning, not error. (Login.php:88)
* file_exists($filename, $fatal=true) (Templates.php:32)
* Trenner nach der ersten Zeile (Debug.php:278)
* Funktion mit anzeigen (Debug.php:279)
* href_internal_mod (index.php:20)
* move all namespaces to t2//TODO:check/reorg namespaces (Install_wizard.php:14)
* Prompt all field in one form (Install_wizard.php:34)
* $prompting_coreUser (Install_wizard.php:98)
* submit unchecked checkboxes! (cssdemo.php:123)
* submit unchecked radios! (cssdemo.php:124)
* class D_example extends DBtable/*TODO*/{ (D_example.php:5)
* Module Generator: \t2\admin\Admin::prompt_new_module() (index.php:11)
* register module in Config::$MODULES (Tools.php:46)
* Multiselection: Possible templates (Tools.php:47)
* Create module id from module name (Tools.php:62)
* Create absolute_path (Tools.php:63)
* Create relative_path (Tools.php:64)
* //TODO (Tools.php:65)
* class Includes {//TODO (Includes.php:13)
* onclick zoom-in als objekt (core.js:19)
* init rights (Start.php:111)

### Medium
* Submission of a form on a page called with "?key=val" results in an $_REQUEST array that contains key=val instead of submitted value (Form.php:26)
* Check, if class already exists (Html.php:73)
* $page->set_focusFieldId (Page.php:30)
* Der Typ muss PAGE heissen (AJAX kann auch HTML sein) (Page.php:260)
* Detect platform! (Config.php:45)
* AJAX: URL can get too long! (Js.php:30)

### Low
* subroutines für debug_info und fehler-handling (Database.php:213)
* make private? (catch recursion?) (Error_.php:184)
* i18n (Form.php:48)
* Make private (\t2\core\Page::$compiler_messages) (Page.php:59)
* Move to includes (Page.php:297)
* Move to includes (Page.php:301)
* Describe regex projectwide (convert_todos.php:63)
* i18n (Tools.php:26)
* Describe caller (update.cmd:10)
* Describe caller (update_template.cmd:10)

### Deprecated
* Use Error Class instead (\t2\core\Database::$exception) (Database.php:55)
* s.o. (Database.php:84)
* Replace "Error_quit" with "new Error_()" (Error_.php:170)
* Use only $compiler_messages (Page.php:53)
* onclick zoom-in als objekt (core.js:9)
