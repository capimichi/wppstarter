<?php
namespace WppStarter\Wordpress;

class ShortcodeWordpress
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callable;

    /**
     * WPShortcode constructor.
     * @param string $name
     * @param callable $callable
     */
    public function __construct($name, callable $callable)
    {
        $this->name = $name;
        $this->callable = $callable;
    }

    /**
     * Load in memory
     */
    public function load(){
        if(function_exists("add_shortcode")) {
            add_shortcode($this->getName(), $this->getCallable());
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
     * @return callable
     */
    protected function getCallable()
    {
        return $this->callable;
    }
}