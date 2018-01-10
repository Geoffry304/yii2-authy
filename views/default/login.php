
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Settingsgeneral;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign In Token';


?>

<div class="login-authy-box">
    <div class="login-authy-logo">
        <img class="login-authy-logo-img" src="<?=Yii::$app->assetManager->getPublishedUrl('@vendor/geoffry304/yii2-authy/assets')?>/css/images/authy.png">
    </div>
    <!-- /.login-logo -->
    <div class="login-authy-box-body">
        <p class="login-authy-box-msg"><?= Yii::t('app', 'Enter token to start session') ?></p>

        <?php //$form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <form>
          <div class="form-group">
            <?= Html::label(Yii::t('authy', 'Token'), "authy-token", ['class' => 'control-label']) ?>
            <?= Html::textInput("authy-token", '', ['id' => 'authy-token', 'class' => 'form-control']) ?>
          </div>


            <!--<a href="#" id="authy-help">help</a>-->


        <div class="row">
          <div class="col-xs-4">
            <?= Html::a(Yii::t('authy', 'Send sms'), ['/authy/default/sendsms'], ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'send-sms', 'readonly' => true])?>
          </div>
            <div class="col-xs-4">
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('authy', 'Validate'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
            </div>
            <!-- /.col -->
        </div>


        </form>
    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->
<?php
$this->registerJs("$('#authy-token').focus();")
?>
