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

### Others
* class Admin {//TODO (Admin.php:15)
* Namespace (update.php:9)
* git pull (update.php:19)
* onclick zoom-in als objekt (core.js:19)
* check/reorg namespaces (Core_database.php:14)
* rename column 'user' in core_sessions (Core_database.php:51)
* check/reorg namespaces (Core_values.php:9)
* subroutines für debug_info und fehler-handling (Database.php:213)
* OR ADMIN (!$minimalistic) (Error_.php:99)
* i18n(!$minimalistic) (Error_.php:100)
* //Write to errorlog-file(TODO): (Error_.php:116)
* Was ist mit SCRIPT_URI? (Error_.php:147)
* Build full request (Error_.php:149)
* i18n (Form.php:44)
* Check, if class already exists (Html.php:71)
* $page->set_focusFieldId (Page.php:30)
* Make private (\t2\core\Page::$compiler_messages) (Page.php:59)
* Make private and use add_message_error, add_message_info and add_message_confirm (add_message_ok) (Page.php:218)
* Move to includes (Page.php:296)
* Move to includes (Page.php:300)
* check/reorg namespaces (Arrays.php:12)
* Warning, not Error. (Config.php:46)
* Necessary? (Config.php:184)
* Move service\Html to t2\core\Html (Html.php:22)
* move all namespaces to t2 (Login.php:12)
* Logout (Login.php:25)
* Warning, not error. (Login.php:88)
* file_exists($filename, $fatal=true) (Templates.php:32)
* check/reorg namespaces (Service.php:11)
* Trenner nach der ersten Zeile (Debug.php:278)
* Funktion mit anzeigen (Debug.php:279)
* move all namespaces to t2//TODO:check/reorg namespaces (Install_wizard.php:14)
* Prompt all field in one form (Install_wizard.php:33)
* $prompting_coreUser (Install_wizard.php:97)
* check/reorg namespaces (new_module.php:9)
* check/reorg namespaces (cssdemo.php:9)
* class D_example extends DBtable/*TODO*/{ (D_example.php:5)
* Module Generator: \t2\admin\Admin::prompt_new_module() (index.php:11)
* check/reorg namespaces (Tools.php:12)
* i18n (Tools.php:24)
* register module in Config::$MODULES (Tools.php:44)
* Multiselection: Possible templates (Tools.php:45)
* Create module id from module name (Tools.php:60)
* Create absolute_path (Tools.php:61)
* Create relative_path (Tools.php:62)
* //TODO (Tools.php:63)
* class Includes {//TODO (Includes.php:13)

### Deprecated
* onclick zoom-in als objekt (core.js:9)
* Use Error Class instead (\t2\core\Database::$exception) (Database.php:55)
* s.o. (Database.php:84)
* Replace "Error_quit" with "new Error_()" (Error_.php:165)
* Use only $compiler_messages (Page.php:53)
