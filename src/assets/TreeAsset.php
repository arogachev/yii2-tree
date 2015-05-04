<?php

namespace arogachev\tree\assets;

use yii\web\AssetBundle;

class TreeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/arogachev/yii2-tree/src/assets/src';

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'tree.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'arogachev\tree\assets\JsTreeAsset',
    ];
}
