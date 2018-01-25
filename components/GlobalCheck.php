<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace geoffry304\authy\components;

/**
 * Description of MyGlobalClass
 *
 * @author G.Vandeneede
 */
class GlobalCheck extends \yii\base\Component{
   public function init() {
       
       if (\Yii::$app->user->isGuest){
       } else {
           $current = \geoffry304\authy\models\AuthyLogin::currentValidate();
            if (!$current){
                \Yii::$app->user->logout();
                return \Yii::$app->response->redirect(\yii\helpers\Url::to('user/login'));
            }
       }
        parent::init();
    }
}
