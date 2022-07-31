<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopCategory */

$this->title = 'Создание категории';
$this->params['breadcrumbs'][] = ['label' => 'Магазин - категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
