<?php

namespace WppStarter\Controller;


abstract class Controller
{

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
     * Controller constructor.
     * @param $viewsDir
     * @param $config
     */
    public function __construct($viewsDir, $config)
    {
        $this->viewsDir = rtrim($viewsDir, "/") . "/";
        if (isset($config['cache_dir'])) {
            $config['cache_dir'] = rtrim($config['cache_dir'], "/") . "/";
        }
        $this->config = $config;
    }

    /**
     * @param $view
     * @param array $options
     * @return string
     */
    protected function render($view, $options = [])
    {
        extract($options);
        ob_start();
        require $this->viewsDir . $view;
        $content = ob_get_clean();
        return $content;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    protected function get($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }
}