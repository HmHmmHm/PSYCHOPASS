<?php

namespace PSYCHOPASS_API\psychopass;

use pocketmine\utils\Config;

class PsychoPassData {
	private $userName;
	private $dataFolder;
	private $data;
	const FILTER_VIOLATION = 10;
	const DEFENDER_VIOLATION = 10;
	const UNACCEPTABLE_PVP = 5;
	const UNACCEPTABLE_BLOCK_REPLACE = 5;
	const EXPULSION_VOTE = 40;
	public function __construct($userName, $dataFolder) {
		$userName = strtolower ( $userName );
		
		$this->userName = $userName;
		$this->dataFolder = $dataFolder . substr ( $userName, 0, 1 ) . "/";
		
		if (! file_exists ( $this->dataFolder ))
			@mkdir ( $this->dataFolder );
		
		$this->load ();
	}
	public function load() {
		$this->data = (new Config ( $this->dataFolder . $this->userName . ".json", Config::JSON, [ 
				"colorGraph" => [ 
						"A" 
				],
				"crimeCoefficient" => 0 
		] ))->getAll ();
	}
	public function save($async = false) {
		$data = new Config ( $this->dataFolder . $this->userName . ".json", Config::JSON );
		$data->setAll ( $this->data );
		$data->save ( $async );
	}
	// add is set get delete
	public function getCrimeCoefficient() {
		return $this->data ["crimeCoefficient"];
	}
	public function setCrimeCoefficient($crimeCoefficient) {
		$this->data ["crimeCoefficient"] = $crimeCoefficient;
	}
	public function addColorGraph($color) {
		$this->data ["colorGraph"] [] = $color;
	}
	public function clearColorGraph() {
		$this->data ["colorGraph"] = [ 
				"A" 
		];
	}
}

?>