<?php

namespace WppStarter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class FilterAnnotation extends Annotation
{
    /**
     * @var string|array
     */
    public $hook = "init";
    /**
     * @var int
     */
    public $priority = 10;
    /**
     * @var int
     */
    public $args = 1;

}