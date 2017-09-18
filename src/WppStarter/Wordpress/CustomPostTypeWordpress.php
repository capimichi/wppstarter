<?php
namespace WppStarter\Wordpress;

class CustomPostTypeWordpress
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $pluralName;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var bool
     */
    private $showInMenu;

    /**
     * @var bool
     */
    private $hasArchive;

    /**
     * @var array
     */
    private $supports;

    /**
     * @var int
     */
    private $menuPosition;

    /**
     * @var string
     */
    private $icon;

    public function __construct($name, $pluralName, $slug, $icon = "", $description = "", $showInMenu = true, $hasArchive = false, $supports = null)
    {
        $this->name = $name;
        $this->pluralName = $pluralName;
        $this->slug = $slug;
        $this->icon = $icon;
        $this->description = $description;
        $this->showInMenu = $showInMenu;
        $this->hasArchive = $hasArchive;
        if(!$supports){
            $supports = array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' );
        }
        $this->supports = $supports;
    }

    /**
     * Load in memory
     */
    public function load(){
        $args = array(
            'labels'             => $this->getLabels(),
            'description'        => $this->getDescription(),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => $this->isShowInMenu(),
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $this->getSlug() ),
            'capability_type'    => 'post',
            'has_archive'        => $this->isHasArchive(),
            'hierarchical'       => false,
            'menu_position'      => $this->getMenuPosition(),
            'supports'           => $this->getSupports(),
            'menu_icon'          => $this->getIcon(),
        );
        $parsedName = strtolower(str_replace(" ", "_", $this->getName()));
        if(function_exists("register_post_type")) {
            register_post_type($parsedName, $args);
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
     * @return string
     */
    public function getPluralName()
    {
        return $this->pluralName;
    }

    /**
     * @return string
     */
    public function getDescription(){
        return $this->description;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return bool
     */
    public function isShowInMenu()
    {
        return $this->showInMenu;
    }

    /**
     * @return bool
     */
    public function isHasArchive()
    {
        return $this->hasArchive;
    }

    /**
     * @return array
     */
    public function getSupports()
    {
        return $this->supports;
    }

    /**
     * @return int
     */
    public function getMenuPosition()
    {
        return $this->menuPosition;
    }

    /**
     * @return array
     */
    protected function getLabels(){
        $name = ucfirst($this->getName());
        $pluralName = ucfirst($this->getPluralName());
        $labels = array(
            "name"               => $pluralName,
            "singular_name"      => $name,
            "menu_name"          => $pluralName,
            "name_admin_bar"     => $name,
            "add_new"            => "Add New",
            "add_new_item"       => "Add New {$name}",
            "new_item"           => "New {$name}",
            "edit_item"          => "Edit {$name}",
            "view_item"          => "View {$name}",
            "all_items"          => "All {$pluralName}",
            "search_items"       => "Search {$pluralName}",
            "parent_item_colon"  => "Parent {$pluralName}:",
            "not_found"          => "No {$pluralName} found.",
            "not_found_in_trash" => "No {$pluralName} found in Trash.",
        );
        return $labels;
    }
}