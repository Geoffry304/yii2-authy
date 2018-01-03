<?php

namespace geoffry304\authy\components;
use yii\base\Component;
use \Authy\AuthyApi;

class Authy extends Component {
    
    public $authyInstance;
    
     public $api_key;
    
    public $api_url;
    
    public $default_countrycode = "32";
    
    //expiry time in days
    public $default_expirytime = "30";
    
    
    public function init()
    {
        if (!isset($this->authyInstance)){
            $this->authyInstance =  new AuthyApi($this->api_key, $this->api_url); 
        }
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
}
