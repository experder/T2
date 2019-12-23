# T2
3rd shot of a great PHP toolbox.

Inspired by: [Tethys-Beta](https://github.com/gitfabian/tethysbeta) (2014) and [Tethys](https://github.com/GitFabian/Tethys) (2018).

## Installation

### Repository

Create new project:  
`git init`  
`git add .`  
`git commit -m "Initial commit"`

Create submodule in new project:  
`git submodule add https://github.com/experder/T2.git tethys`  
`git commit -m "Add T2 as submodule tethys"`

Clone existing project:  
`git clone --recurse-submodules REPO_URL .`

### Apache configuration

#### Windows

    Alias /myproject "C:/MyWorkspace/MyProject"
    <Directory "C:/MyWorkspace/MyProject">
        Require all granted
        AllowOverride All
    </Directory>

## More

[[Useful links]](https://github.com/experder/T2/blob/master/ref.md)
