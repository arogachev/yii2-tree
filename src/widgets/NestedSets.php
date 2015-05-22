<?php

namespace arogachev\tree\widgets;

use arogachev\tree\assets\TreeAsset;
use Yii;
use yii\base\Widget as BaseWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\i18n\PhpMessageSource;

class NestedSets extends BaseWidget
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var string
     */
    public $updateUrl;

    /**
     * @var array
     */
    public $options = [];

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

        Yii::setAlias('@tree', dirname(__DIR__));
        Yii::$app->i18n->translations['tree'] = [
            'class' => PhpMessageSource::className(),
            'basePath' => '@tree/messages',
        ];

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $clientEvents = [
            'create_node' => 'yii.tree.createNode',
            'move_node' => 'yii.tree.moveNode',
            'rename_node' => 'yii.tree.renameNode',
            'delete_node' => 'yii.tree.deleteNode',
        ];

        $model = new $this->modelClass;
        if ($model->saveState) {
            $clientEvents = array_merge($clientEvents, [
                'open_node' => 'yii.tree.openNode',
                'close_node' => 'yii.tree.closeNode',
            ]);
        }

        $items = [
            'create' => ['label' => Yii::t('tree', 'Create')],
            'rename' => ['label' => Yii::t('tree', 'Rename')],
            'remove' => ['label' => Yii::t('tree', 'Remove')],
        ];
        if ($this->updateUrl) {
            $items['update'] = ['label' => Yii::t('tree', 'Update')];
        }

        $this->jsTreeOptions = ArrayHelper::merge([
            'clientOptions' => [
                'core' => [
                    'data' => [
                        'url' => Url::to(['/tree/get-tree', 'modelClass' => $this->modelClass]),
                    ],
                    'check_callback' => true,
                    'strings' => [
                        'New node' => Yii::t('tree', 'New node'),
                    ],
                ],
                'plugins' => ['contextmenu', 'dnd'],
                'contextmenu' => [
                    'items' => $items,
                ],
            ],
            'clientEvents' => $clientEvents,
        ], $this->jsTreeOptions);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        TreeAsset::register($this->view);

        /* @var $model \yii\db\ActiveRecord */
        $model = new $this->modelClass;
        $properties = Json::encode([
            'modelClass' => addslashes($this->modelClass),
            'modelPk' => $model->primaryKey()[0],
            'updateUrl' => Url::to($this->updateUrl),
        ]);
        $this->getView()->registerJs("yii.tree.initProperties($properties);");

        echo Html::tag('div', JsTree::widget($this->jsTreeOptions), $this->options);
    }
}
