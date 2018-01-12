<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;
use geoffry304\authy\models\Authy;
use yii\helpers\Url;

$this->title = Yii::t('authy', 'Authentications');
$this->params['breadcrumbs'][] = $this->title;
?> 
<div class="authy-login-index">
    <?php
    $gridColumn = [
        ['class' => 'yii\grid\SerialColumn'],
        ['attribute' => 'id', 'visible' => false],
        'ip',
        'expire_at:datetime',
        'hostname',
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'urlCreator' => function($action, $model, $key, $index) { 
        return Url::to(["/authy/default/".$action,'id'=>$key]);
        },
        ],
    ];
    ?> 
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $gridColumn,
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-authy-login']],
        'panel' => [
            'template' => '{delete}',
            'type' => GridView::TYPE_PRIMARY,
            'heading' => '<span class="glyphicon glyphicon-book"></span>  ' . Html::encode($this->title),
        ],
    ]);
    ?> 

</div> 