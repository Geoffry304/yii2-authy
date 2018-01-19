<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

//use yii\helpers\Html;
//use kartik\grid\GridView;
//use geoffry304\authy\models\Authy;
//use yii\helpers\Url;

//$this->title = Yii::t('authy', 'Authentications');
//$this->params['breadcrumbs'][] = $this->title;

$authentications = $dataProvider->query->all();
?>
<div class="authy-login-index">
    <?php
//    $gridColumn = [
//        ['class' => 'yii\grid\SerialColumn'],
//        ['attribute' => 'id', 'visible' => false],
//        'ip',
//        'expire_at:datetime',
//        'hostname',
//        [
//            'class' => 'kartik\grid\ActionColumn',
//            'template' => '{delete}',
//            'urlCreator' => function($action, $model, $key, $index) {
//        return Url::to(["/authy/default/".$action,'id'=>$key]);
//        },
//        ],
//    ];
    ?>
    <?php
//    GridView::widget([
//        'dataProvider' => $dataProvider,
//        'columns' => $gridColumn,
//        'pjax' => true,
//        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-authy-login']],
//        'panel' => [
//            'template' => '{delete}',
//            'type' => GridView::TYPE_PRIMARY,
//            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
//        ],
//    ]);
    ?>

    <!--<div class="panel panel-default">-->
  <!-- Default panel contents -->
  <div class="panel-heading"></div>
  <!--<div class="panel-body">-->

  <!--</div>-->

  <!-- Table -->
  <h4 style="color:#337ab7;"><?= Yii::t('authy','Confirmed authentications')?></h4>
  <p><?=Yii::t('authy','These devices have currently access to your account.')?> <?= \yii\helpers\Html::a(Yii::t('authy', 'Remove all other devices'), \yii\helpers\Url::to(["/authy/default/removeotherdevices"]), ['data-confirm' => Yii::t('authy','Are you sure you want to delete these items?')])?></p>
  <table class="table">
   <thead>
      <tr>
        <th><?= Yii::t('authy','Expires')?></th>
        <th><?= Yii::t('authy','Browser')?></th>
        <th><?= Yii::t('authy','IP-adres')?></th>
        <th><?= Yii::t('authy','Hostname')?></th>
        <th><?= Yii::t('authy','Country')?></th>
        <th><?= Yii::t('authy','Current')?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach($authentications as $authentication):?>
        <tr>
            <td><?= Yii::$app->formatter->asDatetime($authentication->expire_at)?></td>
            <td><?= $authentication->getBrowserConcat()?></td>
            <td><?= $authentication->ip ?></td>
            <td><?= $authentication->hostname ?></td>
            <td><?= $authentication->ip_country ?></td>
            <td><?= $authentication->checkIfCurrent() ?></td>
            <td><?= $authentication->getDeleteUrl() ?></td>
        </tr>
        <?php endforeach;?>
    </tbody>
  </table>
<!--</div>-->

</div>
