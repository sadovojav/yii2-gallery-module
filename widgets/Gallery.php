<?php

namespace sadovojav\gallery\widgets;

use Yii;
use yii\caching\DbDependency;
use yii\helpers\Html;
use sadovojav\gallery\models\Gallery as BaseGallery;

/**
 * Class Gallery
 * @package sadovojav\gallery\widgets
 */
class Gallery extends \yii\base\Widget
{
    /**
     * @var
     */
    public $galleryId;

    /**
     * @var bool
     */
    public $caption = false;

    /**
     * @var
     */
    public $template = null;

    public function run()
    {
        $dependency = new DbDependency();
        $dependency->sql = 'SELECT MAX(updated) FROM {{%gallery}}';

        $model = BaseGallery::getDb()->cache(function () {
            return BaseGallery::find()
                ->where('id = :id', [
                    ':id' => $this->galleryId
                ])
                ->active()
                ->one();
        }, Yii::$app->getModule('gallery')->queryCacheDuration, $dependency);

        if (is_null($model) || !count($model->files)) {
            return false;
        }

        if (!is_null($this->template)) {
            return $this->render($this->template, [
                'model' => $model,
                'models' => $model->files
            ]);
        } else {
            return $this->getDefaultGallery($model);
        }
    }

    /**
     * Get default gallery style image/caption
     * @param $model
     * @return string
     */
    private function getDefaultGallery($model)
    {
        $html = Html::beginTag('div', [
            'class' => 'content-gallery default gallery-' . $model->id,
        ]);

        foreach ($model->files as $value) {
            $html .= Html::beginTag('div');
            $html .= Html::img($value->src, [
                'alt' => $this->caption ? $value->caption : null,
                'class' => 'img-responsive'
            ]);

            if ($this->caption) {
                $html .= Html::tag('div', $value->caption, [
                    'class' => 'caption'
                ]);
            }

            $html .= Html::endTag('div');
        }

        $html .= Html::endTag('div');

        return $html;
    }
}