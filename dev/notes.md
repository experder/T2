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
* Is \service\Config::$DEVMODE set to FALSE?
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------
    Install_wizard.php
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
    (100, 24) if(Config::$DEVMODE/*TODO: OR ADMIN*/){
    (108, 28) //Write to errorlog-file(TODO):
    (125, 5) //TODO: Was ist mit SCRIPT_URI?
    (127, 5) //TODO: Build full request
    (153, 5) * TODO:private? machbar über error_fatal?
    (179, 5) //TODO: Copy from Error
    (180, 5) //TODO: stop mysql service, catch exception ("SQLSTATE\[HY000] \[2002] No such file or directory")
    Html.php
    (71, 38) public function addClass($class) {//TODO: Check, if class already exists
    Page.php
    (13, 18) namespace core;//TODO: move all namespaces to t2
    (31, 4) * TODO: $page->set_focusFieldId
    Debug.php
    (25, 5) //TODO:update these values before deployment
    (111, 5) * TODO: Trenner nach der ersten Zeile
    (112, 5) * TODO: Funktion mit anzeigen
    Html.php
    (20, 4) * TODO:Move service\Html to core\Html
    Templates.php
    (32, 5) //TODO: file_exists($filename, $fatal=true)
    User.php
    (30, 5) * TODO: revert logic ($halt_on_error=false)
    index.php
    (10, 10) * @see (TODO: Module Template Generator)
