<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function jeelink_install() {
	$sql = file_get_contents(dirname(__FILE__) . '/install.sql');
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
	foreach (jeelink::byType('jeelink') as $jeelink) {
		$jeelink->save();
	}
}

function jeelink_update() {
	foreach (jeelink::byType('jeelink') as $jeelink) {
		$jeelink->save();
	}
}

function jeelink_remove() {
	foreach (jeelink_master::all() as $jeelink_master) {
		$jeelink_master->removeListener();
	}
	DB::Prepare('DROP TABLE IF EXISTS `jeelink_master`', array(), DB::FETCH_TYPE_ROW);
}

?>
