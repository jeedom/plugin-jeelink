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

	public static function deadCmd() {
		return array();
	}

	public static function event() {
		$cmds = cmd::byLogicalId('remote::' . init('remote_cmd_id') . '::' . init('remote_apikey'), 'info');
		if (count($cmds) == 0) {
			return;
		}
		$cmd = $cmds[0];
		if (!is_object($cmd)) {
			return;
		}
		$cmd->event(urldecode(init('remote_cmd_value')));
	}

	public static function createEqLogicFromDef($_params) {
		foreach ($_params['eqLogics'] as $eqLogic_info) {
			log::add('jeelink', 'debug', 'Check eqLogic : ' . $eqLogic_info['id'] . '::' . $_params['remote_apikey'] . ' with name : ' . $eqLogic_info['name']);
			$map_id = array();
			$eqLogic = self::byLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['remote_apikey'], 'jeelink');
			if (!is_object($eqLogic)) {
				log::add('jeelink', 'debug', 'EqLogic not exist create it');
				$eqLogic = new jeelink();
				utils::a2o($eqLogic, $eqLogic_info);
				$eqLogic->setId('');
				$eqLogic->setObject_id('');
				if (isset($eqLogic_info['object_name']) && $eqLogic_info['object_name'] != '') {
					$object = jeeObject::byName($eqLogic_info['object_name']);
					if (is_object($object)) {
						log::add('jeelink', 'debug', 'Find match object affect it to eqLogic');
						$eqLogic->setObject_id($object->getId());
					}
				}
			}
			$eqLogic->setConfiguration('remote_id', $eqLogic_info['id']);
			if(isset($_params['address'])){
				$eqLogic->setConfiguration('remote_address', $_params['address']);
			}else{
				$eqLogic->setConfiguration('remote_address', $_params['address_primary']);
			}
			$eqLogic->setConfiguration('remote_address_primary', $_params['address_primary']);
			$eqLogic->setConfiguration('remote_address_secondary', $_params['address_secondary']);
			$eqLogic->setConfiguration('remote_apikey', $_params['remote_apikey']);
			$eqLogic->setEqType_name('jeelink');
			try {
				$eqLogic->save();
			} catch (Exception $e) {
				$eqLogic->setName($eqLogic->getName() . ' remote ' . rand(0, 9999));
				$eqLogic->save();
			}
			log::add('jeelink', 'debug', 'EqLogic save, create cmd');
			foreach ($eqLogic_info['cmds'] as &$cmd_info) {
				if (isset($cmd_info['configuration']) && isset($cmd_info['configuration']['calculValueOffset'])) {
					unset($cmd_info['configuration']['calculValueOffset']);
				}
				$cmd = $eqLogic->getCmd(null, 'remote::' . $cmd_info['id'] . '::' . $_params['remote_apikey']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					utils::a2o($cmd, $cmd_info);
					$cmd->setId('');
					$cmd->setValue('');
				}
				$cmd->setEqType('jeelink');
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setConfiguration('remote_id', $cmd_info['id']);
				if ($cmd_info['logicalId'] == 'refresh') {
					$cmd->setConfiguration('isRefreshCmd', 1);
				} else {
					$cmd->setConfiguration('isRefreshCmd', 0);
				}
				try {
					$cmd->save();
				} catch (Exception $e) {
					$cmd->setName($cmd->getName() . ' remote ' . rand(0, 9999));
					$cmd->save();
				}

				$map_id[$cmd_info['id']] = $cmd->getId();
			}

			foreach ($eqLogic_info['cmds'] as $cmd_info) {
				if (!isset($cmd_info['value']) || !isset($map_id[$cmd_info['value']])) {
					continue;
				}
				if (!isset($map_id[$cmd_info['id']])) {
					continue;
				}
				$cmd = cmd::byId($map_id[$cmd_info['id']]);
				if (!is_object($cmd)) {
					continue;
				}
				$cmd->setValue($map_id[$cmd_info['value']]);
				$cmd->save();
			}
		}
		$eqLogic = self::byLogicalId('remote::core::' . $_params['remote_apikey'], 'jeelink');
		if (!is_object($eqLogic)) {
			$eqLogic = new jeelink();
			$eqLogic->setName(__('Controle', __FILE__) . ' ' . $_params['name']);
			$eqLogic->setIsEnable(1);
		}
		$eqLogic->setConfiguration('remote_id', 'core');
		if(isset($_params['address'])){
			$eqLogic->setConfiguration('remote_address', $_params['address']);
		}else{
			$eqLogic->setConfiguration('remote_address', $_params['address_primary']);
		}
		$eqLogic->setConfiguration('remote_address_primary', $_params['address_primary']);
		$eqLogic->setConfiguration('remote_address_secondary', $_params['address_secondary']);
		$eqLogic->setConfiguration('remote_apikey', $_params['remote_apikey']);
		$eqLogic->setConfiguration('deamons', $_params['deamons']);
		$eqLogic->setEqType_name('jeelink');
		try {
			$eqLogic->save();
		} catch (Exception $e) {
			$eqLogic->setName($eqLogic->getName() . ' remote ' . rand(0, 9999));
			$eqLogic->save();
		}
		if(isset($_params['deamons']) && count($_params['deamons']) > 0){
			$i = 0;
			foreach ($_params['deamons'] as $info) {
				$cmd = $eqLogic->getCmd(null, 'deamonState::' . $info['id']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					$cmd->setName(__('Démon', __FILE__) . ' ' . $info['name']);
					$cmd->setTemplate('mobile', 'line');
					$cmd->setTemplate('dashboard', 'line');
					$cmd->setOrder(100 + $i);
				}
				$cmd->setConfiguration('remote_plugin_id', $info['id']);
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setLogicalId('deamonState::' . $info['id']);
				$cmd->setType('info');
				$cmd->setSubType('binary');
				$cmd->save();

				$cmd = $eqLogic->getCmd(null, 'deamonStart::' . $info['id']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					$cmd->setName(__('Démarrer', __FILE__) . ' ' . $info['name']);
					$cmd->setOrder(101 + $i);
				}
				$cmd->setConfiguration('remote_plugin_id', $info['id']);
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setLogicalId('deamonStart::' . $info['id']);
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->save();

				$cmd = $eqLogic->getCmd(null, 'deamonStop::' . $info['id']);
				if (!is_object($cmd)) {
					$cmd = new jeelinkCmd();
					$cmd->setName(__('Arrêter', __FILE__) . ' ' . $info['name']);
					$cmd->setOrder(102 + $i);
				}
				$cmd->setConfiguration('remote_plugin_id', $info['id']);
				$cmd->setEqLogic_id($eqLogic->getId());
				$cmd->setLogicalId('deamonStop::' . $info['id']);
				$cmd->setType('action');
				$cmd->setSubType('other');
				$cmd->save();
				$i += 10;
			}
		}
		
	}

	public static function receiveBatteryLevel($_params) {
		foreach ($_params['eqLogics'] as $eqLogic_info) {
			$eqLogic = self::byLogicalId('remote::' . $eqLogic_info['id'] . '::' . $_params['remote_apikey'], 'jeelink');
			if (!is_object($eqLogic)) {
				continue;
			}
			$eqLogic->batteryStatus($eqLogic_info['battery'], $eqLogic_info['datetime']);
		}
	}

	public static function cron10($_eqLogic_id = null) {
		if ($_eqLogic_id == null) {
			$eqLogics = eqLogic::byType('jeelink');
		} else {
			$eqLogics = array(eqLogic::byId($_eqLogic_id));
		}
		foreach ($eqLogics as $eqLogic) {
			if ($eqLogic->getConfiguration('remote_id') != 'core') {
				continue;
			}
			$eqLogic->updateSysInfo();
		}
	}

	public static function cronDaily() {
		$jeelink_masters = jeelink_master::all();
		if (is_array($jeelink_masters) && count($jeelink_masters) > 0) {
			foreach ($jeelink_masters as $jeelink_master) {
				try {
					$jeelink_master->sendBatteryToMaster();
				} catch (Exception $e) {
					log::add('jeelink', 'error', $jeelink_master->getName() . ' ' . __('Erreur sur l\'envoi du niveau de batterie : ', __FILE__) . $e->getMessage());
				}
			}
		}
	}

	/*     * *********************Méthodes d'instance************************* */

	public function getJsonRpc() {
		$params = array(
			'apikey' => $this->getConfiguration('remote_apikey'),
			'plugin' => 'jeelink'
		);
		$jsonrpc = new jsonrpcClient($this->getConfiguration('remote_address') . '/core/api/jeeApi.php', '', $params);
		$jsonrpc->setNoSslCheck(true);
		return $jsonrpc;
	}

	public function updateSysInfo() {
		$jsonrpc = $this->getJsonRpc();

		$cmd = $this->getCmd(null, 'ping');
		if (is_object($cmd)) {
			try {
				if ($jsonrpc->sendRequest('ping')) {
					$cmd->event(1);
				} else {
					$cmd->event(0);
				}
			} catch (Exception $e) {
				$cmd->event(0);
			}
		}

		$cmd = $this->getCmd(null, 'state');
		if (is_object($cmd)) {
			try {
				if ($jsonrpc->sendRequest('jeedom::isOk')) {
					if ($jsonrpc->getResult()) {
						$cmd->event(1);
					} else {
						$cmd->event(0);
					}
				} else {
					$cmd->event(0);
				}
			} catch (Exception $e) {
				$cmd->event(0);
			}
		}

		$cmd = $this->getCmd(null, 'updateNb');
		if (is_object($cmd)) {
			try {
				if ($jsonrpc->sendRequest('update::nbNeedUpdate')) {
					$cmd->event($jsonrpc->getResult());
				}
			} catch (Exception $e) {
				$cmd->event(0);
			}
		}

		$cmd = $this->getCmd(null, 'messageNb');
		if (is_object($cmd)) {
			try {
				if ($jsonrpc->sendRequest('message::all')) {
					$cmd->event(count($jsonrpc->getResult()));
				}
			} catch (Exception $e) {
				$cmd->event(0);
			}
		}

		$cmd = $this->getCmd(null, 'version');
		if (is_object($cmd)) {
			if ($jsonrpc->sendRequest('version')) {
				$cmd->event($jsonrpc->getResult());
			}
		}

		foreach ($this->getConfiguration('deamons') as $info) {
			$cmd = $this->getCmd(null, 'deamonState::' . $info['id']);
			if (is_object($cmd)) {
				try {
					if ($jsonrpc->sendRequest('plugin::deamonInfo', array('plugin_id' => $info['id']))) {
						$result = $jsonrpc->getResult();
						if ($result['state'] == 'ok') {
							$cmd->event(1);
						} else {
							$cmd->event(0);
						}
					} else {
						$cmd->event(0);
					}
				} catch (Exception $e) {
					$cmd->event(0);
				}
			}
		}
	}

	public function preSave() {
		if ($this->getConfiguration('remote_id') == '') {
			throw new Exception(__('Le remote ID ne peut être vide', __FILE__));
		}
		if ($this->getConfiguration('remote_address') == '') {
			throw new Exception(__('La remote adresse ne peut être vide', __FILE__));
		}
		if ($this->getConfiguration('remote_apikey') == '') {
			throw new Exception(__('La remote apikey ne peut être vide', __FILE__));
		}
		$this->setLogicalId('remote::' . $this->getConfiguration('remote_id') . '::' . $this->getConfiguration('remote_apikey'));
	}

	public function postSave() {
		if ($this->getConfiguration('remote_id') != 'core') {
			return;
		}

		$cmd = $this->getCmd(null, 'refresh');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Rafraichir', __FILE__));
			$cmd->setOrder(0);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('refresh');
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->save();

		$cmd = $this->getCmd(null, 'ping');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Joignable', __FILE__));
			$cmd->setTemplate('mobile', 'line');
			$cmd->setTemplate('dashboard', 'line');
			$cmd->setOrder(1);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('ping');
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->save();

		$cmd = $this->getCmd(null, 'state');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Statut', __FILE__));
			$cmd->setTemplate('mobile', 'line');
			$cmd->setTemplate('dashboard', 'line');
			$cmd->setOrder(2);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('state');
		$cmd->setType('info');
		$cmd->setSubType('binary');
		$cmd->save();

		$cmd = $this->getCmd(null, 'updateNb');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Nombre update', __FILE__));
			$cmd->setTemplate('mobile', 'line');
			$cmd->setTemplate('dashboard', 'line');
			$cmd->setOrder(2);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('updateNb');
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->save();

		$cmd = $this->getCmd(null, 'messageNb');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Nombre de message', __FILE__));
			$cmd->setTemplate('mobile', 'line');
			$cmd->setTemplate('dashboard', 'line');
			$cmd->setOrder(2);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('messageNb');
		$cmd->setType('info');
		$cmd->setSubType('numeric');
		$cmd->save();


		$cmd = $this->getCmd(null, 'version');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Version', __FILE__));
			$cmd->setTemplate('mobile', 'line');
			$cmd->setTemplate('dashboard', 'line');
			$cmd->setOrder(3);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('version');
		$cmd->setType('info');
		$cmd->setSubType('string');
		$cmd->save();

		$cmd = $this->getCmd(null, 'restart');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Redémarrer', __FILE__));
			$cmd->setOrder(4);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('restart');
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->save();

		$cmd = $this->getCmd(null, 'halt');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Arrêter', __FILE__));
			$cmd->setOrder(5);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('halt');
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->save();

		$cmd = $this->getCmd(null, 'update');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Mettre à jour', __FILE__));
			$cmd->setOrder(6);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('update');
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->save();

		$cmd = $this->getCmd(null, 'backup');
		if (!is_object($cmd)) {
			$cmd = new jeelinkCmd();
			$cmd->setName(__('Lancer un backup', __FILE__));
			$cmd->setOrder(7);
		}
		$cmd->setEqLogic_id($this->getId());
		$cmd->setLogicalId('backup');
		$cmd->setType('action');
		$cmd->setSubType('other');
		$cmd->save();
	}

	/*     * **********************Getteur Setteur*************************** */
}

class jeelinkCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function preSave() {
		$eqLogic = $this->getEqLogic();
		if ($eqLogic->getConfiguration('remote_id') != 'core' && $this->getConfiguration('remote_id') == '') {
			throw new Exception(__('Le remote ID ne peut être vide', __FILE__));
		}
		if ($eqLogic->getConfiguration('remote_id') != 'core') {
			$this->setLogicalId('remote::' . $this->getConfiguration('remote_id') . '::' . $eqLogic->getConfiguration('remote_apikey'));
		}
	}

	public function execute($_options = array()) {
		$eqLogic = $this->getEqLogic();
		if ($eqLogic->getConfiguration('remote_id') != 'core') {
		        if( $eqLogic->getConfiguration('remote_address_primary') != ''){
				$base_url = $eqLogic->getConfiguration('remote_address_primary') . '/core/api/jeeApi.php?plugin=jeelink&type=cmd&apikey=' . $eqLogic->getConfiguration('remote_apikey');
			}else{
			   	$base_url = $eqLogic->getConfiguration('remote_address') . '/core/api/jeeApi.php?plugin=jeelink&type=cmd&apikey=' . $eqLogic->getConfiguration('remote_apikey');
			}
			$url = '&id=' . $this->getConfiguration('remote_id');
			if (count($_options) > 0) {
				foreach ($_options as $key => $value) {
					if (in_array($key, array('apikey', 'id'))) {
						continue;
					}
					$url .= '&' . $key . '=' . urlencode($value);
				}
			}
			
			try{
				$request_http = new com_http($base_url.$url);
				$request_http->exec(60);
			}catch(Exception $e){
				if( $eqLogic->getConfiguration('remote_address_secondary') != ''){
				  	log::add('jeelink','debug',__('Erreur de l\'exécution de la commande, essai par le lien secondaire',__FILE__));
					$base_url = $eqLogic->getConfiguration('remote_address_secondary') . '/core/api/jeeApi.php?plugin=jeelink&type=cmd&apikey=' . $eqLogic->getConfiguration('remote_apikey');
					$request_http = new com_http($base_url.$url);
					$request_http->exec(60);
				}
			}
			
			return;
		}

		if ($this->getLogicalId() == 'refresh') {
			$eqLogic->updateSysInfo();
			return;
		}

		$jsonrpc = $eqLogic->getJsonRpc();
		if ($this->getLogicalId() == 'restart') {
			if (!$jsonrpc->sendRequest('jeedom::reboot')) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}

		if ($this->getLogicalId() == 'halt') {
			if (!$jsonrpc->sendRequest('jeedom::halt')) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}

		if ($this->getLogicalId() == 'update') {
			if (!$jsonrpc->sendRequest('update::update')) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}

		if ($this->getLogicalId() == 'backup') {
			if (!$jsonrpc->sendRequest('jeedom::backup')) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}

		if (strpos($this->getLogicalId(), 'deamonStop') !== false) {
			if (!$jsonrpc->sendRequest('plugin::deamonStop', array('plugin_id' => $this->getConfiguration('remote_plugin_id')))) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}

		if (strpos($this->getLogicalId(), 'deamonStart') !== false) {
			if (!$jsonrpc->sendRequest('plugin::deamonStart', array('plugin_id' => $this->getConfiguration('remote_plugin_id')))) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}
		$eqLogic->updateSysInfo();
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
		$jeelink_master->apiCallCmdEvent($_options['event_id'], $_options['value']);
	}

	/*     * *********************Methode d'instance************************* */

	public function apiCallCmdEvent($_cmd_id, $_value) {
		$url = $this->getAddress() . '/core/api/jeeApi.php?apikey=' . $this->getApikey();
		$url .= '&plugin=jeelink';
		$url .= '&type=event';
		$url .= '&remote_cmd_id=' . $_cmd_id;
		$url .= '&remote_cmd_value=' . urlencode($_value);
		$url .= '&remote_apikey=' . jeedom::getApiKey('jeelink');
		$request_http = new com_http($url);
		$request_http->exec(60);
	}

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
				$listenner_number = 0;
				$listener = new listener();
				$listener->setClass(__CLASS__);
				$listener->setFunction('sendEvent');
				$listener->setOption(array('background' => 0, 'master_id' => intval($this->getId()), 'eqLogic_id' => intval($eqLogic->getId()), 'listenner_number' => $listenner_number));
				$listener->emptyEvent();
				$count = 0;
				foreach ($eqLogic->getCmd('info') as $cmd) {
					$listener->addEvent('#' . $cmd->getId() . '#');
					$count++;
					if ($count > 15) {
						$listener->save();
						$listenner_number++;
						$listener = new listener();
						$listener->setClass(__CLASS__);
						$listener->setFunction('sendEvent');
						$listener->setOption(array('background' => 0, 'master_id' => intval($this->getId()), 'eqLogic_id' => intval($eqLogic->getId()), 'listenner_number' => $listenner_number));
						$listener->emptyEvent();
					}
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

	public function sendBatteryToMaster() {
		$toSend = array(
			'eqLogics' => array(),
			'remote_apikey' => jeedom::getApiKey('jeelink'),
		);
		if (is_array($this->getConfiguration('eqLogics'))) {
			foreach ($this->getConfiguration('eqLogics') as $eqLogic_info) {
				$eqLogic = eqLogic::byId(str_replace('eqLogic', '', str_replace('#', '', $eqLogic_info['eqLogic'])));
				if (!is_object($eqLogic) || $eqLogic->getStatus('battery', -2) == -2) {
					continue;
				}
				$toSend['eqLogics'][$eqLogic->getId()] = array(
					'battery' => $eqLogic->getStatus('battery', -2),
					'datetime' => $eqLogic->getStatus('batteryDatetime', date('Y-m-d H:i:s')),
					'id' => $eqLogic->getId()
				);
			}
		}
		$params = array(
			'apikey' => $this->getApikey(),
			'plugin' => 'jeelink',
		);
		$jsonrpc = new jsonrpcClient($this->getAddress() . '/core/api/jeeApi.php', '', $params);
		$jsonrpc->setNoSslCheck(true);
		if (!$jsonrpc->sendRequest('eqLogicBattery', $toSend, 300)) {
			sleep(1);
			if (!$jsonrpc->sendRequest('eqLogicBattery', $toSend, 300)) {
				throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
			}
		}
	}

	public function sendEqlogicToMaster() {
		$params = array(
			'apikey' => $this->getApikey(),
			'plugin' => 'jeelink',
		);
		$toSend = array(
			'eqLogics' => array(),
			'address_primary' => network::getNetworkAccess($this->getConfiguration('network::access_primary')),
			'address_secondary' => network::getNetworkAccess($this->getConfiguration('network::access_secondary')),
			'address' => network::getNetworkAccess($this->getConfiguration('network::access_primary',$this->getConfiguration('network::access_secondary',$this->getConfiguration('network::access')))),
			'remote_apikey' => jeedom::getApiKey('jeelink'),
			'name' => config::byKey('name', 'core', 'Jeedom'),
		);
		$toSend['deamons'] = array();
		foreach (plugin::listPlugin(true) as $plugin) {
			if ($plugin->getHasOwnDeamon() != 1) {
				continue;
			}
			$toSend['deamons'][] = array('id' => $plugin->getId(), 'name' => $plugin->getName());
		}
		$jsonrpc = new jsonrpcClient($this->getAddress() . '/core/api/jeeApi.php', '', $params);
		$jsonrpc->setNoSslCheck(true);
		if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
			throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
		}
		unset($toSend['deamons']);
		if (is_array($this->getConfiguration('eqLogics'))) {
			$i=0;
			foreach ($this->getConfiguration('eqLogics') as $eqLogic_info) {
				$eqLogic = eqLogic::byId(str_replace('eqLogic', '', str_replace('#', '', $eqLogic_info['eqLogic'])));
				if (!is_object($eqLogic)) {
					continue;
				}
				$i++;
				$toSend['eqLogics'][$eqLogic->getId()] = utils::o2a($eqLogic);
				$toSend['eqLogics'][$eqLogic->getId()]['configuration']['real_eqType'] = $eqLogic->getEqType_name();
				unset($toSend['eqLogics'][$eqLogic->getId()]['object_id']);
				$toSend['eqLogics'][$eqLogic->getId()]['object_name'] = '';
				$object = $eqLogic->getObject();
				if (is_object($object)) {
					$toSend['eqLogics'][$eqLogic->getId()]['object_name'] = $object->getName();
				}
				unset($toSend['eqLogics'][$eqLogic->getId()]['html']);
				unset($toSend['eqLogics'][$eqLogic->getId()]['cache']);
				$toSend['eqLogics'][$eqLogic->getId()]['cmds'] = array();
				foreach ($eqLogic->getCmd() as $cmd) {
					$toSend['eqLogics'][$eqLogic->getId()]['cmds'][$cmd->getId()] = utils::o2a($cmd);
					$toSend['eqLogics'][$eqLogic->getId()]['cmds'][$cmd->getId()]['configuration']['real_eqType'] = $cmd->getEqType_name();
					$toSend['eqLogics'][$eqLogic->getId()]['cmds'][$cmd->getId()]['configuration']['real_logicalId'] = $cmd->getLogicalId();
				}
				if($i>9){
					$jsonrpc = new jsonrpcClient($this->getAddress() . '/core/api/jeeApi.php', '', $params);
					$jsonrpc->setNoSslCheck(true);
					if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
						sleep(1);
						if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
							sleep(1);
							if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
								throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
							}
						}
					}
					$toSend['eqLogics'] = array();
					$i=0;
				}
			}
			$jsonrpc = new jsonrpcClient($this->getAddress() . '/core/api/jeeApi.php', '', $params);
			$jsonrpc->setNoSslCheck(true);
			if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
				sleep(1);
				if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
					sleep(1);
					if (!$jsonrpc->sendRequest('createEqLogic', $toSend, 300)) {
						throw new Exception($jsonrpc->getError(), $jsonrpc->getErrorCode());
					}
				}
			}
		}
		
		
		
		if (is_array($this->getConfiguration('eqLogics'))) {
			foreach ($this->getConfiguration('eqLogics') as $eqLogic_info) {
				$eqLogic = eqLogic::byId(str_replace('eqLogic', '', str_replace('#', '', $eqLogic_info['eqLogic'])));
				if (!is_object($eqLogic)) {
					continue;
				}
				foreach ($eqLogic->getCmd('info') as $cmd) {
					$this->apiCallCmdEvent($cmd->getId(), $cmd->execCmd());
				}
			}
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
