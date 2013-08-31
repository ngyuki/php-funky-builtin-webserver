<?php
namespace ngyuki\FunkyBuiltinWebserver;

class Router
{
    private $base = null;

    public static function create()
    {
        return new self;
    }

    public function __construct($base = null)
    {
        if ($base === null)
        {
            $base = getcwd();
        }
        else
        {
            $base = realpath($base);

            if ($base === false)
            {
                throw new \RuntimeException("unable directory \$base.");
            }
        }

        $this->base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function run()
    {
        $path = $_SERVER['SCRIPT_NAME'];

        $path = $this->fixPath($path);

        $full = $this->base . $path;

        if (is_dir($full))
        {
            $list = $this->collectDirectoryIndex($path, $full);
            $breadcrumb = $this->collectBreadcrumb($path);

            $this->render($path, $list, $breadcrumb);
            return true;
        }
        else if (is_file($full))
        {
            $ext = pathinfo($full, PATHINFO_EXTENSION);
            $resolver = new MimeResolver();
            $type = $resolver->resolve($ext);

            if ($type !== false)
            {
                header("Content-Type: $type");
                readfile($full);
                return true;
            }
        }

        return false;
    }

    /**
     * パスの先頭からスラッシュを除去して終端に付与
     *
     * @param string $path
     * @return string
     */
    private function fixPath($path)
    {
        $path = trim($path, '/');

        if (strlen($path))
        {
            $path = '/' . $path;
        }

        return $path;
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
     * @param string $full
     *
     * @return array
     */
    private function collectDirectoryIndex($path, $full)
    {
        $path = '/' . trim($path, '/');

        if ($path !== '/')
        {
            $path .= '/';
        }

        $path = self::urlencode($path);

        $list = array();

        foreach (new \FilesystemIterator($full) as $info)
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

    /**
     * 表示
     *
     * @param string $path
     * @param array $list
     * @param array $breadcrumb
     */
    private function render($path, $filelist, $breadcrumb)
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__);

        $twig = new \Twig_Environment($loader, array(
            'debug' => false,
            'strict_variables' => true,
        ));

        $content = $twig->render('index.html.twig', array(
            'path' => $path,
            'filelist' => $filelist,
            'breadcrumb' => $breadcrumb,
        ));

        echo $content;
    }
}
