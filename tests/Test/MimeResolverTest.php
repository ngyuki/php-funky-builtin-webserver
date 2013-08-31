<?php
namespace Test;

use ngyuki\FunkyBuiltinWebserver\MimeResolver;
use ngyuki\FunkyBuiltinWebserver\Resource;

/**
 * @author ng
 */
class MimeResolverTest extends \PHPUnit_Framework_TestCase
{
    private $resource;

    protected function setUp()
    {
        $this->resource = new Resource();
    }

    /**
     * @test
     */
    function resolve_cache_empty()
    {
        $obj = new MimeResolver(false);
        $type = $obj->resolve('psd');

        assertFalse($type);
    }

    /**
     * @test
     */
    function resolve_ok()
    {
        $obj = new MimeResolver(false);
        $obj->load($this->resource->filename('mime.types'));

        $type = $obj->resolve('psd');

        assertEquals('image/vnd.adobe.photoshop', $type);
    }

    /**
     * @test
     */
    function resolve_ng()
    {
        $obj = new MimeResolver(false);
        $obj->load($this->resource->filename('mime.types'));

        $type = $obj->resolve('unknown-ext');

        assertFalse($type);
    }

    /**
     * @test
     */
    function resolve_ignore()
    {
        $obj = new MimeResolver(false);
        $obj->load($this->resource->filename('mime.types'));

        $type = $obj->resolve('php');

        assertFalse($type);
    }

    /**
     * @test
     */
    function resolve_by_override()
    {
        $obj = new MimeResolver();
        $obj->load($this->resource->filename('mime.types'));
        $obj->load($this->resource->filename('mime.types.override'));

        $type = $obj->resolve("md");

        assertEquals('text/plain', $type);
    }

    /**
     * @test
     */
    function resolve_by_cache()
    {
        $obj = new MimeResolver();

        $type = $obj->resolve("md");

        assertEquals('text/plain', $type);
    }

    /**
     * @test
     */
    function save()
    {
        $fn = 'php://output';

        $obj = new MimeResolver();
        $obj->load($this->resource->filename('mime.types'));

        ob_start();

        try
        {
            $obj->save($fn);
            $out = ob_get_clean();
        }
        catch (\Exception $ex)
        {
            ob_get_clean();
            throw $ex;
        }

        $map = json_decode($out, true);
        assertInternalType(gettype(array()), $map);
    }
}
