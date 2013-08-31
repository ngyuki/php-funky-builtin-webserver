<?php
namespace ngyuki\FunkyBuiltinWebserver;

class Router
{
    private $base;
    private $renderer;

    public static function create()
    {
        return new self(getcwd());
    }

    public function __construct($base, Renderer $renderer = null)
    {
        $base = realpath($base);

        if ($base === false)
        {
            throw new \RuntimeException("invalid path \$base.");
        }

        if ($renderer === null)
        {
            $renderer = new Renderer();
        }

        $this->base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->renderer = $renderer;
    }

    public function run()
    {
        $path = $_SERVER['SCRIPT_NAME'];
        $real = realpath($this->base . $path);

        if (is_dir($real))
        {
            $filelist = $this->collectDirectoryIndex($path, $real);
            $breadcrumb = $this->collectBreadcrumb($path);

            $this->renderer->render($path, $filelist, $breadcrumb);

            return true;
        }
        else if (is_file($real))
        {
            $ext = pathinfo($real, PATHINFO_EXTENSION);
            $resolver = new MimeResolver();
            $type = $resolver->resolve($ext);

            if ($type !== false)
            {
                $this->renderer->header('Content-Type', $type);
                $this->renderer->rawfile($real);

                return true;
            }
        }

        return false;
    }

    /**
     * スラッシュを除いたURLエンコード
     */
    private static function urlencode($url)
    {
        $arr = explode('/', $url);
        $arr = array_map(function ($str) { return urlencode($str); }, $arr);
        return implode('/', $arr);
    }

    /**
     * ディレクトリインデックスの情報を収集
     *
     * @param string $path
     * @param string $real
     *
     * @return array
     */
    private function collectDirectoryIndex($path, $real)
    {
        $path = '/' . trim($path, '/');

        if ($path !== '/')
        {
            $path .= '/';
        }

        $path = self::urlencode($path);

        $list = array();

        foreach (new \FilesystemIterator($real) as $info)
        {
            $info instanceof \SplFileInfo;

            $list[] = $obj = new \stdClass();
            $obj->name = $info->getFilename();
            $obj->url = $path . urlencode($info->getFilename());

            if ($info->isDir())
            {
                $obj->name .= '/';
                $obj->url .= '/';
            }
        }

        return $list;
    }

    /**
     * パンくずリストの情報を収集
     *
     * @param string $path
     *
     * @return array
     */
    private function collectBreadcrumb($path)
    {
        $path = trim($path, '/');
        $arr = explode('/', $path);
        $arr = array_filter($arr, 'strlen');

        $url = '/';

        $list = array();

        foreach ($arr as $name)
        {
            $list[] = $obj = new \stdClass();

            $url .= urlencode($name) . '/';
            $obj->url = $url;
            $obj->name = $name;
        }

        return $list;
    }
}
