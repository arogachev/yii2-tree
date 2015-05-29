# Yii 2 Tree

Database tree structures management for Yii 2 framework

[![Latest Stable Version](https://poser.pugx.org/arogachev/yii2-tree/v/stable)](https://packagist.org/packages/arogachev/yii2-tree)
[![Total Downloads](https://poser.pugx.org/arogachev/yii2-tree/downloads)](https://packagist.org/packages/arogachev/yii2-tree)
[![Latest Unstable Version](https://poser.pugx.org/arogachev/yii2-tree/v/unstable)](https://packagist.org/packages/arogachev/yii2-tree)
[![License](https://poser.pugx.org/arogachev/yii2-tree/license)](https://packagist.org/packages/arogachev/yii2-tree)

Currently it's Nested Sets management extension based on:

- [Yii2 Nested Sets](https://github.com/creocoder/yii2-nested-sets)
- [jsTree](https://github.com/vakata/jstree)

Contents:

- [Installation](#installation)
- [Features](#features)
- [Usage](#usage)
- [Behavior configuration](#behavior-configuration)
- [Widget configuration](#widget-configuration)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist arogachev/yii2-tree
```

or add

```
"arogachev/yii2-tree": "*"
```

to the require section of your `composer.json` file.

## Features

- Basic actions with tree nodes: creating, renaming, moving, deleting
- Saving state of nodes (opened / closed)
- Links for updating node

## Usage

Add this to application config:

```php
'controllerMap' => [
    'tree' => 'arogachev\tree\controllers\TreeController',
],
```

Attach additional behavior along with `NestedSetsBehavior` to your model:

```php
use arogachev\tree\behaviors\NestedSetsManagementBehavior;
```

```php
/**
 * @inheritdoc
 */
public function behaviors()
{
    return [
        NestedSetsBehavior::className(),
        NestedSetsManagementBehavior::className(),
    ];
}
```

The last step is display widget:

```php
use arogachev\tree\widgets\NestedSets;
use frontend\modules\department\models\Department;
```

```php
<?= NestedSets::widget([
    'modelClass' => Department::className(),
]) ?>
```

## Behavior configuration

`nameAttribute` - string, the name of attribute storing the name of node. Defaults to `name`.

`saveState` - boolean, save state of nodes (opened / closed). Defaults to `false`.

`isOpenedAttribute` - string, the name of attribute storing if the node opened or closed.
Used together with `saveState`. Defaults to `is_opened`.

## Widget configuration

`modelClass` - string, the full model class including namespace of managed model. Required.

`updateUrl` - string, url for updating model in separate page.
Will be processed by `yii\helpers\Url::to()`.

`jsTreeOptions` - array, options for JsTree widget. Example:

```php
'jsTreeOptions' => [
    'clientOptions' => [
        'core' => [
            'strings' => [
                'New node' => 'Новый отдел',
            ],
        ],
    ],
],
```
