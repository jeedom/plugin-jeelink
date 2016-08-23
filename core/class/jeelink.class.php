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
			$eqLogic = self::byLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['key'], 'jeelink');
			if (!is_object($eqLogic)) {
				$eqLogic = new jeelink();
				utils::a2o($eqLogic, $eqLogic_info);
				$eqLogic->setId('');
				$eqLogic->setObject_id('');
			}
			$eqLogic->setConfiguration('remote_id', $eqLogic_info['id']);
			$eqLogic->setLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['key']);
			$eqLogic->setEqType_name('jeelink');
			$eqLogic->save();
			foreach ($eqLogic_info['cmds'] as $cmd_info) {
				$cmd = $eqLogic->getCmd(null, 'remote::' . $cmd_info['id'] . '::' . $_params['key']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					utils::a2o($cmd, $cmd_info);
					$cmd->setId('');
				}
				$cmd->setEqType('jeelink');
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setLogicalId('remote::' . $cmd_info['id'] . '::' . $_params['key']);
				$cmd->setConfiguration('remote_id', $cmd_info['id']);
				$cmd->setConfiguration('apikey', $_params['apikey']);
				$cmd->setConfiguration('address', $_params['address']);
				$cmd->save();
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	/*     * **********************Getteur Setteur*************************** */
}

class jeelinkCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = array()) {
		$url = $this->getConfiguration('address') . '/core/api/jeeApi.php?type=cmd&apikey=' . $this->getConfiguration('apikey');
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
				$listener = listener::byClassAndFunction(__CLASS__, 'sendEvent', array('master_id' => intval($this->getId()), 'eqLogic_id' => intval($eqLogic->getId())));
				if (!is_object($listener)) {
					$listener = new listener();
				}
				$listener->setClass(__CLASS__);
				$listener->setFunction('sendEvent');
				$listener->setOption(array('master_id' => intval($this->getId()), 'eqLogic_id' => intval($eqLogic->getId())));
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
			'address' => network::getNetworkAccess('external'),
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
