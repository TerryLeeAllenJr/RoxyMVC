<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 4:38 PM
 */
namespace Core;
class Controller
{

    public $model;

    function __construct()
    {
        $this->view = new View();
    }

    /**
     *
     * @param string $name Name of the model
     * @param string $path Location of the models
     */
    public function loadModel($path='models/index_model.php')
    {
        $path = $_SERVER['DOCUMENT_ROOT'].'/'.$path;
        // TODO: Error Handling
        if (file_exists($path)) {
            require $path;
            return true;
        }else{
            die('Failed to load model...');
        }
    }

}