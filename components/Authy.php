<?php

namespace geoffry304\authy\components;
use yii\base\Component;
use \Authy\AuthyApi;
use \geoffry304\authy\models\Authy as AuthyModel;
use \geoffry304\authy\models\AuthyLogin;

class Authy extends Component {

    public $authyInstance;

     public $api_key;

    public $api_url;

    public $default_countrycode = "32";

    public $userid;
    
    public $status = 1;

    //expiry time in seconds 30 * 60 * 60 * 24 = 30 days
    public $default_expirytime = 2592000;

    const PARAM_REGISTER = 1;
    const PARAM_LOGIN = 2;
    const PARAM_REDIRECT = 3;


    public function init()
    {
        if (!isset($this->authyInstance)){
            $this->authyInstance =  new AuthyApi($this->api_key, $this->api_url);
        }


        $this->userid = \Yii::$app->user->id;
    }

     /**
     * Use magic PHP function __call to route ALL function calls to the Adldap class.
     * Look into the Adldap class for possible functions.
     *
     * @param string $methodName Method name from AuthyApi class
     * @param array $methodParams Parameters pass to method
     * @return mixed
     */
    public function __call($methodName, $methodParams)
    {
        return call_user_func_array([$this->authyInstance, $methodName], $methodParams);
    }


    public function check2FA(){
        $authy = $this->isRegistered();
        if ($authy){
          $authylogin = $this->isActive($authy);
          if ($authylogin){
            return \yii\helpers\Url::to(["/".\Yii::$app->defaultRoute]);;
          } else {
            return \yii\helpers\Url::to(['/authy/default/login']);
          }
        } else {
          return \yii\helpers\Url::to(['/authy/default/register']);
        }

    }

    public function isRegistered(){
      $authy = AuthyModel::find()->where(['userid' => \Yii::$app->user->id])->one();
      if ($authy){
        return $authy;
      } else {
        return false;
      }
    }

    public function isActive($authy){
      $authylogin = AuthyLogin::find()->where(['authyid' => $authy->id, 'ip' => \Yii::$app->request->userIP])->one();
      if ($authylogin){
        if ($authylogin->expire_at < date('Y-m-d H:i:s', time())){
          return false;
        }
        return $authylogin;
      } else {
        return false;
      }
    }

    public function checkAction(){
      $authy = $this->isRegistered();
      if ($authy){
        $authylogin = $this->isActive($authy);
        if ($authylogin){
          return self::PARAM_REDIRECT;
        } else {
          return self::PARAM_LOGIN;
        }
      } else {
        return self::PARAM_REGISTER;
      }
    }

}
