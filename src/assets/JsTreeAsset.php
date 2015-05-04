<?php

namespace arogachev\tree\assets;

use yii\web\AssetBundle;

class JsTreeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jstree/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'jstree.min.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'themes/default/style.min.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
