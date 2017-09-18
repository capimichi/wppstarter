<?php

namespace WppStarter\Asset;


class DefaultAsset extends Asset
{
    /**
     * @param $name
     * @param $asset
     * @param bool $admin
     * @return DefaultAsset $this
     */
    public function add($name, $asset, $admin = false)
    {
        parent::add($name, $asset, $admin);
        return $this;
    }


    /**
     * Enqueue css and js
     */
    public function enqueue()
    {
        foreach ($this->assets as $name => $asset) {
            if (preg_match('/\.js$/', $asset)) {
                wp_enqueue_script($name, $this->assetsUrl . $asset, [], false, true);
            }
            if (preg_match('/\.css$/', $asset)) {
                wp_enqueue_style($name, $this->assetsUrl . $asset);
            }
        }
    }

}