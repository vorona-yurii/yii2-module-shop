<?php

/* @var $model \backend\modules\shop\models\ShopOrder */

?>

<?php if ($model->shopOrderProducts) { ?>
    <hr>
    <div class="row">
        <div class="col-12">
            <div class="grid-view">
                <h6>Товары</h6>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Название</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($model->shopOrderProducts as $order_product) { ?>
                        <tr>
                            <td><?= $order_product->name ?></td>
                            <td><?= $order_product->quantity ?></td>
                            <td><?= $order_product->price ?></td>
                            <td><?= $order_product->total ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
<?php } ?>
