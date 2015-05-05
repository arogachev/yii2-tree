<?php

namespace arogachev\tree\widgets;

use arogachev\tree\assets\TreeAsset;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;
use yii\helpers\Url;

class NestedSets extends BaseWidget
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var array
     */
    public $jsTreeOptions = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->jsTreeOptions = array_merge($this->jsTreeOptions, [
            'clientOptions' => [
                'core' => [
                    'data' => [
                        'url' => Url::to(['/tree/get-tree', 'modelClass' => $this->modelClass]),
                    ],
                    'check_callback' => true,
                ],
                'plugins' => ['contextmenu', 'dnd'],
            ],
            'clientEvents' => [
                'open_node' => 'yii.tree.openNode',
                'close_node' => 'yii.tree.closeNode',
                'create_node' => 'yii.tree.createNode',
                'move_node' => 'yii.tree.moveNode',
                'rename_node' => 'yii.tree.renameNode',
                'delete_node' => 'yii.tree.deleteNode',
            ],
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        TreeAsset::register($this->view);
        $modelClass = addslashes($this->modelClass);
        $this->getView()->registerJs("yii.tree.modelClass = '$modelClass';");
        echo Html::tag('div', JsTree::widget($this->jsTreeOptions), $this->options);
    }
}
