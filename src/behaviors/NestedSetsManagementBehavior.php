<?php

namespace arogachev\tree\behaviors;

use arogachev\tree\helpers\ArrayHelper;
use yii\base\Behavior;

class NestedSetsManagementBehavior extends Behavior
{
    /**
     * @var string
     */
    public $isOpenedAttribute = 'is_opened';

    /**
     * @var boolean
     */
    public $saveState = false;


    /**
     * @return array
     */
    public function getHierarchicalArray()
    {
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $this->owner->className();
        /* @var $baseModel \creocoder\nestedsets\NestedSetsBehavior */
        $baseModel = new $this->owner;
        $models = $modelClass::find()
            ->orderBy($baseModel->leftAttribute)
            ->all();

        $depth = 0;
        $c = 0;
        $result = [];
        $path = [];

        foreach ($models as $model) {
            $depthValue = $model->{$baseModel->depthAttribute};

            if ($depthValue > $depth) {
                $c = 0;
            } elseif ($depthValue < $depth) {
                for ($i = $depth - $depthValue; $i; $i--) {
                    unset($path[count($path) - 1]);
                }

                $c = $path[count($path) - 1] + 1;
            }

            $path[$depthValue] = $c;

            $value = [
                'id' => $model->primaryKey,
                'text' => $model->name,
            ];

            if ($this->saveState) {
                $value['state'] = [
                    'opened' => $model->{$model->isOpenedAttribute},
                ];
            }

            ArrayHelper::saveByPath($result, $path, $value);

            $depth = $depthValue;

            $c++;
        }

        return $result;
    }
}
