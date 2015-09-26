<?php

namespace sadovojav\gallery;

/**
 * Class AssetBundle
 * @package sadovojav\gallery
 */
class AssetBundle extends \yii\web\AssetBundle
{
    /**
     * @var array
     */
    public $css = [
        'css/style.css',
        'js/fancybox/source/jquery.fancybox.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'js/sortable/Sortable.min.js',
        'js/fancybox/source/jquery.fancybox.pack.js',
    ];

    /**
     * @var array
     */
    public $depends = array(
        'yii\web\JqueryAsset'
    );

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        parent::init();
    }
}