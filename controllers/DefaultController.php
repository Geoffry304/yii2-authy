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
//    public function behaviors()
//    {
//        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['index', 'confirm', 'resend', 'logout'],
//                        'allow' => true,
//                        'roles' => ['?', '@'],
//                    ],
//                    [
//                        'actions' => ['account', 'profile', 'resend-change', 'cancel'],
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                    [
//                        'actions' => ['login', 'register', 'forgot', 'reset', 'login-email', 'login-callback'],
//                        'allow' => true,
//                        'roles' => ['?'],
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
//        /** @var \amnah\yii2\user\models\forms\LoginForm $model */
//        $model = $this->module->model("LoginForm");
//
//        // load post data and login
//        $post = Yii::$app->request->post();
//        if ($model->load($post) && $model->validate()) {
//            $returnUrl = $this->performLogin($model->getUser(), $model->rememberMe);
//            return $this->redirect($returnUrl);
//        }

        return $this->render('login');
    }
}