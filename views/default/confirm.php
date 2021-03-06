
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Settingsgeneral;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In Token';


?>

<div class="login-authy-box">
    <div class="login-authy-logo">
        <!--<img class="login-authy-logo-img" src="<?php //Yii::$app->assetManager->getPublishedUrl('@vendor/geoffry304/yii2-authy/assets')?>/css/images/authy.png">-->
        <img class="login-authy-logo-img" src="<?= Yii::$app->getModule("authy")->logo?>">
    </div>
    <!-- /.login-logo -->
    <div class="login-authy-box-body">
      <h3 class="login-authy-box-msg"><?= Yii::t('authy', '2-Step Verification') ?></h3>
        <p class="login-authy-box-msg"><?= Yii::t('authy', 'Enter the verification code generated by your phone.') ?></p>

        <?php $form = ActiveForm::begin(
                ['id' => $model->formName(),
                 'enableAjaxValidation' => false,
                 'enableClientValidation' => false,
                 'validateOnBlur' => false,
                 'validateOnType' => false,
                 'validateOnChange' => false
                ]); ?>
        <!--<form>-->

            <?= $form->field($model, 'twoFactorAuthenticationCode',['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control', 'tabindex' => '1',
                 'autocomplete' => 'off']]) ?>
          <div class="form-group">
            <?php // Html::label(Yii::t('authy', 'Token'), "authy-token", ['class' => 'control-label']) ?>
            <?php // Html::textInput("authy-token", '', ['id' => 'authy-token', 'class' => 'form-control']) ?>
            </div>
            <div class="form-group">
            <label>
            <?= $form->field($model, 'rememberComputer')->checkbox(['tabindex' => '4'])?>
            <?php //Html::checkbox("remember_computer",false,['id' => "remember_computer"]); ?>
            <?php  //Yii::t('authy', 'Don\'t ask me for the code again for 30 days when I use this computer.'); ?>
          </label>
        </div>



            <!--<a href="#" id="authy-help">help</a>-->


        <div class="row">
          <div class="col-xs-4">
            <?= Html::a(Yii::t('authy', 'Send sms'), ['sendsms'], ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'send-sms', 'readonly' => true])?>
          </div>
            <div class="col-xs-4">
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('authy', 'Validate'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
            </div>
            <!-- /.col -->
        </div>

            <?php ActiveForm::end(); ?>
<!--        </form>-->
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php
$this->registerJs("$('#authy-token').focus();")
?>
