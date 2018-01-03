<?php
namespace geoffry304\authy;



use Yii;

/**
 * Class Module
 * @package geoffry304\authy
 */
class Module extends yii\base\Module {
    
    
    public $controllerNamespace = "geoffry304\authy\controllers";
    
    public $forceTranslation = false;
    
    public $api_key = "";
    
    public $api_url = "";
    public $default_countrycode = "32";
    
    //expiry time in days
    public $default_expirytime = "30";
       
    
    public function init(){
        parent::init();
        
        // set up i8n
        if (empty(Yii::$app->i18n->translations['authy'])) {
            Yii::$app->i18n->translations['authy'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                'forceTranslation' => $this->forceTranslation,
            ];
        }
        
    }
    
    protected function instance(){
        return new \Authy\AuthyApi($this->api_key, $this->$api_url);
    }
    
  
}
