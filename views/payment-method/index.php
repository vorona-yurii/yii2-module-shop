<?php

use yii\helpers\Html;
use yii\grid\GridView;
use src\helpers\Common;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\shop\models\search\ShopPaymentMethodSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Методы оплаты';
$this->params['breadcrumbs'][] = $this->title;
$this->params['right_content'] = '';
$this->params['right_content'] .= \src\helpers\Buttons::create('Добавить метод оплаты');
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
                'id' => 'shop-payment_method',
                'columns' => [
                    'name',
                    'api_id',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'headerOptions' => ['width' => '105'],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', ['/shop/payment-method/update', 'id' => $key],
                                    [
                                        'class' => 'btn btn-primary',
                                        'title' => 'Изменить',
                                    ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/shop/payment-method/delete', 'id' => $key],
                                    [
                                        'title' => 'Удалить',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                        'data-confirm' => 'Вы уверены, что хотите удалить даннный метод оплаты?'
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>