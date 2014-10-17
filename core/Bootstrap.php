<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 12/4/13
 * Time: 4:39 PM
 */

namespace Core;
use SebastianBergmann\Exporter\Exception;

class Bootstrap
{

    private $_url = null;
    private $_controller = null;

    private $_controllerPath = 'controllers/'; // Always include trailing slash
    private $_modelPath = 'models/'; // Always include trailing slash
    private $_errorFile = 'error.php';
    private $_defaultFile = 'index.php';

    /**
     * Starts the Bootstrap
     *
     * @return boolean
     */
    public function init()
    {
        // Sets the protected $_url

        $this->_url = $url =$this->_getUrl(isset($_GET['url']) ? $_GET['url'] : null);

        // Load the default controller if no URL is set
        // eg: Visit http://localhost it loads Default Controller
        if (empty($url[0])) {
            $this->_loadDefaultController();
            return false;
        }

        $this->_loadExistingController($url);
        $this->_callControllerMethod();
    }

    /**
     * (Optional) Set a custom path to controllers
     * @param string $path
     */
    public function setControllerPath($path)
    {
        $this->_controllerPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to models
     * @param string $path
     */
    public function setModelPath($path)
    {
        $this->_modelPath = trim($path, '/') . '/';
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: error.php
     */
    public function setErrorFile($path)
    {
        $this->_errorFile = trim($path, '/');
    }

    /**
     * (Optional) Set a custom path to the error file
     * @param string $path Use the file name of your controller, eg: index.php
     */
    public function setDefaultFile($path)
    {
        $this->_defaultFile = trim($path, '/');
    }

    /**
     * Fetches the $_GET from 'url'
     *
     * @param string $url The $_GET data parsed by .htaccess
     * @return array The url array used by the rest of the class.
     */
    private function _getUrl($url)
    {
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        return explode('/', $url);
    }

    /**
     * This loads if there is no GET parameter passed
     *
     * @param bool $test Is this a test? BAD DOG! BAD!
     * @return bool Checks if the file exists.
     */
    private function _loadDefaultController($test=false)
    {
        try{

            if (!is_file($this->_controllerPath . $this->_defaultFile)){
                throw new Exception("Default controller not found");
            }
            if(!$test){
                require $this->_controllerPath . $this->_defaultFile;
                $this->_controller = new Index();
                $this->_controller->index();
            }
            return true;
        } catch (Exception $e){

            if(!file_exists($this->_controllerPath)){
                // TODO: Log error to database and send alert.
                error_log($e->getCode()." ".$e->getMessage());
            }
            return false;
        }

    }

    /**
     * Load an existing controller if there IS a GET parameter passed
     *
     * @param array $url The url array created in _getUrl()
     * @param bool $test Used for phpUnit. Woof!
     * @return boolean|string
     */
    private function _loadExistingController($url,$test = false)
    {

        try {

            $file = $this->_controllerPath . $url[0] . '.php';
            if (file_exists($file)) {
                if(!$test){
                    require $file;
                    $this->_controller = new $this->_url[0];
                }
                return true;
            }

        } catch (Exception $e) {
            return ($test) ? true : $this->_error();
        }



        $file = $this->_controllerPath . $this->_url[0] . '.php';
        if (file_exists($file)) {
            require $file;
            $this->_controller = new $this->_url[0];
        } else {
            $this->_error();
            return false;
        }
    }

    /**
     * If a method is passed in the GET url paremter
     *
     *  http://localhost/controller/method/(param)/(param)/(param)
     *  url[0] = Controller
     *  url[1] = Method
     *  url[2] = Param
     *  url[3] = Param
     *  url[4] = Param
     */
    private function _callControllerMethod()
    {
        $length = count($this->_url);

        // Make sure the method we are calling exists
        if ($length > 1) {
            if (!method_exists($this->_controller, $this->_url[1])) {
                $this->_error();
            }
        }

        // Determine what to load
        switch ($length) {
            case 5:
                //Controller->Method(Param1, Param2, Param3)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3], $this->_url[4]);
                break;

            case 4:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2], $this->_url[3]);
                break;

            case 3:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}($this->_url[2]);
                break;

            case 2:
                //Controller->Method(Param1, Param2)
                $this->_controller->{$this->_url[1]}();
                break;

            default:
                $this->_controller->index();
                break;
        }
    }

    /**
     * Display an error page if nothing exists
     *
     * @return boolean
     */
    private function _error()
    {
        require $this->_controllerPath . $this->_errorFile;
        $this->_controller = new Error();
        $this->_controller->index();
        exit;
    }

}