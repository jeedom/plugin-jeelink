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

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class jeelink extends eqLogic {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	public static function event() {
		$cmds = cmd::byLogicalId('remote::' . init('remote_cmd_id') . '::' . init('remote_key'), 'info');
		if (count($cmds) == 0) {
			return;
		}
		$cmd = $cmds[0];
		if (!is_object($cmd)) {
			return;
		}
		$cmd->event(init('remote_cmd_value'));
	}

	public static function createEqLogicFromDef($_params) {
		foreach ($_params['eqLogics'] as $eqLogic_info) {
			$map_id = array();
			$eqLogic = self::byLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['key'], 'jeelink');
			if (!is_object($eqLogic)) {
				$eqLogic = new jeelink();
				utils::a2o($eqLogic, $eqLogic_info);
				$eqLogic->setId('');
				$eqLogic->setObject_id('');
				if (isset($eqLogic_info['object_name']) && $eqLogic_info['object_name'] != '') {
					$object = object::byName($eqLogic_info['object_name']);
					if (is_object($object)) {
						$eqLogic->setObject_id($object->getId());
					}
				}
			}
			$eqLogic->setConfiguration('remote_id', $eqLogic_info['id']);
			$eqLogic->setConfiguration('remote_key', $_params['key']);
			$eqLogic->setConfiguration('remote_address', $_params['address']);
			$eqLogic->setConfiguration('remote_apikey', $_params['apikey']);
			$eqLogic->setLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['key']);
			$eqLogic->setEqType_name('jeelink');
			try {
				$eqLogic->save();
			} catch (Exception $e) {
				$eqLogic->setName($eqLogic->getName() . ' remote ' . rand(0, 9999));
				$eqLogic->save();
			}

			foreach ($eqLogic_info['cmds'] as $cmd_info) {
				$cmd = $eqLogic->getCmd(null, 'remote::' . $cmd_info['id'] . '::' . $_params['key']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					utils::a2o($cmd, $cmd_info);
					$cmd->setId('');
					$cmd->setValue('');
				}
				$cmd->setEqType('jeelink');
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setLogicalId('remote::' . $cmd_info['id'] . '::' . $_params['key']);
				$cmd->setConfiguration('remote_id', $cmd_info['id']);
				$cmd->save();
				$map_id[$cmd_info['id']] = $cmd->getId();
			}

			foreach ($eqLogic_info['cmds'] as $cmd_info) {
				log::add('jeelink', 'debug', print_r($cmd_info, true));
				if (!isset($cmd_info['value']) || !isset($map_id[$cmd_info['value']])) {
					continue;
				}
				if (!isset($map_id[$cmd_info['id']])) {
					continue;
				}
				log::add('jeelink', 'debug', 'je passe');
				$cmd = cmd::byId($map_id[$cmd_info['id']]);
				if (!is_object($cmd)) {
					continue;
				}
				log::add('jeelink', 'debug', 'je passe 2');
				log::add('jeelink', 'debug', print_r($cmd, true));
				if ($cmd->getValue() != '') {
					continue;
				}
				log::add('jeelink', 'debug', 'je passe 3');
				$cmd->setValue($map_id[$cmd_info['value']]);
				$cmd->save();
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function preSave() {
		if ($this->getConfiguration('remote_id') == '') {
			throw new Exception(__('Le remote ID ne peut etre vide', __FILE__));
		}
		if ($this->getConfiguration('remote_address') == '') {
			throw new Exception(__('La remote addresse ne peut etre vide', __FILE__));
		}
		if ($this->getConfiguration('remote_apikey') == '') {
			throw new Exception(__('La remote apikey ne peut etre vide', __FILE__));
		}
		if ($this->getConfiguration('remote_key') == '') {
			throw new Exception(__('La remote key ne peut etre vide', __FILE__));
		}
		$this->setLogicalId('remote::' . $this->getConfiguration('remote_id') . '::' . $this->getConfiguration('remote_key'));
	}

	/*     * **********************Getteur Setteur*************************** */
}

class jeelinkCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		if ($this->getConfiguration('remote_id') == '') {
			throw new Exception(__('Le remote ID ne peut etre vide', __FILE__));
		}
		$eqLogic = $this->getEqLogic();
		$this->setLogicalId('remote::' . $this->getConfiguration('remote_id') . '::' . $eqLogic->getConfiguration('remote_key'));
	}

	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();
		$url = $eqLogic->getConfiguration('remote_address') . '/core/api/jeeApi.php?type=cmd&apikey=' . $eqLogic->getConfiguration('remote_apikey');
		$url .= '&id=' . $this->getConfiguration('remote_id');
		if (count($_options) > 0) {
			foreach ($_options as $key => $value) {
				$url .= '&' . $key . '=' . urlencode($value);
			}
		}
		$request_http = new com_http($url);
		$request_http->exec();
	}

	/*     * **********************Getteur Setteur*************************** */
}

class jeelink_master {
	/*     * *************************Attributs****************************** */
	private $id;
	private $name;
	private $address;
	private $apikey;
	private $configuration;

	/*     * ***********************Methode static*************************** */

	public static function getJeelinkSlaveKey() {
		$key = config::byKey('slave_key', 'jeelink');
		if ($key == '') {
			$key = config::genKey(10);
			config::save('slave_key', $key, 'jeelink');
		}
		return $key;
	}

	public static function byId($_id) {
		$values = array(
			'id' => $_id,
		);
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM jeelink_master
		WHERE id=:id';
		return DB::Prepare($sql, $values, DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function all() {
		$sql = 'SELECT ' . DB::buildField(__CLASS__) . '
		FROM jeelink_master';
		return DB::Prepare($sql, array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS, __CLASS__);
	}

	public static function sendEvent($_options) {
		$jeelink_master = self::byId($_options['master_id']);
		if (!is_object($jeelink_master)) {
			return;
		}
		$url = $jeelink_master->getAddress() . '/core/api/jeeApi.php?apikey=' . $jeelink_master->getApikey();
		$url .= '&type=jeelink';
		$url .= '&remote_cmd_id=' . $_options['event_id'];
		$url .= '&remote_cmd_value=' . urlencode($_options['value']);
		$url .= '&remote_key=' . urlencode(self::getJeelinkSlaveKey());
		$request_http = new com_http($url);
		$request_http->exec();
	}

	/*     * *********************Methode d'instance************************* */

	public function removeListener() {
		$listeners = listener::byClass(__CLASS__);
		foreach ($listeners as $listener) {
			if ($listener->getFunction() != 'sendEvent') {
				continue;
			}
			$options = $listener->getOption();
			if (!isset($options['master_id']) || $options['master_id'] != $this->getId()) {
				continue;
			}
			$listener->remove();
		}
	}

	public function save() {
		return DB::save($this);
	}

	public function postSave() {
		$this->removeListener();
		if (is_array($this->getConfiguration('eqLogics'))) {
			foreach ($this->getConfiguration('eqLogics') as $eqLogic_info) {
				$eqLogic = eqLogic::byId(str_replace('eqLogic', '', str_replace('#', '', $eqLogic_info['eqLogic'])));
				if (!is_object($eqLogic)) {
					continue;
				}
				$listener = new listener();
				$listener->setClass(__CLASS__);
				$listener->setFunction('sendEvent');
				$listener->setOption(array('background' => 0, 'master_id' => intval($this->getId()), 'eqLogic_id' => intval($eqLogic->getId())));
				$listener->emptyEvent();
				foreach ($eqLogic->getCmd('info') as $cmd) {
					$listener->addEvent('#' . $cmd->getId() . '#');
				}
				$listener->save();
			}
		}
		$this->sendEqlogicToMaster();
	}

	public function preRemove() {
		$this->removeListener();
	}

	public function remove() {
		return DB::remove($this);
	}

	public function sendEqlogicToMaster() {
		$toSend = array(
			'eqLogics' => array(),
			'address' => network::getNetworkAccess($this->getConfiguration('network::access')),
			'key' => self::getJeelinkSlaveKey(),
			'apikey' => config::byKey('api'),
		);
		if (is_array($this->getConfiguration('eqLogics'))) {
			foreach ($this->getConfiguration('eqLogics') as $eqLogic_info) {
				$eqLogic = eqLogic::byId(str_replace('eqLogic', '', str_replace('#', '', $eqLogic_info['eqLogic'])));
				if (!is_object($eqLogic)) {
					continue;
				}
				$toSend['eqLogics'][$eqLogic->getId()] = utils::o2a($eqLogic);
				$toSend['eqLogics'][$eqLogic->getId()]['object_name'] = '';
				$object = $eqLogic->getObject();
				if (is_object($object)) {
					$toSend['eqLogics'][$eqLogic->getId()]['object_name'] = $object->getName();
				}
				unset($toSend['eqLogics'][$eqLogic->getId()]['html']);
				$toSend['eqLogics'][$eqLogic->getId()]['cmds'] = array();
				foreach ($eqLogic->getCmd() as $cmd) {
					$toSend['eqLogics'][$eqLogic->getId()]['cmds'][$cmd->getId()] = utils::o2a($cmd);
				}
			}
		}
		$params = array(
			'apikey' => $this->getApikey(),
			'plugin' => 'jeelink',
		);
		$jsonrpc = new jsonrpcClient($this->getAddress() . '/core/api/jeeApi.php', '', $params);
		$jsonrpc->setNoSslCheck(true);
		if (!$jsonrpc->sendRequest('createEqLogic', $toSend)) {
			throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
		}
	}

	/*     * **********************Getteur Setteur*************************** */

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getAddress() {
		return $this->address;
	}

	public function setAddress($address) {
		$this->address = $address;
	}

	public function getApikey() {
		return $this->apikey;
	}

	public function setApikey($apikey) {
		$this->apikey = $apikey;
	}

	public function getConfiguration($_key = '', $_default = '') {
		return utils::getJsonAttr($this->configuration, $_key, $_default);
	}

	public function setConfiguration($_key, $_value) {
		$this->configuration = utils::setJsonAttr($this->configuration, $_key, $_value);
	}

}

?>
