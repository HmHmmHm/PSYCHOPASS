<?php

namespace PSYCHOPASS_API\psychopass;

use pocketmine\Player;
use pocketmine\Server;
use PSYCHOPASS_API\PSYCHOPASS_API;
use PSYCHOPASS_API\task\AutoUnloadTask;

class PsychoPassLoader {
	private static $instance = null;
	/**
	 *
	 * @var Users prefix data
	 */
	private $users = [ ];
	/**
	 *
	 * @var PsychoPassManager
	 */
	private $plugin;
	/**
	 *
	 * @var Server
	 */
	private $server;
	public function __construct(PSYCHOPASS_API $plugin) {
		if (self::$instance == null)
			self::$instance = $this;
		
		$this->server = Server::getInstance ();
		$this->plugin = $plugin;
		
		$this->server->getScheduler ()->scheduleRepeatingTask ( new AutoUnloadTask ( $this ), 12000 );
	}
	/**
	 * Create a default setting
	 *
	 * @param string $userName        	
	 */
	public function loadPsychoPass($userName) {
		$userName = strtolower ( $userName );
		$alpha = substr ( $userName, 0, 1 );
		
		if (isset ( $this->users [$userName] ))
			return $this->users [$userName];
		
		if (! file_exists ( $this->plugin->getDataFolder () . "player/" ))
			@mkdir ( $this->plugin->getDataFolder () . "player/" );
		
		return $this->users [$userName] = new PsychoPassData ( $userName, $this->plugin->getDataFolder () . "player/" );
	}
	public function unloadPsychoPass($userName = null) {
		if ($userName === null) {
			foreach ( $this->users as $userName => $PsychoPassData ) {
				if ($this->users [$userName] instanceof PsychoPassData)
					$this->users [$userName]->save ( true );
				unset ( $this->users [$userName] );
			}
			return true;
		}
		
		$userName = strtolower ( $userName );
		if (! isset ( $this->users [$userName] ))
			return false;
		if ($this->users [$userName] instanceof PsychoPassData) {
			$this->users [$userName]->save ( true );
		}
		unset ( $this->users [$userName] );
		return true;
	}
	/**
	 * Get PsychoPass Data
	 *
	 * @param Player $player        	
	 * @return PsychoPassData
	 */
	public function getPsychoPass(Player $player) {
		$userName = strtolower ( $player->getName () );
		if (! isset ( $this->users [$userName] ))
			$this->loadPsychoPass ( $userName );
		return $this->users [$userName];
	}
	/**
	 * Get PsychoPass Data
	 *
	 * @param string $player        	
	 * @return PsychoPassData
	 */
	public function getPsychoPassToName($name) {
		$userName = strtolower ( $name );
		if (! isset ( $this->users [$userName] ))
			$this->loadPsychoPass ( $userName );
		return $this->users [$userName];
	}
	public function save($async = false) {
		foreach ( $this->users as $userName => $PsychoPassData )
			if ($PsychoPassData instanceof PsychoPassData)
				$PsychoPassData->save ( $async );
	}
	/**
	 *
	 * @return AreaLoader
	 */
	public static function getInstance() {
		return static::$instance;
	}
}

?>