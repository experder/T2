#!/bin/bash

# /*GPL
# This file is part of the T2 toolbox;
# Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
# T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
# certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
# GPL*/
#
# Please set rights and line endings of this file according to:
# help/install.md#linux
#
# äöü/**TPLDOCSTART
# This template is used to create commandline update file "update.sh"
# by \t2\dev\Install_wizard::init_updater()
# TPLDOCEND*/


cd ':rel_root'

echo "========= git pull ========="
git pull
