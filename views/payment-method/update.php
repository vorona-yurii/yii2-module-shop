<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopPaymentMethod */

$this->title = 'Редактирование метода оплаты: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Методы оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>