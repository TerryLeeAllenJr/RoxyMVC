<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 5:23 PM
 */

namespace Core;
class Security
{
    public function __construct(){
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $this->log_login = $this->getIpLog();
        $this->blacklist = $this->getBlackList();
    }

    private function getIpLog(){
        return $this->db->select("SELECT * FROM logs_login",array());
    }

    private function getBlackList(){
        return $this->db->select("SELECT * FROM blacklist",array());
    }


    /*
     * log_loginAttempt
     * @param $data array Use array('ip'=>(int)xxx.xxx.xxx.xxx,'browser'=>(string));
     * return int Number of time user is logged into the database.
     */
    public function log_loginAttempt($data){


    }

    public function checkBlacklist(){
        //header('Location: '.URL.'error/blacklist/');
    }


}
