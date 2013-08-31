<?php
namespace ngyuki\FunkyBuiltinWebserver;

class Renderer
{
    public function header($name, $val)
    {
        header("$name: $val");
    }

    public function rawfile($file)
    {
        readfile($file);
    }

    public function render($path, $filelist, $breadcrumb)
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
