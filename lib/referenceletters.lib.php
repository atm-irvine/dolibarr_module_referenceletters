<?php
/* Copyright (C) 2022 SuperAdmin
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * \file    referenceletters/lib/referenceletters.lib.php
 * \ingroup referenceletters
 * \brief   Library files with common functions for Referenceletters
 */

/**
 * Prepare admin pages header
 *
 * @return array
 */
function referencelettersAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("referenceletters@referenceletters");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/referenceletters/admin/setup.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;

	/*
	$head[$h][0] = dol_buildpath("/referenceletters/admin/myobject_extrafields.php", 1);
	$head[$h][1] = $langs->trans("ExtraFields");
	$head[$h][2] = 'myobject_extrafields';
	$h++;
	*/

	$head[$h][0] = dol_buildpath("/referenceletters/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@referenceletters:/referenceletters/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@referenceletters:/referenceletters/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, null, $head, $h, 'referenceletters@referenceletters');

	complete_head_from_modules($conf, $langs, null, $head, $h, 'referenceletters@referenceletters', 'remove');

	return $head;
}

function referenceletterPrepareHead($object) {
	global $langs, $conf;

	$langs->load("referenceletters@referenceletters");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/referenceletters/referenceletters_card.php", 1) . '?id=' . $object->id;
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h ++;

	$head[$h][0] = dol_buildpath("/referenceletters/header.php", 1) . '?id=' . $object->id;
	$head[$h][1] = $langs->trans("RefLtrHeaderTab");
	$head[$h][2] = 'head';
	$h ++;

	$head[$h][0] = dol_buildpath("/referenceletters/footer.php", 1) . '?id=' . $object->id;
	$head[$h][1] = $langs->trans("RefLtrFooterTab");
	$head[$h][2] = 'foot';
	$h ++;

	$head[$h][0] = dol_buildpath("/referenceletters/background.php", 1) . '?id=' . $object->id;
	$head[$h][1] = $langs->trans("RefLtrBackground");
	$head[$h][2] = 'background';
	$h ++;

	$head[$h][0] = dol_buildpath("/referenceletters/info.php", 1) . '?id=' . $object->id;
	$head[$h][1] = $langs->trans("Info");
	$head[$h][2] = 'info';
	$h ++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	// $this->tabs = array(
	// 'entity:+tabname:Title:@referenceletters:/referenceletters/mypage.php?id=__ID__'
	// ); // to add new tab
	// $this->tabs = array(
	// 'entity:-tabname:Title:@referenceletters:/referenceletters/mypage.php?id=__ID__'
	// ); // to remove a tab
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'referenceletters');

	return $head;
}
