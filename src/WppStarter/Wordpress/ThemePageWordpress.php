<?php

namespace WppStarter\Wordpress;

class ThemePageWordpress
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $capability;

    /**
     * @var callable
     */
    private $callable;

    /**
     * WPAdminPage constructor.
     * @param $name
     * @param $capability
     * @param callable $callable
     */
    public function __construct($name, $capability, callable $callable)
    {
        $this->name = $name;
        $this->capability = $capability;
        $this->callable = $callable;
    }

    /**
     * Load in memory
     */
    public function load()
    {
        if (function_exists("add_theme_page")) {
            add_theme_page(
                $this->getPageTitle(),
                $this->getMenuTitle(),
                $this->getCapability(),
                $this->getMenuSlug(),
                $this->getCallable()
            );
        }
    }

    /**
     * @return string
     */
    protected function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    protected function getPageTitle()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    protected function getMenuTitle()
    {
        return ucfirst($this->name);
    }

    /**
     * @return string
     */
    protected function getMenuSlug()
    {
        return strtolower(str_replace(" ", "_", $this->name));
    }

    /**
     * @return string
     */
    protected function getCapability()
    {
        return $this->capability;
    }

    /**
     * @return callable
     */
    protected function getCallable()
    {
        return $this->callable;
    }
}