<?php

namespace WppStarter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class AjaxAnnotation extends Annotation
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var bool
     */
    public $public = true;
    /**
     * @var bool
     */
    public $admin = true;

}