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
* download third party packages
* check/reorg namespaces:
    * core/service
    * dev/templates/cssdemo.php
    * dev/api
    * core/api/Core_values.php
    * core/api/Core_database.php
    * dev/Tools.php
    * dev/Install_wizard.php
    * dev\new_module.php
* Module dev tools: Format TODOs, Passwordgenerator (, MD5, Base64)
* Autoloader?


    Config.php
    (16, 17) class Config {//TODO
    core.js
    (19, 4) * TODO: onclick zoom-in als objekt
    Database.php
    (130, 7) //TODO:Error_Fatal
    (231, 5) //TODO:subroutines für debug_info und fehler-handling
    (278, 7) //TODO: quit without error and pass to calling function to process install wizard
    Error_warn.php
    (22, 4) * TODO: New class Error_ that combines Error_fatal and Error_warn
    (84, 5) //TODO: $type und $debug_info verwursten?
    (86, 47) echo "Please contact your administrator.";//TODO:i18n
    (100, 24) if(Config::$DEVMODE/*TODO: OR ADMIN*/){
    (101, 6) //TODO:i18n
    (109, 28) //Write to errorlog-file(TODO):
    (126, 5) //TODO: Was ist mit SCRIPT_URI?
    (128, 5) //TODO: Build full request
    (154, 5) * TODO:private? machbar über error_fatal?
    (180, 5) //TODO: Copy from Error
    (181, 5) //TODO: stop mysql service, catch exception ("SQLSTATE\[HY000] \[2002] No such file or directory")
    Form.php
    (46, 81) public function __construct($CMD_ = null, $action = "", $submit_text = "Send"/*TODO:i18n*/, $method = "post") {
    Html.php
    (72, 38) public function addClass($class) {//TODO: Check, if class already exists
    Gui.php
    (15, 43) public function html_index(Page $page);//TODO:?????
    Page.php
    (31, 4) * TODO: $page->set_focusFieldId
    (173, 5) //TODO: is_string || instanceof Node
    (191, 5) * TODO: Make private and create add_message_error, add_message_info and add_message_confirm (add_message_ok)
    (245, 39) public function add_js_jquery341(){//TODO: Move to includes
    (249, 34) public function add_js_core(){//TODO: Move to includes
    (341, 6) //TODO: Try/catch __toString
    Html.php
    (20, 4) * TODO:Move service\Html to t2\core\Html
    Login.php
    (12, 21) namespace service;//TODO: move all namespaces to t2
    (28, 16) class Login {//TODO:Logout
    Templates.php
    (32, 5) //TODO: file_exists($filename, $fatal=true)
    User.php
    (29, 5) * TODO: revert logic ($halt_on_error=false)
    Admin.php
    (29, 51) const empty_path = '(Leave empty for default)';//TODO:i18n
    (49, 76) $form->add_field(new Formfield_text("path", "Path", self::empty_path));//TODO: register module in Config::$MODULES
    (64, 5) //TODO:Create module id from module name
    (65, 5) //TODO:Create absolute_path
    (66, 5) //TODO:Create relative_path
    (67, 5) //TODO
    Debug.php
    (199, 5) //TODO: Core includes
    (229, 5) * TODO: Trenner nach der ersten Zeile
    (230, 5) * TODO: Funktion mit anzeigen
    Install_wizard.php
    (14, 19) namespace admin;//TODO: move all namespaces to t2
    (32, 25) class Install_wizard {//TODO: Make an installer class that must be called explicitly (not via index)
    D_example.php
    (5, 34) class D_example extends DBtable/*TODO*/{
    index.php
    (11, 9) * @see TODO: Module Generator: \t2\admin\Admin::prompt_new_module()
    Tools.php
    (26, 51) const empty_path = '(Leave empty for default)';//TODO:i18n
    (46, 76) $form->add_field(new Formfield_text("path", "Path", self::empty_path));//TODO: register module in Config::$MODULES
    (47, 5) //TODO:Multiselection: Possible templates
    (62, 5) //TODO:Create module id from module name
    (63, 5) //TODO:Create absolute_path
    (64, 5) //TODO:Create relative_path
    (65, 5) //TODO
    Includes.php
    (13, 19) class Includes {//TODO