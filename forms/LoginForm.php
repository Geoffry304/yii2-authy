<?php

namespace geoffry304\authy\forms;

use \yii\base\Model;
use \amnah\yii2\user\models\forms\LoginForm as BaseLoginForm;
use Yii;
use geoffry304\authy\helpers\SecurityHelper;
use geoffry304\authy\validators\TwoFactorCodeValidator;
use geoffry304\authy\validators\TwoFactorRegisterValidator;

class LoginForm extends BaseLoginForm {


    public $twoFactorAuthenticationCode;
    
    public $rememberComputer = false;
    
    public $cellphone;
    
    protected $authy;
    
    public $verifyCode;

    public $moduleAuthy;

    public function init() {
        parent::init();
        $this->rememberMe = false;
        if (!$this->moduleAuthy) {
            $this->moduleAuthy = Yii::$app->getModule("authy");
        }
    }

    public function attributeLabels() {
        return [
            'email' => Yii::t('authy', 'Login'),
            'password' => Yii::t('authy', 'Password'),
            'rememberMe' => Yii::t('authy', 'Remember Me'),
            'cellphone' => Yii::t('authy', 'Cellphone'),
            'rememberComputer' => Yii::t('authy', 'Don\'t ask me for the code again for 30 days when I use this computer.'),
            'twoFactorAuthenticationCode' => Yii::t('authy', 'Token'),
        ];
    }

    public function rules() {

        return array_merge(parent::rules(), [
            'requiredFields' => [['email', 'password'], 'required'],
            'requiredFieldsTwoFactor' => [['email', 'password', 'twoFactorAuthenticationCode'], 'required', 'on' => '2fa'],
            'requiredFieldsTwoFactorRegister' => [['email', 'password', 'cellphone'], 'required', 'on' => '2faregister'],
            'loginTrim' => ['email', 'trim'],
            'twoFactorAuthenticationCodeTrim' => ['twoFactorAuthenticationCode', 'trim'],
            'twoFactorAuthenticationCodeValidate' => [
                'twoFactorAuthenticationCode',
                function ($attribute) {
                    if ($this->user === null ||
                            !(new TwoFactorCodeValidator($this->authy, $this->twoFactorAuthenticationCode, $this->rememberComputer, $this->moduleAuthy))->validate()) {
                        $this->addError($attribute, Yii::t('authy', 'Invalid two factor authentication code'));
                    }
                }
            ],
//                    'twoFactorAuthenticationCellphoneValidate' => [
//                'cellphone',
//                function ($attribute) {
//                    if ($this->user === null ||
//                            !(new TwoFactorRegisterValidator($this, $this->twoFactorAuthenticationCode, $this->rememberComputer, $this->moduleAuthy))->validate()) {
//                        $this->addError($attribute, Yii::t('authy', 'Invalid two factor authentication code'));
//                    }
//                }
//            ],
            'rememberMe' => ['rememberComputer', 'boolean'],
            ['verifyCode', 'captcha', 'on' => 'withCaptcha'],
        ]);
    }
    
    /**
     * Validate user
     */
    public function validateUser() {
        $user = $this->getUser();
        if (!$user || !$user->password) {

            if (!$user) {
                if ($this->module->loginEmail && $this->module->loginUsername) {
                    $attribute = "Email / Username";
                } else {
                    $attribute = $this->module->loginEmail ? "Email" : "Username";
                }
                $this->addError("email", Yii::t("user", "$attribute not found"));
            }

            // do we need to check $user->userAuths ???
        }

        // check if user is banned
        if ($user && $user->banned_at) {
            $this->addError("email", Yii::t("user", "User is banned - {banReason}", [
                        "banReason" => $user->banned_reason,
            ]));
        }

        // check status and resend email if inactive
        if ($user && $user->status == $user::STATUS_INACTIVE) {
            /** @var \amnah\yii2\user\models\UserToken $userToken */
            $userToken = $this->module->model("UserToken");
            $userToken = $userToken::generate($user->id, $userToken::TYPE_EMAIL_ACTIVATE);
            //$user->sendEmailConfirmation($userToken);
            //$this->addError("email", Yii::t("user", "Confirmation email resent"));
        }
    }
    
        /**
     * Validate password
     */
    public function validatePassword() {
        // skip if there are already errors
        if ($this->hasErrors()) {
            return;
        }
        
        $components = \Yii::$app->getComponents();
        /** @var \amnah\yii2\user\models\User $user */
        // check if password is correct
        $user = $this->getUser();
        if (!$user->password) {
            if (isset($components['ad'])) {
                if (!\Yii::$app->ad->auth()->attempt($this->email, $this->password)) {
                    $this->addError("password", Yii::t("user", "Incorrect passwords"));
                }
            }
        } else if (!$user->validatePassword($this->password)) {
            $this->addError("password", Yii::t("user", "Incorrect passwords"));
        }
    }
    
    public function login() {
        if ($this->validate()) {
            \geoffry304\authy\models\AuthyLogin::addNewRecord($this->authy,$this->moduleAuthy,$this->rememberComputer);
            $duration = $this->rememberMe ? $this->moduleAuthy->default_loginDuration: 0;
//            $duration = 604800;
            return Yii::$app->user->login($this->user, $duration);
        }

        return false;
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
//            $this->user = $this->whereUsernameOrEmail(trim($this->login));
            //$this->user = $this->whereUsernameOrEmail(trim($this->get));
            if ($this->getUser()){
             $this->authy = \geoffry304\authy\models\Authy::find()->where(['userid' => $this->getUser()->id])->one();   
            }
            return true;
        }
        return false;
    }

    public function whereUsernameOrEmail($usernameOrEmail) {
        $user = \app\models\User::find();
        $user->orWhere(["email" => $usernameOrEmail]);
        $user->orWhere(["username" => $usernameOrEmail]);
        return $user->one();
    }

//        public function getUser(){
//        return $this->user;
//    }
    public function getAuthy() {
        return $this->authy;
    }
    
        public function createNewUser() {
        /** @var \amnah\models\User $user */
        /** @var \amnah\yii2\user\models\Profile $profile */
        /** @var \amnah\yii2\user\models\Role $role */
        $role = $this->module->model("Role");
        $user = $this->module->model("User");
        $profile = $this->module->model("Profile");

        $result = \Yii::$app->ad->getDefaultProvider()->search()->select(["name", "mail", "samaccountname", "givenname", "sn"])->where('samaccountname', '=', $this->email)->get();
        if (!empty($result[0])) {
            if (isset($result[0]['attributes']['mail'])) {
                $user->email = $result[0]['attributes']['mail'][0];
                $user->username = $this->email;
                $sn = (isset($result[0]['attributes']['sn'])) ? $result[0]['attributes']['sn'][0] : "";
                $profile->full_name = $result[0]['attributes']['givenname'][0] . " " . $sn;
                // save new models
                $user->setScenario("social");
                $user->setRegisterAttributes($role::ROLE_USER, $user::STATUS_ACTIVE)->save();
                $profile->setUser($user->id)->save();
                $this->user = $user;
                $assignment = new \mdm\admin\models\Assignment($user->getPrimaryKey());
                $assignment->assign(["System engineer"]);
                return true;
            }
//                return false;
        }
        return false;
//        else {
//            return false;
//        }
    }

}
