<?php

namespace geoffry304\authy\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use geoffry304\authy\models\AuthyLogin;

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
         $model->hostname = gethostname();
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
     * Lists all AuthyLogin models for the logged in user. 
     * @return mixed 
     */ 
    public function actionAuthentications() 
    { 
        $dataProvider = new ActiveDataProvider([ 
            'query' => AuthyLogin::find()->joinWith('authy')->where(['authy.userid' => Yii::$app->user->id]), 
        ]); 

        return $this->render('authentications', [ 
            'dataProvider' => $dataProvider, 
        ]); 
    } 
    
    /** 
     * Lists all AuthyLogin models for the logged in user. 
     * @return mixed 
     */ 
    public function actionIndex() 
    { 
        $dataProvider = new ActiveDataProvider([ 
            'query' => AuthyLogin::find()->joinWith('authy')->where(['authy.userid' => Yii::$app->user->id]), 
        ]); 

        return $this->render('authentications', [ 
            'dataProvider' => $dataProvider, 
        ]); 
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
        if ($usermodel){
          return $this->goHome();
        } else {
          return $this->render('register',[
                              'model' => $model,
                  ]);
        }

    }

    public function actionSendsms(){
      $authy = \geoffry304\authy\models\Authy::find()->where(['userid' => Yii::$app->user->id])->one();
      Yii::$app->authy->requestSms($authy->authyid, ['force' => true]);
      return $this->redirect('login');
    }
    
    /** 
     * Deletes an existing AuthyLogin model. 
     * If deletion is successful, the browser will be redirected to the 'index' page. 
     * @param integer $id
     * @return mixed 
     */ 
    public function actionDelete($id) 
    { 
        $this->findModel($id)->delete(); 

//        return $this->redirect(['authentications']); 
        return $this->redirect(Yii::$app->request->referrer);
    } 
    /** 
     * Finds the AuthyLogin model based on its primary key value. 
     * If the model is not found, a 404 HTTP exception will be thrown. 
     * @param integer $id
     * @return AuthyLogin the loaded model 
     * @throws NotFoundHttpException if the model cannot be found 
     */ 
    protected function findModel($id) 
    { 
        if (($model = AuthyLogin::findOne($id)) !== null) { 
            return $model; 
        } else { 
            throw new NotFoundHttpException(Yii::t('authy', 'The requested page does not exist.')); 
        } 
    } 
}
