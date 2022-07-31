<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use src\helpers\Common;
use unclead\multipleinput\MultipleInput;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopFilter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'sort')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'status')->dropDownList(Common::getStatusesAll()) ?>

            <br><br>
            <?= $form->field($model, 'values')->widget(MultipleInput::className(), [
                'iconSource' => 'fa',
                'min' => 0,
                'columns' => [
                    [
                        'name' => 'value',
                        'title' => 'Значение',
                        'enableError' => true,
                        'options' => [
                            'class' => 'input-priority',
                            'required' => 'required'
                        ]
                    ],
                    [
                        'name' => 'name',
                        'title' => 'Название',
                        'enableError' => true,
                        'options' => [
                            'class' => 'input-priority',
                            'required' => 'required'
                        ]
                    ],
                ]
            ]); ?>
            <br>
            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>