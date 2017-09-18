<?php

namespace WppStarter\Wordpress;

class AdminPageWordpress
{

    // add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )


    /**
     * @var string
     */
    private $pageTitle;

    /**
     * @var string
     */
    private $menuTitle;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $capability;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var string|null
     */
    private $parentSlug;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $icon;

    /**
     * WPAdminPage constructor.
     * @param $pageTitle
     * @param $menuTitle
     * @param $slug
     * @param $capability
     * @param callable $callable
     * @param string $parentSlug
     * @param string $icon
     * @param null $position
     */
    public function __construct($pageTitle, $menuTitle, $slug, $capability, callable $callable, $parentSlug = null, $icon = "", $position = null)
    {
        $this->pageTitle = $pageTitle;
        $this->menuTitle = $menuTitle;
        $this->slug = $slug;
        $this->capability = $capability;
        $this->callable = $callable;
        $this->parentSlug = $parentSlug;
        $this->icon = $icon;
        $this->position = $position;
    }

    /**
     * Load in memory
     */
    public function load()
    {
        if (!$this->hasParent()) {
            if (function_exists("add_menu_page")) {
                add_menu_page(
                    $this->pageTitle,
                    $this->menuTitle,
                    $this->capability,
                    $this->slug,
                    $this->callable,
                    $this->icon,
                    $this->position
                );
            }
        } else {
            if (function_exists("add_submenu_page")) {
                add_submenu_page(
                    $this->parentSlug,
                    $this->pageTitle,
                    $this->menuTitle,
                    $this->capability,
                    $this->slug,
                    $this->callable
                );
            }
        }
    }

    /**
     * @return bool
     */
    protected function hasParent()
    {
        return isset($this->parentSlug);
    }


}