<?php

namespace backend\modules\shop\src\behavior;

use backend\modules\shop\models\ShopCart;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class CartProductBehavior
 * @package backend\modules\shop\src\behavior
 */
class CartProductBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'cartTotal',
            ActiveRecord::EVENT_AFTER_UPDATE => 'cartTotal',
            ActiveRecord::EVENT_AFTER_DELETE => 'cartTotal',
        ];
    }

    public function cartTotal()
    {
        $total = 0;
        $cart = ShopCart::findOne($this->owner->cart->id);
        if ($cart) {
            foreach ($cart->shopCartProducts as $product) {
                $total += $product->price * $product->quantity;
            }
            $cart->total = $total;
            $cart->save();
        }
    }
}