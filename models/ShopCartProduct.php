<?php

namespace backend\modules\shop\models;

use backend\modules\shop\src\behavior\CartProductBehavior;
use src\behavior\Timestamp;
use Yii;

/**
 * This is the model class for table "shop_cart_product".
 *
 * @property int $id
 * @property int|null $cart_id
 * @property int|null $product_id
 * @property string|null $name
 * @property float|null $price
 * @property int|null $quantity
 * @property string|null $option
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property ShopCart $cart
 * @property ShopProduct $product
 */
class ShopCartProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_cart_product';
    }

    public function behaviors()
    {
        return [
            [
                'class' => Timestamp::className(),
            ],
            [
                'class' => CartProductBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cart_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['option', 'updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['cart_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCart::className(), 'targetAttribute' => ['cart_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cart_id' => 'Cart ID',
            'product_id' => 'Product ID',
            'name' => 'Name',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'option' => 'Option',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Cart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCart()
    {
        return $this->hasOne(ShopCart::className(), ['id' => 'cart_id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(ShopProduct::className(), ['id' => 'product_id']);
    }

    public function getTotal()
    {
        return $this->quantity * $this->price;
    }

    public static function cartProduct($customer_id, $product_id, $option = null)
    {
        $cart = ShopCart::selfCart($customer_id);
        if ($cart_product = self::findOne(['cart_id' => $cart->id, 'product_id' => $product_id, 'option' => $option])) {
            return $cart_product;
        }
        return null;
    }

    public static function addProduct($customer_id, $product_id, $increment = 1, $option = null)
    {
        if (!$product = ShopProduct::findOne($product_id)) {
            return null;
        }
        $cart = ShopCart::selfCart($customer_id);
        if (!$cart_product = self::findOne(['cart_id' => $cart->id, 'product_id' => $product_id, 'option' => $option])) {
            $cart_product = new self();
            $cart_product->cart_id = $cart->id;
            $cart_product->product_id = $product_id;
            $cart_product->name = $product->name;
            $cart_product->quantity = 0;
            $cart_product->price = ((int)$product->special > 0) ? $product->special : $product->price;
            $cart_product->option = $option;
            $cart_product->save();
        }
        $cart_product->quantity += $increment;
        $cart_product->save();
        return $cart_product;
    }

    public static function deleteProduct($customer_id, $product_id, $option = null)
    {
        if ($cart = ShopCart::findOne(['customer_id' => $customer_id])) {
            if ($cart_product = self::findOne(['cart_id' => $cart->id, 'product_id' => $product_id, 'option' => $option])) {
                $cart_product->delete();
            }
            if (self::find()->where(['cart_id' => $cart->id])->count() == 0) {
                $cart->delete();
            }
            return true;
        }
        return false;
    }
}