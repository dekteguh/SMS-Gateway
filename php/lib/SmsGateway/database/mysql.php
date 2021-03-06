<?php

namespace \SmsGateway\Database;

require 'exception.php';
require 'abstract.php';

class Mysql extents \SmsGateway\Database\DbAbstract {
	private $creatorTag = 'SmsGateway 0.1.0';

	/**
	 *
	 * @param	string	$dbname
	 * @param	string	$dbuser
	 * @param	string	$dbpass
	 * @param	string	$dbhost
	 * @param	string	$dbport
     * @param	string	$dbsocket
     *
     * @throws \SmsGateway\Database\Exception
	 */
	protected function __construct ($config) {
        if (!$config['dbhost']) {
            $config['dbhost'] = 'localhost';
        }

        if (!$config['dbport']) {
            $config['dbport'] = 3306;
        }

        if (!$config['dbsocket']) {
            $config['dbsocket'] = null;
        }

        $this->dbinstance = new mysqli(
            $config['dbhost'], $config['dbuser'}, 
            $config['dbpass'], $config['dbname'],
            $config['dbport'], $config['dbsocket']
        );

		if ($this->dbinstance->connect_error) {
			throw new \SmsGateway\Database\DbException("Connect error");
		}
	}

	/**
	 * Closes connection on object removal
	 */
	public function __destruct () {
		$this->dbinstance->close();
	}

	/**
         *
	 */
	protected function __clone () {}

	/**
	 * Splits up long messages into message parts.
	 *
	 * @param	string	&$text
	 * @param	array	&$sequences
	 */

	/**
	 * @param	string	$receiver
	 * @param	string	$text
	 * @param	int	$flash
	 * @param	mixed	$time
	 * @param	bool	$report
	 */
	public function send_sms ($receiver, $text, $flash = -1, $time = null,
				$report = true) {
		$text_sequences = null;
		$multipart      = 'false';
		$deliveryrepot  = true ? 'yes' : 'no';

		if (strlen($text) > 160) {			
			$this->split_sms($text, $text_sequences);
			$multipart = 'true';
		}

		$query = "INSERT into `outbox` (`DestinationNumber`, `Class`,
				`TextDecoded`, `MultiPart`, `DeliveryReport`,
				`CreatorID`) VALUES 
			('$receiver', '$class', '$text', '$multipart', 
				'$report', '{$this->creatorTag}');";
		$this->dbinstance->query($query);
		$message_id = $this->dbinstance->insert_id;

		if ($multipart === 'true') {
			$i = 2;
			foreach ($text_sequences as $seq) {
				$query = "INSERT INTO `outbox_multipart` 
						(`Class`, `TextDecoded`, `ID`,
						`SequencePosition`) VALUES
						($class, '$seq', $message_id,
						$i)";
				$this->dbinstance->query($query);
				++$i;
			}
		}

		return $message_id;
	}

	/**
	 * List sent SMS
	 *
	 * @param	int	$limit
	 * @param	int	$offset
	 */
	public function list_sent_sms ($limit = 20, $offset = 0) {
		$query     = "SELECT `ID`, `ReceivingDateTime`, `TextDecoded`,
					`SenderNumber`
				FROM inbox
				LIMIT $offset, $limit;";
		$result    = $this->dbinstance->query($query);
		$resultset = array();

		if (!$result) {
			return $resultset;
		}

		$i = 0;
		while ($r = $result->fetch_assoc()) {
			$resultset[$i]["id"]       = $r["ID"];
			$resultset[$i]["received"] = $r["ReceivingDateTime"];
			$resultset[$i]["text"]     = $r["TextDecoded"];
			$resultset[$i]["sender"]   = $r["SenderNumber"];
			++$i;
		}

		return $resultset;
	}

	/**
	 * Lists received SMS
	 *
	 * @param	int	$limit
	 * @param	int	$offset
	 */
	public function list_received_sms ($limit = 20, $offset = 0) {
		$query     = "SELECT `ID`, `ReceivingDateTime`, `TextDecoded`, 
					`SenderNumber` 
				FROM inbox 
				LIMIT $offset, $limit;";
		$result    = $this->dbinstance->query($query);
		$resultset = array();

		if (!$result) {
			return $resultset;
		}

		$i = 0;
		while ($r = $result->fetch_assoc()) {
			$resultset[$i]["id"]       = $r["ID"];
			$resultset[$i]["received"] = $r["ReceivingDateTime"];
			$resultset[$i]["text"]     = $r["TextDecoded"];
			$resultset[$i]["sender"]   = $r["SenderNumber"];
			++$i;
		}

		return $resultset;
	}

	/**
	 * Returns true if sent, false if not sent or unknown
	 *
	 * @param	int	$message_id
	 * @return	bool
	 */
	public function get_status ($message_id = 0) {
		if ($message_id === 0) {
			$message_id = $this->dbinstance->insert_id;
		}

		$status = $this->dbinstance
				->query("SELECT Status FROM sentitems WHERE ID = 12;")
				->fetch_object()
				->Status;

		if ($status === 'DeliveryOK') {
			return true;
		} else {
			return false;
		}
	}
}
