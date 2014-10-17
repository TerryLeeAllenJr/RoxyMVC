<?php

namespace Core;
class Notification
{

    /**
     *    Notification Types:
     *      1 - Fatal Error
     *      2 - Error
     *      3 - Warning
     *      4 - Notice
     *      5 - Message
     *      6 - Activity
     *      7 - Log
     */

    function __construct()
    {
        $this->db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
    }

    function create($type,$createdBy,$message){
        $this->db->insert('notification_logs',
            array(
                'type'=>$type,
                'createdBy'=>$createdBy,
                'message'=>$message
            ));
    }

    function getNotifications(){
        return $this->db->select("SELECT * FROM notification_logs",array());
    }

    function getTypes(){
        return $this->db->select("SELECT * FROM notification_types",array());
    }

}