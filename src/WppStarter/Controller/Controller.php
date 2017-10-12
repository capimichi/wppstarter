<?php

namespace WppStarter\Controller;

use Doctrine\ORM\EntityManager;
use Pixie\QueryBuilder\QueryBuilderHandler;

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
     * @var QueryBuilderHandler
     */
    protected $qb;

    /**
     * @var array
     */
    protected $config;

    /**
     * Controller constructor.
     *
     * @param $twig
     * @param $qb
     * @param $config
     */
    public function __construct($twig, $qb, $config = [])
    {
        $this->twig = $twig;
        $this->qb = $qb;
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