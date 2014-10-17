<?php
/**
 * Created by PhpStorm.
 * User: LeeAllen
 * Date: 10/16/14
 * Time: 2:50 PM
 */

namespace Core\Test;
use Core\Bootstrap;

class BootstrapTest extends \PHPUnit_Framework_TestCase {

    public function test_getUrl(){

        $url = "/admin/getName/test/3";
        $expectedValue = array(
            '',
            'admin',
            'getName',
            'test',
            '3'
        );

        $bootstrap = new Bootstrap();
        $this->assertEquals($expectedValue,
            $this->invokeMethod($bootstrap,'_getUrl',array($url)));

    }

    public function test_loadDefaultController() {
        $bootstrap = new Bootstrap();
        $this->assertNotFalse($this->invokeMethod($bootstrap,'_loadDefaultController',array(true)));
    }

    public function test_callExistingController() {
        $url = array(
            'admin',
            'test',
            '2'
        );
        $bootstrap = new Bootstrap();
        $this->assertNotFalse($this->invokeMethod($bootstrap,'_loadDefaultController',array($url,true)));
    }


    public function invokeMethod(&$object, $methodName, array $parameters = array()){
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
 