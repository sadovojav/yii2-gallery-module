<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use sadovojav\gallery\Module;

$this->title = Html::encode($model->name);
$this->params['breadcrumbs'] = [
    ['label' => Module::t('default', 'GALLERIES'), 'url' => ['index']],
    $this->title
];

?>

<div class="gallery-view">
    <div class="page-header">
        <div class="row">
            <div class="col-md-9">
                <h1><?= Html::encode($model->name); ?></h1>
            </div>

            <div class="col-md-3">
                <div class="pull-right">
                    <?= Html::a(Module::t('default', 'CREATE'), ['create'], [
                        'class' => 'btn btn-success btn-sm'
                    ]); ?>
                    <?= Html::a(Module::t('default', 'UPDATE'), ['update', 'id' => $model->id], [
                        'class' => 'btn btn-primary btn-sm'
                    ]); ?>
                    <?= Html::a(Module::t('default', 'DELETE'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => Module::t('default', 'DELETE_ITEM'),
                            'method' => 'post',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'status:boolean',
            'created',
            'updated',
        ],
    ]); ?>
</div>
