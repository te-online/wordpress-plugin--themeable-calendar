<?php
namespace TE\CAL;

class Calendar {
	private $events;
	private $source;
	private $lastCached;
	private $cacheInterval;
	private $firstCachedDate;
	private $lastCachedDate;

	public function __construct() {

	}

	public function getEvents($options = array()) {

	}

	public function getEventsFromCache($options = array()) {

	}

	public function updateCache() {

	}

	public function mustUpdateCache($firstDate, $lastDate) {
		$now = new Date();
		if($lastCached + $cacheInterval > $now) {
			return true;
		}
		if($firstDate < $firstCachedDate || $lastDate > $lastCachedDate) {
			return true;
		}

		return false;
	}

	public function getEventsAsJSON($options = array()) {

	}

}
?>