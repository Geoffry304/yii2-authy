# yii2-authy

[![Latest Version](https://img.shields.io/github/tag/geoffry304/yii2-authy.svg?style=flat-square&label=release)](https://github.com/geoffry304/yii2-authy/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://github.com/geoffry304/yii2-authy/blob/master/LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/geoffry304/yii2-authy.svg?style=flat-square)](https://packagist.org/packages/geoffry304/yii2-authy)

#### Extension  for using 2FA from Authy with Yii and amnah/yii2-user ####

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
    'modules' => [
                'authy' => [
            'class' => 'geoffry304\authy\Module',
            'api_key' => 'here your api key from authy',
            'send_mail_from => 'demo@example.com'
        ],
'user' => [
            'class' => 'amnah\yii2\user\Module',
            'modelClasses' => [
                'LoginForm' => 'geoffry304\authy\forms\LoginForm'
            ]
        ],
    ],
];
```

## Using Authy ##

You need to add this piece of code before you try performLogin

```php
$module2FA = Yii::$app->getModule('authy');
        if ($module2FA){
                    Yii::$app->session->set('credentials', ['login' => $model->email, 'pwd' => $model->password]);
                    $returnUrl = $module2FA->validateLogin($model->getUser());
                    return $returnUrl;  
        }
 ``` 
  #### options ####
  
  **Module** Has the following options to modify it's behaviour:

  - **api_key**: The key you get from authy website to make connection with it.
  - **api_url**: If you want to use an other url standard to https://api.authy.com.
  - **default_expirytime**: The expire time the user will need to insert a new token standard to 30 days.
  - **send_mail**: Send mail when new device is added, standard to true.
  - **send_mail_from**: Send mail from required when send_mail is on.
  - **logo**: Path tho logo used in confirmation and registration form and also in sending mail.

If you need extra security, you can check on every action and controller if the current session still exist in db.
Update the config file
```php
// app/config/web.php
return [
    'bootstrap' => ['GlobalCheck'],
    'components' => [
        'GlobalCheck'=>[
        'class'=>'geoffry304\authy\components\GlobalCheck'
     ],
];
```
