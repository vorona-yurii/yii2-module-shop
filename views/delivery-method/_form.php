<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopDeliveryMethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'api_id')->textInput(['type' => 'number']) ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>