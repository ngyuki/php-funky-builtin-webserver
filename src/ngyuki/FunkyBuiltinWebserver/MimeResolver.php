<?php
namespace ngyuki\FunkyBuiltinWebserver;

class MimeResolver
{
    /**
     * 拡張子とMIMEタイプのマップ
     *
     * @var array
     */
    private $map = array();

    /**
     * 標準のビルドインサーバで解釈される拡張子
     *
     * @var array
     */
    private static $ignore = array(
        'css',
        'gif',
        'htm',
        'html',
        'jpe',
        'jpeg',
        'jpg',
        'js',
        'png',
        'svg',
        'txt',
    );

    public function __construct($loadCache = true)
    {
        if ($loadCache)
        {
            $this->loadCache($this->getCacheFile());
        }
    }

    private function getCacheFile()
    {
        $res = new Resource();
        return $res->filename('mime.types.json');
    }

    private function loadCache($fn)
    {
        $data = file_get_contents($fn);

        if ($data === false)
        {
            throw new \RuntimeException("unable read file \"$fn\".");
        }

        $map = json_decode($data, true);

        if (!is_array($map))
        {
            throw new \RuntimeException("unable decode json \"$fn\".");
        }

        $this->map = $map;
    }

    public function load($fn)
    {
        $map = $this->parse($fn);
        $this->map = $map + $this->map;
        return $this;
    }

    public function save($fn = null)
    {
        if ($fn === null)
        {
            $fn = $this->getCacheFile();
        }

        $map = $this->map;
        ksort($map);

        $data = json_encode($this->map, JSON_PRETTY_PRINT);

        if (!is_string($data))
        {
            throw new \RuntimeException("unable encode json.");
        }

        if (file_put_contents($fn, $data) === false)
        {
            throw new \RuntimeException("unable write file \"$fn\".");
        }

        return $this;
    }

    public function resolve($ext)
    {
        if (isset($this->map[$ext]))
        {
            return $this->map[$ext];
        }
        else
        {
            return false;
        }
    }

    private function parse($fn)
    {
        $list = file($fn);

        if ($list === false)
        {
            throw new \RuntimeException("unable read file \"$fn\".");
        }

        $ignore = array_flip(self::$ignore);

        $map = array_reduce($list, function ($map, $line) use ($ignore) {

            $line = trim($line);
            $exts = preg_split('/\s+/', $line);

            $type = array_shift($exts);

            if (strlen($type) === 0)
            {
                return $map;
            }

            if ($type[0] === '#')
            {
                return $map;
            }

            foreach ($exts as $ext)
            {
                if (!isset($ignore[$ext]))
                {
                    $map[$ext] = $type;
                }
            }

            return $map;

        }, []);

        return $map;
    }
}
