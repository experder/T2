@echo off
rem /*GPL
rem This file is part of the T2 toolbox;
rem Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
rem T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
rem certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
rem GPL*/
rem ��� (set encoding to IBM850 / OEM 852)/**TPLDOCSTART
rem This template is used to create commandline update file "update.cmd"
rem by \t2\dev\Install_wizard::init_updater()
rem TPLDOCEND*/


echo ========= git pull =========
git pull
