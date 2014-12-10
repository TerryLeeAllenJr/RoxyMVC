<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 4:39 PM
 */

namespace Core;
class View
{

    function __construct()
    {
        //echo 'this is the view';
    }

    public function render($name, $noInclude = false)
    {
        require_once 'views/' . $name . '.php';
    }

}