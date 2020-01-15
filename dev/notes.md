Developers
==========

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
* Module dev tools: Format TODOs, Passwordgenerator (, MD5, Base64)
* Autoloader? (-> \t2\dev\Debug::$core_includes, dev/Debug.php:36)
* Updater: git pull (admin/update.php)


    Admin.php
    (15, 16) class Admin {//TODO
    core.js
    (9, 16) * @deprecated TODO: onclick zoom-in als objekt
    (19, 4) * TODO: onclick zoom-in als objekt
    Core_database.php
    (14, 19) namespace admin;//TODO:check/reorg namespaces
    (51, 33) `user` int(11) NOT NULL,-- TODO:rename column 'user' in core_sessions
    Core_values.php
    (9, 19) namespace admin;//TODO:check/reorg namespaces
    Database.php
    (55, 17) * @deprecated TODO: Use Error Class instead (\t2\core\Database::$exception)
    (84, 17) * @deprecated TODO: s.o.
    (213, 5) //TODO:subroutines für debug_info und fehler-handling
    Error_.php
    (99, 24) if(Config::$DEVMODE/*TODO: OR ADMIN (!$minimalistic)*/){
    (100, 6) //TODO:i18n(!$minimalistic)
    (116, 28) //Write to errorlog-file(TODO):
    (147, 5) //TODO: Was ist mit SCRIPT_URI?
    (149, 5) //TODO: Build full request
    (165, 17) * @deprecated TODO: Replace "Error_quit" with "new Error_()"
    Form.php
    (46, 81) public function __construct($CMD_ = null, $action = "", $submit_text = "Send"/*TODO:i18n*/, $method = "post") {
    Html.php
    (72, 38) public function addClass($class) {//TODO: Check, if class already exists
    Page.php
    (31, 4) * TODO: $page->set_focusFieldId
    (54, 17) * @deprecated TODO: Use only $compiler_messages
    (60, 5) * TODO: Make private (\t2\core\Page::$compiler_messages)
    (215, 5) * TODO: Make private and use add_message_error, add_message_info and add_message_confirm (add_message_ok)
    (293, 39) public function add_js_jquery341(){//TODO: Move to includes
    (297, 34) public function add_js_core(){//TODO: Move to includes
    Arrays.php
    (12, 21) namespace service;//TODO:check/reorg namespaces
    Config.php
    (46, 7) //TODO:Warning, not Error.
    (184, 49) Database::destroy();//Make Page Standalone (TODO: Necessary?)
    Html.php
    (22, 4) * TODO:Move service\Html to t2\core\Html
    Login.php
    (12, 21) namespace service;//TODO: move all namespaces to t2
    (26, 16) class Login {//TODO:Logout
    (89, 6) //TODO: Warning, not error.
    Templates.php
    (32, 5) //TODO: file_exists($filename, $fatal=true)
    Service.php
    (11, 20) namespace t2\api;//TODO:check/reorg namespaces
    Debug.php
    (279, 5) * TODO: Trenner nach der ersten Zeile
    (280, 5) * TODO: Funktion mit anzeigen
    Install_wizard.php
    (14, 19) namespace admin;//TODO: move all namespaces to t2//TODO:check/reorg namespaces
    (33, 25) class Install_wizard {//TODO: Prompt all field in one form
    (97, 25) Database::destroy();//TODO: $prompting_coreUser
    new_module.php
    (9, 22) namespace t2\admin;//TODO:check/reorg namespaces
    cssdemo.php
    (9, 3) //TODO:check/reorg namespaces
    D_example.php
    (5, 34) class D_example extends DBtable/*TODO*/{
    index.php
    (11, 9) * @see TODO: Module Generator: \t2\admin\Admin::prompt_new_module()
    Tools.php
    (12, 22) namespace t2\admin;//TODO:check/reorg namespaces
    (24, 51) const empty_path = '(Leave empty for default)';//TODO:i18n
    (44, 76) $form->add_field(new Formfield_text("path", "Path", self::empty_path));//TODO: register module in Config::$MODULES
    (45, 5) //TODO:Multiselection: Possible templates
    (60, 5) //TODO:Create module id from module name
    (61, 5) //TODO:Create absolute_path
    (62, 5) //TODO:Create relative_path
    (63, 5) //TODO
    Includes.php
    (13, 19) class Includes {//TODO