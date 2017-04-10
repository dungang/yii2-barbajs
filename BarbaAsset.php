<?php
/**
 * Author: dungang
 * Date: 2017/4/9
 * Time: 14:52
 */

namespace dungang\barbajs;


use yii\web\AssetBundle;

class BarbaAsset extends AssetBundle
{

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        if (YII_DEBUG) {
            $this->js = ['barba.js'];
        } else {
            $this->js = ['barba.min.js'];
        }
    }
}