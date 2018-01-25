<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace geoffry304\authy\validators;

use \geoffry304\authy\interfaces\ValidatorInterface;
use \geoffry304\authy\forms\LoginForm;
use geoffry304\authy\models\Authy;

class TwoFactorRegisterValidator implements ValidatorInterface {
protected $module;
protected $form;
protected $model;

    public function __construct(LoginForm $form, Authy $model) {

        $this->form = $form;
        $this->model = $model;
        $this->module = \Yii::$app->getModule("authy");

    }

    public function validate() {
        $manager = new \Authy\AuthyApi($this->module->api_key, $this->module->api_url);

        $user = $manager->registerUser($this->form->getUser()->email, $this->model->cellphone, $this->model->countrycode); //email, cellphone, country_code
        if ($user->ok()) {
            return $user->id();
        }
        return false;
    }

}
