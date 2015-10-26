<?php

namespace PSYCHOPASS_API\psychopass;

use PSYCHOPASS_API\PSYCHOPASS_API;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\Player;

class PsychoPassProvider {
	/**
	 *
	 * @var PsychoPassProvider
	 */
	private static $instance = null;
	/**
	 *
	 * @var PsychoPassManager
	 */
	private $plugin;
	/**
	 *
	 * @var PsychoPassLoader
	 */
	private $loader;
	/**
	 *
	 * @var Server
	 */
	private $server;
	/**
	 *
	 * @var PsychoPassProvider DB
	 */
	private $db;
	public function __construct(PSYCHOPASS_API $plugin) {
		if (self::$instance == null)
			self::$instance = $this;
		
		$this->plugin = $plugin;
		$this->loader = $plugin->getPsychoPassLoader ();
		$this->server = Server::getInstance ();
		
		$this->db = (new Config ( $this->plugin->getDataFolder () . "pluginDB.yml", Config::YAML, [ ] ))->getAll ();
	}
	public function save($async = false) {
		(new Config ( $this->plugin->getDataFolder () . "pluginDB.yml", Config::YAML, $this->db ))->save ( $async );
	}
	public function loadPsychoPass($userName) {
		return $this->loader->loadPsychoPass ( $userName );
	}
	public function unloadPsychoPass($userName = null) {
		return $this->loader->unloadPsychoPass ( $userName );
	}
	public function getPsychoPass(Player $player) {
		return $this->loader->getPsychoPass ( $player );
	}
	public function getPsychoPassToName($name) {
		return $this->loader->getPsychoPassToName ( $name );
	}
	public static function getInstance() {
		return static::$instance;
	}
}

?>