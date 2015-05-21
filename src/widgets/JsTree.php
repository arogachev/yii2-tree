<?php

namespace arogachev\tree\widgets;

use arogachev\tree\assets\JsTreeAsset;
use yii\base\Widget as BaseWidget;
use yii\helpers\Html;
use yii\helpers\Json;

class JsTree extends BaseWidget
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    public $clientEvents = [];


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    protected function overrideDefaults()
    {
        if (!isset($this->clientOptions['contextmenu']['items'])) {
            return;
        }

        $userOptions = Json::encode($this->clientOptions['contextmenu']['items']);
        $js = "yii.tree.overrideDefaults($userOptions);";
        $this->getView()->registerJs($js);
    }

    /**
     * @param string $id
     */
    protected function registerClientOptions($id)
    {
        if ($this->clientOptions !== false) {
            $options = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
            $js = "jQuery('#$id').jstree($options);";
            $this->getView()->registerJs($js);
        }
    }

    /**
     * @param string $id
     */
    protected function registerClientEvents($id)
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $eventName = "$event.jstree";
                $js[] = "jQuery('#$id').on('$eventName', $handler);";
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }

    /**
     * @param string $id
     */
    protected function registerWidget($id = null)
    {
        if ($id === null) {
            $id = $this->options['id'];
        }
        JsTreeAsset::register($this->getView());
        $this->overrideDefaults();
        $this->registerClientOptions($id);
        $this->registerClientEvents($id);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::tag('div', '', $this->options);
        $this->registerWidget();
    }
}
