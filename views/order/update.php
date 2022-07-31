<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopOrder */

$this->title = 'Изменение заказа #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>