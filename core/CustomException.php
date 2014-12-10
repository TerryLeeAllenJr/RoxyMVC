<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 3/6/14
 * Time: 10:16 AM
 */
namespace Core;
class CustomException extends Exception
{
    public function errorMessage()
    {
        //error message
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': <b>'.$this->getMessage().'</b> is not a valid E-Mail address';
        return $errorMsg;
    }
} 