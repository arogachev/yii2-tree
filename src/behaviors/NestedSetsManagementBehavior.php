<?php

namespace arogachev\tree\behaviors;

use yii\base\Behavior;

class NestedSetsManagementBehavior extends Behavior
{
    /**
     * @var string
     */
    public $isOpenedAttribute = 'is_opened';
}
