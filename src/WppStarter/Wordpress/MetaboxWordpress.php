<?php
namespace WppStarter\Wordpress;

class MetaboxWordpress
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
     * @var string|array
     */
    private $screen;

    /**
     * @var string , accepted values: normal, side, advanced
     */
    private $context;

    /**
     * @var string , accepted values: high, low
     */
    private $priority;

    /**
     * @var array
     */
    private $form = array();

    /**
     * WPMetabox constructor.
     * @param string $name
     * @param callable $callable
     * @param array|string $screen
     * @param string $context
     * @param string $priority
     * @param array $form
     */
    public function __construct($name, callable $callable, $screen = "post", $context = "advanced", $priority = "default", array $form = array())
    {
        $this->name = $name;
        $this->callable = $callable;
        $this->screen = $screen;
        $this->context = $context;
        $this->priority = $priority;
        $this->form = $form;
    }

    /**
     * Load in memory
     */
    public function load()
    {
        if (function_exists("add_meta_box")) {
            add_meta_box($this->getId(), $this->getName(), $this->getCallable(), $this->getScreen(), $this->getContext(), $this->getPriority(), $this->getForm());
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        $id = str_replace(" ", "_", $this->getName());
        return $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @return array|string
     */
    public function getScreen()
    {
        return $this->screen;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return array
     */
    public function getForm()
    {
        return $this->form;
    }


}