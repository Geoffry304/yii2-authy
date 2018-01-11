# yii2-authy

[![Latest Version](https://img.shields.io/github/tag/geoffry304/yii2-authy.svg?style=flat-square&label=release)](https://github.com/geoffry304/yii2-authy/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://github.com/geoffry304/yii2-authy/blob/master/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/geoffry304/yii2-authy.svg?style=flat-square)](https://packagist.org/packages/geoffry304/yii2-authy)

#### Component for using 2FA from Authy with Yii ####

## Installation ##

The preferred way to install **Authy** is through [Composer](https://getcomposer.org/). Either add the following to the require section of your `composer.json` file:

`"geoffry304/yii2-authy": "*"` 

Or run:

`$ php composer.phar require geoffry304/yii2-authy "*"` 

You can manually install **Authy** by [downloading the source in ZIP-format](https://github.com/geoffry304/yii2-authy/archive/master.zip).

Run the migration file
  ```php yii migrate --migrationPath=@vendor/geoffry304/yii2-authy/migrations```

Update the config file
```php
// app/config/web.php
return [
    'components' => [
    'authy' => [
            'class' => 'geoffry304\authy\components\Authy',
            'api_key' => 'here your api key from authy',
        ],
    ],
    'modules' => [
                'authy' => [
            'class' => 'geoffry304\authy\Module',
        ],
    ],
];
```

## Using Authy ##

You need to create a controller where every other controller extends from.
Then you add next beforeAction


    public function beforeAction($action) {
        if (\Yii::$app->authy->status == 1) {
        switch (\Yii::$app->authy->checkAction()){
            case \geoffry304\authy\components\Authy::PARAM_REGISTER:
                $this->redirect(\yii\helpers\Url::to(['/authy/default/register']));
                parent::beforeAction($action);
                break;
            case \geoffry304\authy\components\Authy::PARAM_LOGIN:
                $this->redirect(\yii\helpers\Url::to(['/authy/default/login']));
                parent::beforeAction($action);
                break;
            case \geoffry304\authy\components\Authy::PARAM_REDIRECT:
                //$this->goHome();
                //$this->
                //parent::beforeAction($action);
                break;
        }  
            parent::beforeAction($action);
            return true;
        } else {
            parent::beforeAction($action);
            return true;
        }
        
    }
  
  #### options ####
  
  **Authycomponent** runs 'out of the box'. It has the following options to modify it's behaviour:

  - **api_key**: The key you get from authy website to make connection with it.
  - **api_url**: If you want to use an other url standard to https://api.authy.com.
  - **default_expirytime**: The expire time the user will need to insert a new token standard to 30 days.
  - **status**: Activate or Deactive the authy component default to 1.
