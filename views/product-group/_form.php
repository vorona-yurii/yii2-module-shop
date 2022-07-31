<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\shop\models\ShopProduct;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProductGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'products')->widget(\kartik\select2\Select2::className(),
                [
                    'data' => ArrayHelper::map(ShopProduct::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Выберите товары',
                        'multiple' => true,
                        'max' => 10
                    ],
                ])
            ?>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>