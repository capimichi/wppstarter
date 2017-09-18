<?php

namespace WppStarter\Parser;

use Doctrine\Common\Annotations\Annotation;
use WppStarter\Annotation\ActionAnnotation;
use WppStarter\Annotation\AdminPageAnnotation;
use WppStarter\Annotation\AjaxAnnotation;
use WppStarter\Annotation\FilterAnnotation;
use WppStarter\Annotation\MetaboxAnnotation;
use WppStarter\Annotation\ShortcodeAnnotation;
use WppStarter\Wordpress\AdminPageWordpress;
use WppStarter\Wordpress\AjaxWordpress;
use WppStarter\Wordpress\MetaboxWordpress;
use WppStarter\Wordpress\ShortcodeWordpress;

class AnnotationParser
{
    /**
     * AnnotationManager constructor.
     * @param Annotation $annotation
     * @param callable $callable
     */
    public function __construct($annotation, $callable)
    {
        switch (true) {
            case $annotation instanceof ActionAnnotation:
                $this->addAction($annotation->hook, $callable, $annotation->priority, $annotation->args);
                break;
            case $annotation instanceof FilterAnnotation:
                $this->addFilter($annotation->hook, $callable, $annotation->priority, $annotation->args);
                break;
            case $annotation instanceof AdminPageAnnotation:
                $adminPage = new AdminPageWordpress(
                    $annotation->pageTitle,
                    $annotation->menuTitle,
                    $annotation->slug,
                    $annotation->capability,
                    $callable,
                    $annotation->parentSlug,
                    $annotation->icon,
                    $annotation->position
                );
                $this->addAdminPage($adminPage);
                break;
            case $annotation instanceof ShortcodeAnnotation:
                $shortcode = new ShortcodeWordpress($annotation->name, $callable);
                $this->addShortcode($shortcode);
                break;
            case $annotation instanceof AjaxAnnotation:
                $ajax = new AjaxWordpress($annotation->name, $callable, $annotation->public, $annotation->admin);
                $this->addAjax($ajax);
                break;
//            case $annotation instanceof ThemePage:
//                $themePage = new WPThemePage(
//                    $annotation->name,
//                    $annotation->capability,
//                    $callable
//                );
//                $this->addThemePage($themePage);
//                break;
            case $annotation instanceof MetaboxAnnotation:
                $metabox = new MetaboxWordpress($annotation->name, $callable, $annotation->screen, $annotation->context, $annotation->priority, $annotation->form);
                $this->addMetabox($metabox);
                break;
        }
    }

    /**
     * @param $hook
     * @param callable $callable
     */
    public function addAction($hook, callable $callable, $priority = 10, $args = 1)
    {
        if (!$hook) {
            $hook = "init";
        }
        if (function_exists("add_action")) {
            add_action($hook, $callable, $priority, $args);
        }
    }

    /**
     * @param $hook
     * @param callable $callable
     */
    public function addFilter($hook, callable $callable, $priority = 10, $args = 1)
    {
        if (!$hook) {
            $hook = "init";
        }
        if (function_exists("add_filter")) {
            add_filter($hook, $callable, $priority, $args);
        }
    }

    /**
     * @param AdminPageWordpress $adminPage
     */
    public function addAdminPage($adminPage)
    {
        $this->addAction("admin_menu", array($adminPage, "load"));
    }

    /**
     * @param ThemePageWordpress $themePage
     */
    public function addThemePage($themePage)
    {
        $this->addAction("admin_menu", array($themePage, "load"));
    }

    /**
     * @param ShortcodeWordpress $shortcode
     */
    public function addShortcode($shortcode)
    {
        $this->addAction("init", array($shortcode, "load"));
    }

    /**
     * @param AjaxWordpress $ajax
     */
    public function addAjax($ajax)
    {
        $ajax->load();
    }

    /**
     * @param MetaboxWordpress $metabox
     */
    public function addMetabox($metabox)
    {
        $this->addAction("add_meta_boxes", array($metabox, "load"));
        $this->addAction("save_post", $metabox->getCallable());
    }

}