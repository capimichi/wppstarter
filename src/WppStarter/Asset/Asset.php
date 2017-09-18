<?php

namespace WppStarter\Asset;

abstract class Asset
{
    /**
     * @var string
     */
    protected $assetsUrl;

    /**
     * @var array
     */
    protected $assets;

    /**
     * Asset constructor.
     * @param string $assetsUrl
     */
    public function __construct($assetsUrl)
    {
        $this->assetsUrl = $assetsUrl;
        $this->assets = [];
    }

    /**
     * @param $name
     * @param $asset
     * @param bool $admin
     *
     * @return Asset
     */
    public function add($name, $asset, $admin = false)
    {
        $this->assets[$name] = $asset;
        $action = $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';
        add_action($action, [
            $this,
            'enqueue',
        ]);
        return $this;
    }

    /**
     *
     */
    public abstract function enqueue();

}