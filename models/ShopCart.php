<?php

namespace backend\modules\shop\models;

use backend\modules\customer\models\Customer;
use src\behavior\Timestamp;
use Yii;

/**
 * This is the model class for table "shop_cart".
 *
 * @property int $id
 * @property int|null $customer_id
 * @property string|null $customer_name
 * @property string|null $customer_last_name
 * @property string|null $customer_phone
 * @property float|null $total
 * @property int|null $payment_method_id
 * @property int|null $delivery_method_id
 * @property string|null $delivery_city
 * @property string|null $delivery_point
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Customer $customer
 * @property ShopDeliveryMethod $deliveryMethod
 * @property ShopPaymentMethod $paymentMethod
 * @property ShopCartProduct[] $shopCartProducts
 */
class ShopCart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_cart';
    }

    public function behaviors()
    {
        return [
            [
                'class' => Timestamp::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'payment_method_id', 'delivery_method_id'], 'integer'],
            [['total'], 'number'],
            [['updated_at', 'created_at'], 'safe'],
            [['delivery_city', 'delivery_point', 'customer_name', 'customer_last_name', 'customer_phone'], 'string', 'max' => 255],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
            [['delivery_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopDeliveryMethod::className(), 'targetAttribute' => ['delivery_method_id' => 'id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopPaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'customer_name' => 'Customer Name',
            'customer_last_name' => 'Customer Last Name',
            'customer_phone' => 'Customer Phone',
            'total' => 'Total',
            'payment_method_id' => 'Payment Method ID',
            'delivery_method_id' => 'Delivery Method ID',
            'delivery_city' => 'Delivery City',
            'delivery_point' => 'Delivery Point',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * Gets query for [[DeliveryMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryMethod()
    {
        return $this->hasOne(ShopDeliveryMethod::className(), ['id' => 'delivery_method_id']);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(ShopPaymentMethod::className(), ['id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[ShopCartProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopCartProducts()
    {
        return $this->hasMany(ShopCartProduct::className(), ['cart_id' => 'id']);
    }

    public function isFull()
    {
        if (!$this->customer_phone) {
            return false;
        } else if (!$this->customer_name) {
            return false;
        } else if (!$this->payment_method_id) {
            return false;
        } elseif (!$this->delivery_method_id) {
            return false;
        } elseif (!$this->delivery_city && ($this->delivery_method_id != ShopDeliveryMethod::PICKUP_METHOD_ID)) {
            return false;
        } elseif (!$this->delivery_point && ($this->delivery_method_id != ShopDeliveryMethod::PICKUP_METHOD_ID)) {
            return false;
        }
        return true;
    }

    public function getCartFullText()
    {
        $text = 'Ваш заказ:' . "\n";
        foreach ($this->shopCartProducts as $cart_product) {
            $text .= $cart_product->name . ($cart_product->option ? ' (' . $cart_product->option . ')' : '') . ' - ' . $cart_product->quantity . ' * ' . round($cart_product->price) . ' грн = ' . round($cart_product->total) . ' грн' . "\n\n";
        }
        $text .= 'Итоговая сумма: ' . round($this->total) . ' грн' . "\n\n";
        if ($this->customer_name) {
            $text .= 'Клиент: ' . $this->customer_name . ' ' . $this->customer_last_name . "\n";
        }
        if ($this->customer_phone) {
            $text .= 'Телефон: ' . $this->customer_phone . "\n";
        }
        if ($this->paymentMethod) {
            $text .= 'Метод оплаты: ' . $this->paymentMethod->name . "\n";
        }
        if ($this->deliveryMethod) {
            $text .= 'Метод доставки: ' . $this->deliveryMethod->name . "\n";
        }
        if ($this->delivery_city) {
            $text .= 'Город доставки: ' . $this->delivery_city . "\n";
            $text .= 'Дополнительные данные: ' . $this->delivery_point;
        }
        return $text;
    }

    public static function selfCart($customer_id)
    {
        $customer = Customer::findOne($customer_id);
        if ($customer && !$cart = self::findOne(['customer_id' => $customer_id])) {
            $cart = new self();
            $cart->customer_phone = $customer->phone;
            $cart->customer_name = $customer->first_name;
            $cart->customer_last_name = $customer->last_name;
            if ($customer->phone && $old_order = ShopOrder::find()->where(['like', 'customer_phone', $customer->phone])->andWhere(['!=', 'is_buy_one_click', 1])->orderBy(['id' => SORT_DESC])->one()) {
                $cart->customer_name = $old_order->customer_name;
                $cart->customer_last_name = $old_order->customer_last_name;
                $cart->payment_method_id = $old_order->payment_method_id;
                $cart->delivery_method_id = $old_order->delivery_method_id;
                $cart->delivery_city = $old_order->delivery_city;
                $cart->delivery_point = $old_order->delivery_point;
            }
            $cart->customer_id = $customer_id;
            $cart->save();
        }
        return $cart;
    }

    public static function addCartFromOrder(ShopOrder $order)
    {
        if ($cart = self::findOne(['customer_id' => $order->customer_id])) {
            $cart->delete();
        }
        $cart = new self();
        $cart->customer_id = $order->customer_id;
        $cart->customer_phone = $order->customer->phone;
        $cart->customer_name = $order->customer->first_name;
        $cart->customer_last_name = $order->customer->last_name;
        $cart->payment_method_id = $order->payment_method_id;
        $cart->delivery_method_id = $order->delivery_method_id;
        $cart->delivery_city = $order->delivery_city;
        $cart->delivery_point = $order->delivery_point;
        if ($cart->save()) {
            foreach ($order->shopOrderProducts as $order_product) {
                $cart_product = new ShopCartProduct();
                $cart_product->cart_id = $cart->id;
                $cart_product->name = $order_product->name;
                $cart_product->product_id = $order_product->product_id;
                $cart_product->price = $order_product->price;
                $cart_product->quantity = $order_product->quantity;
                $cart_product->option = $order_product->option;
                $cart_product->save();
            }
            $cart->total = $order->total;
            $cart->save();
            return $cart;
        }
        return null;
    }
}