<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use sadovojav\gallery\Module;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use sadovojav\gallery\AssetBundle;

AssetBundle::register($this);

if (!$model->isNewRecord) {
    $this->registerJs('
        var el = document.getElementById("images");

        var sortable = Sortable.create(el, {
            dataIdAttr: "data-id",
            handle: ".handle",
            onEnd: function (evt) {
                calculatePositions();
            },
        });

        $(document).on("click", ".remove", function(e) {
            var parent = $(this).closest(".image");
            var id = parent.attr("data-id");

            $.ajax({
                type: "POST",
                url: "' . Yii::$app->urlManager->createUrl(['/gallery/gallery/remove']) . '",
                data: {"id": id},
                beforeSend: function () {
                    parent.addClass("preload");
                },
                success: function(response) {
                    parent.removeClass("preload");

                    if (response) {
                        calculatePositions();

                        parent.fadeOut(500, function() {
                            $(this).remove()
                        });
                    }
                }
            });
        });

        $(document).on("click", ".edit", function(e) {
            var parent = $(this).closest(".image");
            var id = parent.attr("data-id");
            var caption = parent.find("input[type=text]").val();

            $.ajax({
                type: "POST",
                url: "' . Yii::$app->urlManager->createUrl(['/gallery/gallery/caption']) . '",
                data: {"id": id, "caption": caption},
                beforeSend: function () {
                    parent.addClass("preload");
                },
                success: function(response) {
                    parent.removeClass("preload");
                    parent.find(".fancy").attr("title", caption);
                }
            });
        });

        $(".fancy").fancybox({
            padding: 0,
            helpers: {
                overlay: {
                    locked: false
                },
                title : {
                    type : "over"
                }
            }
        });

        function calculatePositions() {
            var order = sortable.toArray();
            var orderJoin = order.join("|");

            $("#positions").val(orderJoin);
        }
    ');
}

?>

<div class="gallery-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data'
        ]
    ]); ?>

    <div class="row">
        <div class="col-md-10">
            <?= $form->field($model, 'name')->textInput([
                'maxlength' => 50
            ]); ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'status')->widget(SwitchInput::className(), [
                'pluginOptions' => [
                    'size' => 'small',
                    'onColor' => 'success',
                    'offColor' => 'danger',
                ]
            ]); ?>
        </div>
    </div>

    <?= Html::hiddenInput('positions', null, [
        'name' => 'positions',
        'id' => 'positions',
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php if ($model->isNewRecord) :?>
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?= Module::t('default', 'ATTENTION_SAVE_MODEL'); ?>
                    </div>
                <?php else :?>
                    <?= FileInput::widget([
                            'name' => 'files[]',
                            'language' => Yii::$app->language,
                            'options'=>[
                                'multiple' => true,
                                'accept' => 'image/*'
                            ],
                            'pluginOptions' => [
                                'uploadAsync' => true,
                                'uploadUrl' => Yii::$app->urlManager->createUrl(['/gallery/gallery/upload']),
                                'uploadExtraData' => [
                                    'galleryId' => $model->id,
                                ],
                            ],
                            'pluginEvents' => [
                                'fileuploaded' => 'function (event, data, previewId, index) {
                                    $("#images").append(data.response.html);

                                    calculatePositions();
                                }'
                            ]
                        ]);
                    ?>

                    <div id="images" class="row">
                        <?php
                            if (count($model->files)) {
                                foreach ($model->files as $key => $value) {
                                    echo $this->render('_image', [
                                        'model' => $value
                                    ]);
                                }
                            }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <hr />

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('default', 'CREATE') : Module::t('default', 'UPDATE'), [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>