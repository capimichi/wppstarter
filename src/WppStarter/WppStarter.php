<?php

namespace WppStarter;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use WppStarter\Parser\AnnotationParser;

class WppStarter
{
    const DOCTRINE_ANNOTATION_CACHE_DIR = "doctrine";

    const TWIG_CACHE_DIR = "twig";

    const DEFAULT_CONFIG = [
        'dev'             => false,
        'cache_dir'       => null,
        'views_dir'       => null,
        'twig_extensions' => [
            'globals'   => [],
            'filters'   => [],
            'functions' => [],
        ],
        'database_config' => [
            'dbname'   => null,
            'user'     => 'root',
            'password' => '',
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ],
    ];


    /**
     * DefaultKernel constructor.
     * @param array $bundles
     * @param array $config
     */
    public function __construct(array $bundles, array $config)
    {
        $config = $this->parseConfig($config);
        foreach ($bundles as $bundle) {

            $controllers = $this->istantiateControllers($bundle, $config);

            foreach ($controllers as $controller) {

                $methods = get_class_methods($controller);

                foreach ($methods as $method) {

                    $reflMethod = new \ReflectionMethod($controller, $method);

                    $annotations = $this->initAnnotationReader($config)->getMethodAnnotations($reflMethod);

                    foreach ($annotations as $annotation) {
                        new AnnotationParser($annotation, array($controller, $method));
                    }
                }
            }
        }
    }

    /**
     * @param $config
     * @return array
     */
    protected function parseConfig($config)
    {
        $config = array_merge(self::DEFAULT_CONFIG, $config);
        $directories = [
            'cache_dir',
            'views_dir',
        ];
        foreach ($directories as $directory) {
            $config[$directory] = $config[$directory] ? rtrim($config[$directory], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : $config[$directory];
        }
        return $config;
    }

    /**
     * @param $config
     * @return \Twig_Environment
     */
    protected function initTwig($config)
    {
        $viewsDir = $config['views_dir'];
        $loader = new \Twig_Loader_Filesystem($viewsDir);
        $cacheDir = $this->getCacheDir($config);
        $cacheDir = $config['dev'] ? false : $cacheDir . self::TWIG_CACHE_DIR . DIRECTORY_SEPARATOR;
        $twig = new \Twig_Environment($loader, array(
            'cache' => $cacheDir,
        ));

        // Filters
        $twigFilters = $config['twig_extensions']['filters'];
        foreach ($twigFilters as $twigFilter) {
            $twig->addFilter($twigFilter);
        }

        // Functions
        $twigFunctions = $config['twig_extensions']['functions'];
        foreach ($twigFunctions as $twigFunction) {
            $twig->addFunction($twigFunction);
        }

        // Globals
        $twigGlobals = $config['twig_extensions']['globals'];
        foreach ($twigGlobals as $twigGlobalKey => $twigGlobalValue) {
            $twig->addGlobal($twigGlobalKey, $twigGlobalValue);
        }

        return $twig;
    }

    /**
     * @param $config
     * @return AnnotationReader|CachedReader
     */
    protected function initAnnotationReader($config)
    {
        $cacheDir = $this->getCacheDir($config);
        if ($cacheDir) {
            $cacheDir .= self::DOCTRINE_ANNOTATION_CACHE_DIR . DIRECTORY_SEPARATOR;
            $cache = new FilesystemCache($cacheDir);
            return new CachedReader(new AnnotationReader(), $cache, $config['dev']);
        } else {
            return new AnnotationReader();
        }
    }

    /**
     * @param $config
     * @return null|string
     */
    protected function getCacheDir($config)
    {
        if ($this->isCacheEnabled($config)) {
            return rtrim($config['cache_dir'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        } else {
            return null;
        }
    }

    /**
     * @param $config
     * @return bool
     */
    protected function isCacheEnabled($config)
    {
        if (($config['dev'] == true) || (!$config['cache_dir'])) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * @param $bundle
     * @param $config
     * @return array
     */
    protected function istantiateControllers($bundle, $config)
    {
        $bundleRootDir = $this->getBundleRootDir($bundle);
        $controllerNamespace = $this->getControllerNamespace($bundle);
        $controllerDir = $bundleRootDir . "/Controller/";
        $controllerNames = scandir($controllerDir);
        $controllers = [];
        foreach ($controllerNames as $controllerName) {
            if ($this->isPhpFile($controllerName)) {
                $controllerName = preg_replace(" /\.php/", "", $controllerName);
                $r = new \ReflectionClass($controllerNamespace . $controllerName);
                if (!$r->isAbstract()) {
                    $controller = $r->newInstanceArgs([
                        $this->initTwig($config),
                        $this->initEntityManager($config),
                        $config,
                    ]);
                    $controllers[] = $controller;
                }
            }
        }
        return $controllers;
    }

    /**
     * @param $bundle
     * @return string
     */
    protected function getBundleRootDir($bundle)
    {
        $reflector = new \ReflectionClass($bundle);
        $bundleFileName = $reflector->getFileName();
        $bundleRootDir = dirname($bundleFileName);
        return $bundleRootDir;
    }

    /**
     * @param $bundle
     * @return string
     */
    protected function getControllerNamespace($bundle)
    {
        $reflector = new \ReflectionClass($bundle);
        $namespace = $reflector->getNamespaceName();
        $controllerNamespace = $namespace . "\\Controller\\";
        return $controllerNamespace;
    }

    /**
     * @param $file
     *
     * @return bool
     */
    protected function isPhpFile($file)
    {
        return preg_match(" /\.php$/is", $file);
    }

}