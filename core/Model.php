<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 4:39 PM
 */

namespace Core;
class Model
{
    function __construct()
    {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);

        $client = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '10.0.0.1',
            'port'   => 6379,
        ]);
    }

}