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
    Admin.php
    (26, 51) const empty_path = '(Leave empty for default)';//TODO:i18n
    (46, 76) $form->add_field(new Formfield_text("path", "Path", self::empty_path));//TODO: register module in Config::$MODULES
    (61, 5) //TODO:Create module id from module name
    (62, 5) //TODO:Create absolute_path
    (63, 5) //TODO:Create relative_path
    (64, 5) //TODO
    Install_wizard.php
    (14, 19) namespace admin;//TODO: move all namespaces to t2
    (32, 25) class Install_wizard {//TODO: Make an installer class that must be called explicitly (not via index)
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
    Page.php
    (31, 4) * TODO: $page->set_focusFieldId
    (190, 5) * TODO: Make private and create add_message_error, add_message_info and add_message_confirm (add_message_ok)
    Debug.php
    (123, 5) * TODO: Trenner nach der ersten Zeile
    (124, 5) * TODO: Funktion mit anzeigen
    Html.php
    (20, 4) * TODO:Move service\Html to t2\core\Html
    Login.php
    (12, 21) namespace service;//TODO: move all namespaces to t2
    (28, 16) class Login {//TODO:Logout
    Templates.php
    (32, 5) //TODO: file_exists($filename, $fatal=true)
    User.php
    (29, 5) * TODO: revert logic ($halt_on_error=false)
    index.php
    (11, 9) * @see TODO: Module Generator: \t2\admin\Admin::prompt_new_module()