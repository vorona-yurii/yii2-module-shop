<?php

use yii\helpers\Html;
use yii\grid\GridView;
use src\helpers\Common;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\shop\models\search\ShopFilterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фильтры';
$this->params['breadcrumbs'][] = $this->title;
$this->params['right_content'] = '';
$this->params['right_content'] .= \src\helpers\Buttons::create('Добавить фильтр');
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
                'id' => 'shop-filter',
                'columns' => [
                    'name',
                    'sort',
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
                                return Html::a('<i class="fas fa-pencil-alt"></i>', ['/shop/filter/update', 'id' => $key],
                                    [
                                        'class' => 'btn btn-primary',
                                        'title' => 'Изменить',
                                    ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/shop/filter/delete', 'id' => $key],
                                    [
                                        'title' => 'Удалить',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                        'data-confirm' => 'Вы уверены, что хотите удалить даннный фильтр?'
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>