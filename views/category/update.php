<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopCategory */

$this->title = 'Редактрование категории: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Магазин - категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="shop-category-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
