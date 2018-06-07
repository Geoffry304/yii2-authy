<?php

namespace geoffry304\authy;

use Yii;
use \yii\web\IdentityInterface;
use geoffry304\authy\models\Authy;
use geoffry304\authy\models\AuthyLogin;
use yii\helpers\Url;
use yii\base\InvalidConfigException;

/**
 * Class Module
 * @package geoffry304\authy
 */
class Module extends yii\base\Module {

    protected $_authy;
    
    protected $_identity;
    
    public $controllerNamespace = "geoffry304\authy\controllers";
    
    public $forceTranslation = false;
    
    public $send_mail = true;
    
    public $send_mail_from;
    
    public $api_key;
    
    public $api_url = 'https://api.authy.com';
    
    public $default_countrycode = "32";
    
    //expiry time in seconds 30 * 60 * 60 * 24 = 30 days
    public $default_expirytime = 2592000;
    
    public $default_loginDuration = 604800; //1week
    
    public $alias = "@authy";
    
    public $emailViewPath = "@authy/mail";
    
    public $logo;

    public function init() {

        parent::init();

        $className = get_called_class();
//         check required fields
        if ($this->send_mail && !$this->send_mail_from) {
            throw new InvalidConfigException("{$className}: \$send_mail_from must be set when  \$send_mail is true");
        }
        

        // set up i8n
        if (empty(Yii::$app->i18n->translations['authy'])) {
            Yii::$app->i18n->translations['authy'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                'forceTranslation' => $this->forceTranslation,
            ];
        }
        
        if ($this->logo){
                $this->logo = Url::to($this->logo, true);
        } else {
            $this->logo = Yii::$app->assetManager->getPublishedUrl('@vendor/geoffry304/yii2-authy/assets')."/css/images/authy.png";
        }
        // set alias
        $this->setAliases([
            $this->alias => __DIR__,
        ]);
    }

    public function setAuthy($value){
        $this->_authy = $value;
    }

    public function getAuthy(){
        return $this->_authy;
    }
    public function setIdentity($value){
        $this->_identity = $value;
    }

    public function getIdentity(){
        return $this->_identity;
    }

    public function validateLogin(IdentityInterface $identity, $rememberMe){
        $authy = Authy::find()->where(['userid' => $identity->getId()])->one();
        $this->setAuthy($authy);
        $this->setIdentity($identity);
        if ($authy){
            return $this->isActive($rememberMe);
        } else {
            return Url::to(["/authy/default/register"]);
        }
    }
    
    public function isActive($rememberMe){
        if (AuthyLogin::checkCookie() !== null) {
           $cookie_array = explode(";", AuthyLogin::checkCookie());
           if ($cookie_array[0] == $this->getAuthy()->authyid) {
               $cookie_value = AuthyLogin::findByCookie($this->getAuthy()->id, $cookie_array);
                if ($cookie_value){
                    Yii::$app->session->set('credentials', null);
                    $loginDuration = $rememberMe ? $this->default_loginDuration : 0;
                    Yii::$app->user->login($this->getIdentity(),$loginDuration);
                    return Yii::$app->homeUrl;
                }
            }
               return Url::to(["/authy/default/confirm"]);
        }
        return Url::to(["/authy/default/confirm"]);
    }
}
