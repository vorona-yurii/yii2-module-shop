<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopFilter */

$this->title = 'Создание фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры категорий', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>