Developers
==========

Release
-------
Before merging the `dev` branch onto the `master`:
* Check for TODOs, deprecated code, compiler warnings, PHPdoc
* Check for out commented and unused code
* Check code format
* Check language (translate into english)
* Check license
* Write [release note](../release_notes.md)
* Create tag

Current TODOs
-------------

### Medium
* $page->set_focusFieldId
* Cleanup Error class: Provide solution (Level: User, Admin, Developer; Log additional info), unify quit and don't quit (class Error_fatal)
* file_exists($filename, $fatal=true)
* stop mysql service, catch exception ("SQLSTATE\[HY000] \[2002] No such file or directory")
* backtrace: echo function

### Low
* rename column 'user' in core_sessions
* remove camel case methods from whole project
* Page.php: organize classes as collection
