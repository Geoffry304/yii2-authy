<?php

namespace geoffry304\authy\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use geoffry304\authy\models\AuthyLogin;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * Default controller for User module
 */
class DefaultController extends Controller {

//    /**
//     * @var \geoffry304\authy\Module
//     * @inheritdoc
//     */
    public $module;

        public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['register', 'confirm', 'sendsms'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['index', 'authentications','delete','removeotherdevices'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }
    /**
     * Display login page
     */
    public function actionConfirm() {

        if (!Yii::$app->user->getIsGuest()) {
            return $this->goHome();
        }

        if (!Yii::$app->session->has('credentials')) {
            return $this->redirect(['/user/login']);
        }

        $this->layout = 'main';

        $credentials = Yii::$app->session->get('credentials');
        $form = new \geoffry304\authy\forms\LoginForm();
        $form->email = $credentials['login'];
        $form->password = $credentials['pwd'];
        $form->setScenario('2fa');

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return \yii\widgets\ActiveForm::validate($form);
        }
        if ($form->load(Yii::$app->request->post())) {
            if ($form->login()) {
                Yii::$app->session->set('credentials', null);
                return $this->redirect(["/" . Yii::$app->defaultRoute]);
            }
        }
        return $this->render('confirm', ['model' => $form]);
    }

    /**
     * Lists all AuthyLogin models for the logged in user.
     * @return mixed
     */
    public function actionIndex() {
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
    public function actionRegister() {

        if (!Yii::$app->user->getIsGuest()) {
            return $this->goHome();
        }

        if (!Yii::$app->session->has('credentials')) {
            return $this->redirect(['/user/login']);
        }

        $this->layout = 'main';

        $credentials = Yii::$app->session->get('credentials');
        $form = new \geoffry304\authy\forms\LoginForm();
        $form->email = $credentials['login'];
        $form->password = $credentials['pwd'];
        $form->setScenario('2faregister');

        if (Yii::$app->request->isAjax && $form->load(Yii::$app->request->post())) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return \yii\widgets\ActiveForm::validate($form);
        }
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
               if (\geoffry304\authy\models\Authy::addNewRecord($form)){
                   $this->actionSendsms();
                   $this->redirect('confirm');
               } 
            } 
        }
        return $this->render('register', [
                    'model' => $form,
        ]);
//        }
    }

    public function actionSendsms() {
        $credentials = Yii::$app->session->get('credentials');
        $form = new \geoffry304\authy\forms\LoginForm();

        $authy = \geoffry304\authy\models\Authy::find()->where(['userid' => $form->whereUsernameOrEmail($credentials['login'])->id])->one();
        $manager = new \Authy\AuthyApi($this->module->api_key, $this->module->api_url);
        $manager->requestSms($authy->authyid, ['force' => true]);
        return $this->redirect('confirm');
    }

    /**
     * Lists all AuthyLogin models for the logged in user.
     * @return mixed
     */
    public function actionAuthentications() {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthyLogin::find()->joinWith('authy')->where(['authy.userid' => Yii::$app->user->id]),
        ]);

        return $this->render('authentications', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing AuthyLogin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
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
    protected function findModel($id) {
        if (($model = AuthyLogin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('authy', 'The requested page does not exist.'));
        }
    }

    public function actionRemoveotherdevices() {
        $logins = AuthyLogin::find()->joinWith('authy')->where(['authy.userid' => Yii::$app->user->id])->all();

        foreach ($logins as $login) {
            if ($login->checkIfCurrent() == null) {
                $this->findModel($login->id)->delete();
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
