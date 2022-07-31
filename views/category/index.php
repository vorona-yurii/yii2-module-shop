<?php

use yii\grid\GridView;
use src\helpers\Common;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\shop\models\ShopCategory;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\shop\models\search\ShopCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории продуктов';
$this->params['breadcrumbs'][] = $this->title;
$this->params['right_content'] = '';
$this->params['right_content'] .= \src\helpers\Buttons::create('Добавить категорию');
$this->params['right_content'] .= \common\widgets\PaginationWidget::widget();
?>
<div class="row content-admin">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table'],
                'options' => ['tag' => 'div', 'class' => 'grid-view table-responsive'],
                'pager' => Common::pager4(),
                'id' => 'shop-category',
                'columns' => [
                    [
                        'format' => 'raw',
                        'attribute' => 'parent_id',
                        'filter' => ArrayHelper::map(ShopCategory::find()->all(), 'id', 'name'),
                        'value' => function ($data) {
                            return $data->parent_id ? ShopCategory::findOne($data->parent_id)->name : '(не указано)';
                        },
                    ],
                    'name',
                    [
                        'format' => 'raw',
                        'attribute' => 'status',
                        'filter' => Common::getStatusesAll(),
                        'value' => function ($data) {
                            return Common::getStatus($data->status);
                        },
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'headerOptions' => ['width' => '105'],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', ['/shop/category/update', 'id' => $key],
                                    [
                                        'class' => 'btn btn-primary',
                                        'title' => 'Изменить',
                                    ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/shop/category/delete', 'id' => $key],
                                    [
                                        'title' => 'Удалить',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                        'data-confirm' => 'Вы уверены, что хотите удалить данную категорию?'
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>