<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProductGroup */

$this->title = 'Редактирование товарной группы: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Товарные группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="shop-product-group-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
