## Release notes

[[Coming features](dev/notes.md#current-todos)]

### v0.3
**Release date:** 2020-02-28  
**DB version:** 4  
**Release notes:**  
A lot of open tasks.
FIXED:
missing class header, config wizard
REORG:
json data
Includes: load all available
moved: includes to service, class Js, core.js, ajax demo to demo module
ENHANCED:
update script (spinner for ajax buttons), pdfdemo, table (set_headers), Ajaxing, Accept no navigation (warning), print.css, load_values_api
Classes: Js, Files, Pdf, Error, Warning
NEW:
waitSpinner, core_prefix, navi_default, header and footer, cleanup_relative_path, Create_blank_navigation, module specific api config override, config admin gui, config values admin, configuration layer (redirect, server-specific, project-specific, database-specific), dev zone (new module, notes (todos))
Includes: tcpdf632, highlight_js
service class: PDF
classes: Table, Navigation, DBTable, Config_core
js services: scroll_to_bottom, ajax_post_to_id
demo: ajax examples
dev: insert linebreaks to html source, default api dir, ini_set('display_errors')  
**Extinctions:** Error_ (replaced by Error), root index (might become front-controller)

### v0.2
**Release date:** 2020-01-29  
**DB version:** 4  
**Release notes:**  
Work in progress.
Classes: Error_, Stylesheet, Login, Ajax, new Formfields (checkbox, header, radio, textarea), Includes (jQuery, Parsedown), Warning, Autoloader.
Services: DB initialization, Filewriter, get_api_class, Arrays, Module Generator.
Developers: $DEVMODE, Module template, TODOs.
Basic CSS, core_prefix prepared, errorhandling, prompt and init config_params, API: default_values, module configuration, config defaults, basic js, init platform.
Enhanced: Updater (Windows/Linux), Installer, dev-stats (queries, core queries, runtime, memory, includes, core includes), namespaces, Config: cache stored values, Page, Installation wizard, documentation (regex, submodules), Form and Formfields, config.
Fixed: user id not available on login form.  
**Extinctions:** Old Error class, dev-tools (extra repository), class service\Html (included in core\Html)

### v0.1
**Release date:** 2019-12-29  
**DB version:** -  
**Release notes:**  
New readme, some links,
Classes: Page, Error, Message, Forms, Database, Html.
Service classes: Config, Files, Html, Request, Strings, Templates.
Compiler messages, Installer, Updater, dev stats.
PHP language level: 5.3.  
**Extinctions:** None

