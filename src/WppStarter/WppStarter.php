<?php

namespace WppStarter;

use Doctrine\Common\Annotations\AnnotationReader;
use WppStarter\Parser\AnnotationParser;

class WppStarter
{

    /**
     * DefaultKernel constructor.
     * @param array $bundles
     * @param array $config
     */
    public function __construct(array $bundles, array $config)
    {
        $reader = new AnnotationReader();
        foreach ($bundles as $bundle) {
            $controllers = $this->istantiateControllers($bundle, $config);
            foreach ($controllers as $controller) {
                $methods = get_class_methods($controller);
                foreach ($methods as $method) {
                    $reflMethod = new \ReflectionMethod($controller, $method);
                    $annotations = $reader->getMethodAnnotations($reflMethod);
                    foreach ($annotations as $annotation) {
                        new AnnotationParser($annotation, array($controller, $method));
                    }
                }
            }
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
                        $this->buildViewsDir($bundleRootDir),
                        $this->buildCacheDir(),
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

    /**
     * @param $bundleRootDir
     * @return string
     */
    protected function buildViewsDir($bundleRootDir)
    {
        $viewsDir = $bundleRootDir . "/Resources/views/";
        return $viewsDir;
    }

    /**
     * @return string
     */
    protected function buildCacheDir()
    {
        $uploadDir = wp_upload_dir();
        $cacheDir = $uploadDir['basedir'] . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR;
        return $cacheDir;
    }

}