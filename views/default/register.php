<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \models\Authy */
$this->title = 'Register token';
?>
  <div class="login-authy-box">
    <div class="login-authy-logo">
        <img class="login-authy-logo-img" src="<?= Yii::$app->getModule("authy")->logo?>">
    </div>
    <div class="login-authy-box-body">
      <p class="login-authy-box-msg"><?= Yii::t('authy', 'Register to get a token') ?></p>

      <?php $form = ActiveForm::begin(['id' => 'register-form','enableClientValidation' => false]);?>
      <?php //$form->field($model, 'countrycode')->dropDownList([],['id' => 'authy-countries', 'value' => Yii::$app->authy->default_countrycode]); ?>
      <?php //$form->field($model, 'cellphone')->textInput(['id' => 'authy-cellphone']) ?>
      <?= $form->field($model, 'cellphone')->widget(PhoneInput::className(), [
        'jsOptions' => [
        'preferredCountries' => ['be']
    ]
]);?>

      <div class="row">
            <div class="col-xs-8">
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton(Yii::t('authy', 'Submit'), ['class' => 'btn btn-success btn-block btn-flat']) ?>
            </div>
            <!-- /.col -->
        </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>

<?php
$this->registerJs("$('#authy-cellphone').focus();")
?>
