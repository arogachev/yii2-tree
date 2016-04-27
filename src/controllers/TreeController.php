<?php

namespace arogachev\tree\controllers;

use arogachev\tree\behaviors\NestedSetsManagementBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\ContentNegotiator;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TreeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException('This page is not allowed for view.');
        }

        return true;
    }

    /**
     * @return array
     */
    public function actionGetTree()
    {
        return $this->createModel(Yii::$app->request->get())->getHierarchicalArray();
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionOpen()
    {
        $model = $this->getModel();
        $model->{$model->isOpenedAttribute} = true;
        $model->save(false);
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionClose()
    {
        $model = $this->getModel();
        $model->{$model->isOpenedAttribute} = false;
        $model->save(false);
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionAppendTo()
    {
        $parent = $this->getModel('parentPk');
        $model = $this->getModel(null, true);
        $model->appendTo($parent);

        return ['pk' => $model->primaryKey];
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionPrependTo()
    {
        $parent = $this->getModel('parentPk');
        $model = $this->getModel(null, true);
        $model->prependTo($parent);

        return ['pk' => $model->primaryKey];
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionRename()
    {
        $model = $this->getModel();
        $model->{$model->nameAttribute} = Yii::$app->request->post('name');
        $model->save(false);
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete()
    {
        $this->getModel()->deleteWithChildren();
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionInsertBefore()
    {
        $model = $this->getModel();
        $prevModel = $this->getModel('nextModelPk');
        $model->insertBefore($prevModel);
    }

    /**
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function actionInsertAfter()
    {
        $model = $this->getModel();
        $nextModel = $this->getModel('prevModelPk');
        $model->insertAfter($nextModel);
    }

    /**
     * @param array $data
     * @return \yii\db\ActiveRecord|NestedSetsBehavior|NestedSetsManagementBehavior
     * @throws BadRequestHttpException
     */
    protected function createModel($data)
    {
        $modelClass = ArrayHelper::getValue($data, 'modelClass');
        if (!$modelClass) {
            throw new BadRequestHttpException('Model class must be specified in order to find model.');
        }

        $model = new $modelClass;
        if (!($model instanceof ActiveRecord)) {
            throw new BadRequestHttpException('Valid ActiveRecord model class must be specified.');
        }

        return $model;
    }

    /**
     * @param null|string $paramName
     * @param boolean $createIfNotFound
     * @return \yii\db\ActiveRecord|NestedSetsBehavior|NestedSetsManagementBehavior
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    protected function getModel($paramName = null, $createIfNotFound = false)
    {
        $baseModel = $this->createModel(Yii::$app->request->post());
        /* @var $modelClass \yii\db\ActiveRecord */
        $modelClass = $baseModel->className();

        $pk = Yii::$app->request->post($paramName ?: 'modelPk');
        if (!$pk && !$createIfNotFound) {
            throw new BadRequestHttpException('Model primary key must be specified in order to find model.');
        }

        $model = $modelClass::findOne($pk);
        if (!$model) {
            if (!$createIfNotFound) {
                throw new NotFoundHttpException('Model not found.');
            } else {
                $model = $baseModel;
            }
        }

        return $model;
    }
}