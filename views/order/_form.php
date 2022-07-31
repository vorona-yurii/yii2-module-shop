<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\shop\models\ShopDeliveryMethod;
use backend\modules\shop\models\ShopPaymentMethod;
use backend\modules\shop\models\ShopOrder;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopOrder */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'integration_id')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'integration_ttn')->textInput() ?>
            <?= $form->field($model, 'date')->textInput(['disabled' => true]) ?>
            <br>
            <?= $form->field($model, 'source')->dropDownList(['bot' => 'Бот', 'site' => 'Сайт',], ['prompt' => '']) ?>
            <?php if ($model->customer) { ?>
                <?= $form->field($model, 'customer_id')->textInput(['value' => $model->customer->fullName, 'disabled' => true]) ?>
            <?php } ?>
            <?= $form->field($model, 'customer_name')->textInput() ?>
            <?= $form->field($model, 'customer_last_name')->textInput() ?>
            <?= $form->field($model, 'customer_phone')->textInput() ?>
            <?= $form->field($model, 'total')->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?= $form->field($model, 'payment_method_id')->dropDownList(ArrayHelper::map(ShopPaymentMethod::find()->all(), 'id', 'name')) ?>
            <?= $form->field($model, 'delivery_method_id')->dropDownList(ArrayHelper::map(ShopDeliveryMethod::find()->all(), 'id', 'name')) ?>
            <?= $form->field($model, 'delivery_city')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'delivery_point')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList(ShopOrder::statuses()) ?>

            <?= $this->render('_products', [
                'model' => $model
            ]); ?>

            <?php if (!$model->isNewRecord) { ?>
                <?= $form->field($model, 'updated_at')->textInput(['disabled' => true]) ?>
            <?php } ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>