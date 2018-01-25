<?php
namespace geoffry304\authy\validators;
use \geoffry304\authy\interfaces\ValidatorInterface;


class TwoFactorCodeValidator implements ValidatorInterface {
    protected $authy;
    protected $code;
    protected $api_key;
    protected $api_url;
    protected $rememberMe;
     public   $module;
    
    
    public function __construct(\geoffry304\authy\models\Authy $authy, $code,$rememberMe,\yii\base\Module $module) {
        $this->authy = $authy;
        $this->code = $code;
        $this->module = $module;
        $this->api_key = $this->module->api_key;
        $this->api_url = $this->module->api_url;
        $this->rememberMe = $rememberMe;
    }
    
    public function validate(){
        $manager = new \Authy\AuthyApi($this->api_key, $this->api_url);
        
        $verification = $manager->verifyToken($this->authy->authyid, $this->code);
        if ($verification->ok()){
            return true;
        }
        return false;
    }
}
