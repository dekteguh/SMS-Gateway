<?php

namespace \SmsGateway\Database;

abstract class DbAbstract {
    private $dbinstance,
            $instace = null,
            $creatorTag;

    protected function __construct($config = null);
    protected function __clone ();

    public function __destruct();

    public static function get_instance($config = null) {
        if (self::$instance === null) {
            self::$instance = new __CLASS__($config);
        }

        return self::$instance;
    }

    /**
     * Splits up long messages into message parts
     *
     * @param   string  &$text
     * @param   array   &$sequences
     */
    protected function split_sms(&$text, &$sequences) {                       
        $sequences = str_split(substr($text, 160), 140);                       
        $text      = substr($text, 0, 160);                                    
    }

    public function send_sms ($receiver, $text, $flash = -1, $time = null,     
        $report = true);

    public function list_sent_sms ($limit = 20, $offset = 0);
    public function list_received_sms ($limit = 20, $offset = 0);

    public function get_status ($message_id);
}
