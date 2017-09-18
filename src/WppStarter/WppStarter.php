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

        $viewsDir = $bundleRootDir . "/Resources/views/";
        $controllerDir = $bundleRootDir . "/Controller/";
        $controllerNames = scandir($controllerDir);
        $controllers = [];
//        $instantiator = new Instantiator();
        foreach ($controllerNames as $controllerName) {
            if (preg_match(" /\.php$/is", $controllerName)) {
                $controllerName = preg_replace(" /\.php/", "", $controllerName);
//                    $controller = $instantiator->instantiate($controllerNamespace . $controllerName);
//                    $controller->setConfig($config);
                $r = new \ReflectionClass($controllerNamespace . $controllerName);
                if (!$r->isAbstract()) {
                    $controller = $r->newInstanceArgs([
                        $viewsDir,
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


}