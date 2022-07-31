<?php

use yii\helpers\Html;
use yii\grid\GridView;
use src\helpers\Common;
use backend\modules\bot\models\Bot;
use src\api\ShortUrlApi;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\shop\models\search\ShopProductGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товарные группы';
$this->params['breadcrumbs'][] = $this->title;
$this->params['right_content'] = '';
$this->params['right_content'] .= \src\helpers\Buttons::create('Добавить группу');
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
                'id' => 'shop-product-group',
                'columns' => [
                    'id',
                    'name',
                    [
                        'label' => 'Диплинк Viber',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $link = ShortUrlApi::API_URL . '/' . $data->slug . 'v';
                            return '<a target="_blank" href="' . $link . '">' . $link . '</a>';
                        }
                    ],
                    [
                        'label' => 'Диплинк Telegram',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $link = ShortUrlApi::API_URL . '/' . $data->slug . 't';
                            return '<a target="_blank" href="' . $link . '">' . $link . '</a>';
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'headerOptions' => ['width' => '105'],
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-pencil-alt"></i>', ['/shop/product-group/update', 'id' => $key],
                                    [
                                        'class' => 'btn btn-primary',
                                        'title' => 'Изменить',
                                    ]);
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<i class="fas fa-trash-alt"></i>', ['/shop/product-group/delete', 'id' => $key],
                                    [
                                        'title' => 'Удалить',
                                        'class' => 'btn btn-danger',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
                                        'data-confirm' => 'Вы уверены, что хотите удалить данную продуктовую группу?'
                                    ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
