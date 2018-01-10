<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace geoffry304\authy;

/**
 * Description of AuthyAsset
 *
 * @author G.Vandeneede
 */
class AuthyAsset extends \yii\web\AssetBundle {
    // the alias to your assets folder in your file system
    public $sourcePath = '@vendor/geoffry304/yii2-authy/assets';
    // finally your files..
    public $css = [
        'css/custom.css',
    ];
    public $js = [
    ];
    // that are the dependecies, for makeing your Asset bundle work with Yii2 framework
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
