<?php

namespace WppStarter\Controller;

use Doctrine\ORM\EntityManager;

/**
 * Class Controller
 * @package WppStarter\Controller
 */
abstract class Controller
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $config;

    /**
     * Controller constructor.
     *
     * @param $twig
     * @param $entityManager
     * @param $config
     */
    public function __construct($twig, $entityManager = null, $config = [])
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
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
     * @return EntityManager|null
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
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