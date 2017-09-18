<?php
namespace WppStarter\Wordpress;

class AjaxWordpress
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $public;
    /**
     * @var bool
     */
    private $admin;

    /**
     * @var callable
     */
    private $callable;

    /**
     * WPAjax constructor.
     * @param $name
     * @param callable $callable
     * @param bool $public
     * @param bool $admin
     */
    public function __construct($name, callable $callable, $public = true, $admin = true)
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->public = $public;
        $this->admin = $admin;
    }

    /**
     * Load in memory
     */
    public function load()
    {
        if (function_exists("add_action")) {
            if ($this->isPublic()) {
                add_action("wp_ajax_nopriv_{$this->getName()}", $this->getCallable());
            }
            if ($this->isAdmin()) {
                add_action("wp_ajax_{$this->getName()}", $this->getCallable());
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @return callable
     */
    protected function getCallable()
    {
        return $this->callable;
    }
}