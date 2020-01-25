
Installation
============

Repository
----------

#### Create new project:  
`git init`  
`git add .`  
`git commit -m "Initial commit"`

#### Create submodule in new project:  
`git submodule add https://github.com/experder/T2.git tethys`  
`git commit -m "Add T2 as submodule tethys"`

#### When cloning an existing project with submodule(s):
`git clone --recurse-submodules REPO_URL .`

### dev_tools

#### Register dev_tools as a submodule:
`git submodule add https://github.com/experder/dev_tools.git modules/dev_tools`  
`git commit -m "Add dev_tools as a submodule"`

#### Clone dev_tools with T2:
`git clone --recurse-submodules https://github.com/experder/dev_tools.git .`

Apache
------

    #Configure your own extension in T2 (here: .t2)
    LoadModule rewrite_module modules/mod_rewrite.so
    RewriteEngine on
    RewriteRule ^/myproject/(.*)\.t2$ "C:/MyWorkspace/MyProject/$1.php"

### Windows

    Alias /myproject "C:/MyWorkspace/MyProject"
    <Directory "C:/MyWorkspace/MyProject">
        Require all granted
        AllowOverride All
    </Directory>

Permissions
-----------

### Linux

    cp dev/templates/update.sh ../update.sh
* Check line endings of `update.sh`! (Must be Linux: `\n`)


    # Set rights for project's root directory:
    sudo chmod 777 '/var/www/myproject' -R
