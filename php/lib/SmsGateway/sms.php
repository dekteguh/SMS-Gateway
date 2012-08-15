<?php

require "sms/db.php"

namespace SmsGateway;

/**
 *
 *
 *
 *
 */
class sms {
	protected $db;

	public function __construct($) {
		try {
			$this->db = \SmsGateway\db::getInstance(
					$dbname, $dbuser, $dbpass, 
					$dbhost, $dbport, $dbsocket
				);

			return true;
		} catch (\SmsGateway\Exception $e) {
			return $e;
		}
	}
}
