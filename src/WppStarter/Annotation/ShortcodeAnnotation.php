<?php

namespace WppStarter\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class ShortcodeAnnotation extends Annotation
{
    /**
     * @var string
     */
    public $name;
}