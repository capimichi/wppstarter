<?php

namespace WppStarter\Controller;

/**
 * Class Controller
 * @package WppStarter\Controller
 */
abstract class Controller
{

    const TWIG_CACHE_DIRECTORY = "twig";

    /**
     * Configuration:
     *  - cache_dir: path to cache directory
     *
     * @var array
     */
    protected $config;

    /**
     * @var string
     */
    protected $viewsDir;

    /**
     * @var string
     */
    protected $cacheDir;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * Controller constructor.
     * @param $viewsDir
     * @param $cacheDir
     * @param $config
     */
    public function __construct($viewsDir, $cacheDir, $config)
    {
        $this->viewsDir = rtrim($viewsDir, "/") . "/";
        $this->cacheDir = rtrim($cacheDir, "/") . "/";
        $this->twig = $this->buildTwig($viewsDir, $cacheDir, $config);
        $this->config = $config;
    }

    /**
     * @param $view
     * @param array $options
     * @return string
     */
    protected function render($view, $options = [])
    {
        return $this->twig->render($view, $options);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function get($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    /**
     * @param $viewsDir
     * @param $cacheDir
     * @param $config
     *
     * @return \Twig_Environment
     */
    private function buildTwig($viewsDir, $cacheDir, $config)
    {
        $loader = new \Twig_Loader_Filesystem($viewsDir);
        $twig = new \Twig_Environment($loader, array(
            'cache' => $cacheDir . self::TWIG_CACHE_DIRECTORY . DIRECTORY_SEPARATOR,
        ));
        return $twig;
    }
}