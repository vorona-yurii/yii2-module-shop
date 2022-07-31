<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProduct */

$this->title = 'Создание товара';
$this->params['breadcrumbs'][] = ['label' => 'Магазин - товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-product-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
