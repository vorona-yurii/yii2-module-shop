<?php

use yii\helpers\Html;
use yii\helpers\Url;
use src\helpers\Common;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use backend\modules\shop\models\ShopFilter;
use backend\modules\shop\models\ShopCategory;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopCategory */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
    $(".clear-image").click(function(e) {
        e.preventDefault();
        $.post("' . Url::to(['/shop/category/ajax-delete-image', 'id' => $model->id]) . '", function(data) {
            $("#shopcategory-media").val("");
            $(".category-image").remove();
        });
    });
');
?>

<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(ShopCategory::find()->joinWith('shopProductCategories')->where(['is', 'shop_product_category.product_id', null])->all(), 'id', 'name'), ['prompt' => 'Выберите категорию']); ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?php if ($model->image) { ?>
                <div class="category-image" style="margin-bottom: 20px;">
                    <img src="/<?= \yii\helpers\Url::to($model->image); ?>" width="150">
                    <br/><a href="#" class="clear-image">Удалить картинку</a>
                </div>
            <?php } ?>
            <?= $form->field($model, 'media')->fileInput() ?>
            <?= $form->field($model, 'status')->dropDownList(Common::getStatusesAll()) ?>
            <?= $form->field($model, 'sort')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'filters')->widget(Select2::className(),
                [
                    'data' => ArrayHelper::map(ShopFilter::find()->all(), 'id', 'name'),
                    'options' => [
                        'placeholder' => 'Виберите фильтры',
                        'multiple' => true
                    ],
                ]);
            ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>