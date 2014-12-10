<?php
/**
 *
 */
namespace Core;
class Error extends Controller {

    function __construct()
    {
        parent::__construct();

    }

    function index(){
        echo phpInfo();
        exit;
    }
}