<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopDeliveryMethod */

$this->title = 'Изменение метода доставки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Методы доставки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>