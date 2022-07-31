<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProductGroup */

$this->title = 'Создать товарную группу';
$this->params['breadcrumbs'][] = ['label' => 'Товарные группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-product-group-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
