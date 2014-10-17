<?php
/**
 *
 */
namespace Core\Controllers;
class ErrorController extends \Core\Controller {

    function __construct()
    {
        parent::__construct();

    }

    function index(){
        // Instantiate the model.
        $this->model = new \Core\Models\IndexModel;

        // Setup any variables that need to be passed to the view.
        $this->view->title = "RoxyMVC | Home";

        //Render the page.
        $this->view->render('error/index');
    }
}