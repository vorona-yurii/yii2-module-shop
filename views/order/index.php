<?php

use yii\helpers\Html;
use yii\grid\GridView;
use src\helpers\Common;
use backend\modules\shop\models\ShopOrder;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\modules\customer\models\Customer;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\shop\models\search\ShopOrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row content-admin">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table'],
                'options' => ['tag' => 'div', 'class' => 'grid-view table-responsive'],
                'pager' => Common::pager4(),
                'id' => 'shop-delivery_method',
                'columns' => [
                    'id',
                    [
                        'attribute' => 'integration_id',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->integration_id ?? '<span style="color: red">(заказ не был создан)</span>';
                        }
                    ],
                    [
                        'attribute' => 'source',
                        'filter' => ShopOrder::sources(),
                        'value' => function ($data) {
                            return $data->sourceLabel();
                        }
                    ],
                    [
                        'attribute' => 'customer_id',
                        'filter' => Select2::widget([
                            'model' => $searchModel,
                            'attribute' => 'customer_id',
                            'data' => ArrayHelper::map(Customer::find()->all(), 'id', 'fullName'),
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => 'Выберите клиента'
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ]
                        ]),
                        'value' => function ($data) {
                            return $data->customer ? $data->customer->fullName : '';
                        }
                    ],
                    'total',
                    [
                        'attribute' => 'status',
                        'filter' => ShopOrder::statuses(),
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->statusLabel();
                        }
                    ],
                    [
                        'label' => 'Дата заказа',
                        'attribute' => 'date',
                        'filter' => false,
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'headerOptions' => ['width' => '105'],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', ['/shop/order/update', 'id' => $key],
                                    [
                                        'class' => 'btn btn-primary',
                                        'title' => 'Изменить',
                                    ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/shop/order/delete', 'id' => $key],
                                    [
                                        'title' => 'Удалить',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                        'data-confirm' => 'Вы уверены, что хотите удалить даннный заказ?'
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>