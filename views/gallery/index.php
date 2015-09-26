<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use sadovojav\gallery\Module;

$this->title = Module::t('default', 'GALLERY_MANAGER');
$this->params['breadcrumbs'] = [
    Module::t('default', 'GALLERIES')
];

?>

<div class="gallery-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'heading' => $this->title,
        ],
        'toolbar' => [
            ['content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'title' => Module::t('default', 'CREATE'), 'class'=>'btn btn-success'
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', [
                    'index'
                ], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Module::t('default', 'RESET')])
            ],
            '{toggleData}'
        ],
        'export' => false,
        'pjax' => true,
        'condensed' => true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'width' => '75px'
            ],
            'name',
            [
                'class' => 'kartik\grid\BooleanColumn',
                'attribute' => 'status',
                'vAlign' => 'middle',
            ],
//            'created',
//            'updated',
            [
                'class' => 'kartik\grid\ActionColumn',
                'vAlign' => 'middle',
            ],
        ],
    ]); ?>
</div>
