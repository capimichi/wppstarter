<?php

namespace WppStarter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class AdminPageAnnotation extends Annotation
{
    /**
     * @var string
     */
    public $pageTitle;

    /**
     * @var string
     */
    public $menuTitle;

    /**
     * @var string
     */
    public $slug;

    /**
     * @var string
     */
    public $icon = "";

    /**
     * @var string
     */
    public $capability;

    /**
     * @var int|null
     */
    public $position = null;
    
    /**
     * @var string|null
     */
    public $parentSlug = null;

}