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

	/*
		     * Fonction exécutée automatiquement toutes les minutes par Jeedom
		      public static function cron() {

		      }
	*/

	/*
		     * Fonction exécutée automatiquement toutes les heures par Jeedom
		      public static function cronHourly() {

		      }
	*/

	/*
		     * Fonction exécutée automatiquement tous les jours par Jeedom
		      public static function cronDayly() {

		      }
	*/

	/*     * *********************Méthodes d'instance************************* */

	public function preInsert() {

	}

	public function postInsert() {

	}

	public function preSave() {

	}

	public function postSave() {

	}

	public function preUpdate() {

	}

	public function postUpdate() {

	}

	public function preRemove() {

	}

	public function postRemove() {

	}

	/*
		     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
		      public function toHtml($_version = 'dashboard') {

		      }
	*/

	/*     * **********************Getteur Setteur*************************** */
}

class jeelinkCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	/*
		     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
		      public function dontRemoveCmd() {
		      return true;
		      }
	*/

	public function execute($_options = array()) {

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

	/*     * *********************Methode d'instance************************* */

	public function save() {
		return DB::save($this);
	}

	public function remove() {
		return DB::remove($this);
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
