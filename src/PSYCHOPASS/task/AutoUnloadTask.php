<?php

namespace PSYCHOPASS_API\task;

use pocketmine\scheduler\Task;
use PSYCHOPASS_API\PSYCHOPASS_API;

class AutoUnloadTask extends Task {
	protected $owner;
	public function __construct(PSYCHOPASS_API $owner) {
		$this->owner = $owner;
	}
	public function onRun($currentTick) {
		$this->owner->unloadRank ();
	}
}
?>