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

try {
	require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
	include_file('core', 'authentification', 'php');

	if (!isConnect('admin')) {
		throw new Exception(__('401 - Accès non autorisé', __FILE__));
	}

	if (init('action') == 'save_jeelinkMaster') {
		$jeelinkMasterSave = jeedom::fromHumanReadable(json_decode(init('jeelink_master'), true));
		$jeelink_master = jeelink_master::byId($jeelinkMasterSave['id']);
		if (!is_object($jeelink_master)) {
			$jeelink_master = new jeelink_master();
		}
		utils::a2o($jeelink_master, $jeelinkMasterSave);
		$jeelink_master->save();
		ajax::success(utils::o2a($jeelink_master));
	}

	if (init('action') == 'get_jeelinkMaster') {
		$jeelink_master = jeelink_master::byId(init('id'));
		if (!is_object($jeelink_master)) {
			throw new Exception(__('Jeelink inconnu : ', __FILE__) . init('id'), 9999);
		}
		ajax::success(jeedom::toHumanReadable(utils::o2a($jeelink_master)));
	}

	if (init('action') == 'remove_jeelinkMaster') {
		$jeelink_master = jeelink_master::byId(init('id'));
		if (!is_object($jeelink_master)) {
			throw new Exception(__('Jeelink inconnu : ', __FILE__) . init('id'), 9999);
		}
		$jeelink_master->remove();
		ajax::success();
	}

	throw new Exception(__('Aucune méthode correspondante à : ', __FILE__) . init('action'));
	/*     * *********Catch exeption*************** */
} catch (Exception $e) {
	ajax::error(displayExeption($e), $e->getCode());
}
?>
