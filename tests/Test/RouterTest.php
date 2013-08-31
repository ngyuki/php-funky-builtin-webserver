<?php
namespace Test;

use ngyuki\FunkyBuiltinWebserver\Router;
use ngyuki\FunkyBuiltinWebserver\Renderer;

/**
 * @author ng
 */
class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    function directory_index()
    {
        $_SERVER['SCRIPT_NAME'] = '/';

        $this->expectOutputRegex('/sample\.php/');

        $route = new Router(__DIR__ . '/_files/');
        $ret = $route->run();

        assertTrue($ret);
    }

    /**
     * @test
     */
    function phpfile_()
    {
        $_SERVER['SCRIPT_NAME'] = '/sample.php';

        $route = new Router(__DIR__ . '/_files/');
        $ret = $route->run();

        assertFalse($ret);
    }

    /**
     * @test
     */
    function rawfile_()
    {
        $_SERVER['SCRIPT_NAME'] = '/README.md';

        $renderer = $this->getMock(get_class(new Renderer()));

        $renderer->expects(once())->method('header')
            ->with('Content-Type', 'text/plain')
        ;

        $renderer->expects(once())->method('rawfile')
            ->with(realpath(__DIR__ . '/_files/README.md'))
        ;

        $route = new Router(__DIR__ . '/_files/', $renderer);
        $ret = $route->run();

        assertTrue($ret);
    }

    /**
     * @test
     */
    function subdir_index()
    {
        $_SERVER['SCRIPT_NAME'] = '/abc';

        $this->expectOutputRegex('/xyz\.html/');

        $route = new Router(__DIR__ . '/_files/');
        $ret = $route->run();

        assertTrue($ret);
    }
}
