<?php

namespace arogachev\tree\helpers;

class NestedSetsHelper
{
    /**
     * @param string $modelClass
     * @return array
     */
    public static function getHierarchicalArray($modelClass)
    {
        /* @var $modelClass \yii\db\ActiveRecord */
        /* @var $baseModel \creocoder\nestedsets\NestedSetsBehavior */
        $baseModel = new $modelClass;
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
                'id' => $model->primaryKey()[0],
                'text' => $model->name,
            ];

            ArrayHelper::saveByPath($result, $path, $value);

            $depth = $depthValue;

            $c++;
        }

        return $result;
    }
}
