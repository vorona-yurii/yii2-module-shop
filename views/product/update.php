<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProduct */

$this->title = 'Изменение товара: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазин - товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="shop-product-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
