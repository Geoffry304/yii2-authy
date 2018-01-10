<?php

namespace geoffry304\authy\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;

/**
 * Default controller for User module
 */
class DefaultController extends Controller
{
    /**
     * @var \geoffry304\authy\Module
     * @inheritdoc
     */
    public $module;

    /**
     * @inheritdoc
     */
     // public function behaviors() {
     //        return [
     //            'access' => [
     //                'class' => AccessControl::className(),
     //                'only' => ['logout'],
     //                'rules' => [
     //                    [
     //                        'actions' => ['logout'],
     //                        'allow' => true,
     //                        'roles' => ['@'],
     //                    ],
     //                ],
     //            ],
     //            'verbs' => [
     //                'class' => VerbFilter::className(),
     //                'actions' => [
     //                    'logout' => ['post'],
     //                ],
     //            ],
     //        ];
     //    }


    /**
     * Display login page
     */
    public function actionLogin()
    {
      $this->layout = 'main';
        $post = Yii::$app->request->post();
        if (isset($_REQUEST['authy-token'])){

          $authy = \geoffry304\authy\models\Authy::find()->where(['userid' => Yii::$app->user->id])->one();
          if ($authy){
            $verification = Yii::$app->authy->verifyToken($authy->authyid, $_REQUEST['authy-token']);
  if($verification->ok()){
       $model = \geoffry304\authy\models\AuthyLogin::find()->where(['ip' => Yii::$app->request->userIP, 'authyid' => $authy->getPrimaryKey()])->one();
       if ($model){
           $model->expire_at = date('Y-m-d  H:i:s', time() + Yii::$app->authy->default_expirytime);
           $model->save();
           return $this->goHome();
       } else {
         $model = new \geoffry304\authy\models\AuthyLogin();
         $model->authyid = $authy->getPrimaryKey();
         $model->expire_at = date('Y-m-d  H:i:s', time() + Yii::$app->authy->default_expirytime);
         $model->ip = Yii::$app->request->userIP;
         if ($model->save()){
           return $this->goHome();
         }
       }
  }
          } else {
            return $this->redirect('register');
          }
        }
//        if ($post) {
//            return "<pre>" . print_r($post) . "</pre>";
            //throw new Exception("<pre>" . print_r($post) . "</pre>");
//        }
        return $this->render('login');
    }

    /**
     * Display login page
     */
    public function actionRegister()
    {
      $this->layout = 'main';
        $model = new \geoffry304\authy\models\Authy();
        $model->userid = Yii::$app->user->id;
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
          $model->authyid = 1;
          if ($model->save()){
            if($model->saveCorrectPhone()){
                 $user = Yii::$app->authy->registerUser(Yii::$app->user->identity->email, $model->cellphone, $model->countrycode); //email, cellphone, country_code
                 if ($user->ok()){
                   $model->authyid = $user->id();
                   if ($model->validate()){
                    if ($model->save()){
                        $this->actionSendsms();
                       return $this->redirect('login');
                     }
                   }
                 } else {
                   echo "problem";
                 }
            }
          }

        }
        $usermodel = $authy = \geoffry304\authy\models\Authy::find()->where(['userid' => Yii::$app->user->id])->one();
        //if ($usermodel){
        //  return $this->goHome();
      //  } else {
          return $this->render('register',[
                              'model' => $model,
                  ]);
        //}

    }

    public function actionSendsms(){
      $authy = \geoffry304\authy\models\Authy::find()->where(['userid' => Yii::$app->user->id])->one();
      Yii::$app->authy->requestSms($authy->authyid, ['force' => true]);
      return $this->redirect('login');
    }
}
