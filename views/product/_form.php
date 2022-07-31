<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\shop\models\ShopCategory;
use backend\modules\bot\models\Bot;
use src\api\ShortUrlApi;

/* @var $this yii\web\View */
/* @var $model backend\modules\shop\models\ShopProduct */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
    $(".clear-image").click(function(e) {
        e.preventDefault();
        $.post("' . \yii\helpers\Url::to(['/shop/product/ajax-delete-image', 'id' => $model->id]) . '", function(data) {
            $("#shopproduct-media").val("");
            $(".product-image").remove();
        });
    });
');
?>
<style>
    .list-cell__id {
        padding: 0 !important;
    }
</style>
<div class="row">
    <div class="col-md-12 p-0 p-md-3">
        <div class="bg-element">
            <?php if (!$model->isNewRecord) { ?>
                <p>
                    <?php foreach (Bot::find()->where(['type' => Bot::TYPE_MAIN])->all() as $bot) { ?>
                        <span><b>Диплинк <?= $bot->platform ?>-бота:</b> <a target="_blank" href="<?= ShortUrlApi::toShort($bot, $model) ?>"><?= ShortUrlApi::toShort($bot, $model) ?></a></span><br>
                    <?php } ?>
                </p>
            <?php } ?>

            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'quantity')->textInput(['type' => 'number']) ?>
            <?php if ($model->image) { ?>
                <div class="product-image" style="margin-bottom: 20px;">
                    <img src="<?= \yii\helpers\Url::to($model->image); ?>" width="150">
                    <br/><a href="#" class="clear-image">Удалить картинку</a>
                </div>
            <?php } ?>
            <?= $form->field($model, 'media')->fileInput() ?>
            <?= $form->field($model, 'status')->dropDownList(\src\helpers\Common::getStatusesAll()) ?>
            <?= $form->field($model, 'sort')->textInput(['type' => 'number']) ?>
            <?= $form->field($model, 'categories')->widget(\kartik\select2\Select2::className(),
                [
                    'data' => ShopCategory::getCategoriesTree(),
                    'options' => [
                        'placeholder' => 'Выберите категории',
                        'multiple' => true
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