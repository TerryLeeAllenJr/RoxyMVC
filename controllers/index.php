<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 5:07 PM
 */
namespace Core;
class Index extends Controller
{

    function __construct()
    {
        parent::__construct();

    }

    function index($location = null)
    {

        // Instantiate the model.
        $this->model = new \Core\Models\IndexModel;

        // Setup any variables that need to be passed to the view.
        $this->view->title = "RoxyMVC | Home";

        //Render the page.
        $this->view->render('index/index');

    }

}