<?php

namespace backend\modules\shop\models;

use backend\modules\customer\models\Customer;
use src\behavior\Timestamp;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shop_order".
 *
 * @property int $id
 * @property int|null $integration_id
 * @property string|null $integration_ttn
 * @property string|null $source
 * @property int|null $customer_id
 * @property string|null $customer_name
 * @property string|null $customer_last_name
 * @property string|null $customer_phone
 * @property float|null $total
 * @property int|null $payment_method_id
 * @property int|null $delivery_method_id
 * @property string|null $delivery_city
 * @property string|null $delivery_point
 * @property int $status
 * @property int $is_buy_one_click
 * @property string|null $date
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property Customer $customer
 * @property ShopDeliveryMethod $deliveryMethod
 * @property ShopPaymentMethod $paymentMethod
 * @property ShopOrderProduct[] $shopOrderProducts
 */
class ShopOrder extends \yii\db\ActiveRecord
{
    const SOURCE_BOT = 'bot';
    const SOURCE_SITE = 'site';

    const STATUS_PENDING = 'pending';
    const STATUS_AUTOBUS = 'autobus';
    const STATUS_CANCELED = 'canceled';
    const STATUS_CLOSED = 'closed';
    const STATUS_COMPLETE = 'complete';
    const STATUS_FIRST_CALL = 'first_call';
    const STATUS_HOLDED = 'holded';
    const STATUS_NOMONEY = 'nomoney';
    const STATUS_PAYMENT_REVIEW = 'payment_review';
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PROCESSING = 'processing';
    const STATUS_READY_TO_SHIP = 'ready_to_ship';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order';
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
            [['integration_id', 'customer_id', 'payment_method_id', 'delivery_method_id', 'is_buy_one_click'], 'integer'],
            [['source', 'status', 'integration_ttn'], 'string'],
            ['source', 'default', 'value' => self::SOURCE_BOT],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            [['total'], 'number'],
            [['updated_at', 'created_at', 'date', 'customer_phone'], 'safe'],
            [['delivery_city', 'delivery_point', 'customer_name', 'customer_last_name'], 'string', 'max' => 255],
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
            'integration_id' => 'ID заказа в системе',
            'integration_ttn' => 'ТТН',
            'source' => 'Источник',
            'customer_id' => 'Клиент',
            'customer_name' => 'Имя клиента',
            'customer_last_name' => 'Фамилия клиента',
            'customer_phone' => 'Телефон клиента',
            'total' => 'Итого',
            'payment_method_id' => 'Метод оплаты',
            'delivery_method_id' => 'Метод доставки',
            'delivery_city' => 'Город доставки',
            'delivery_point' => 'Место доставки (отделение почты)',
            'status' => 'Статус',
            'date' => 'Дата заказа',
            'is_buy_one_click' => 'Заказ в 1 клик',
            'updated_at' => 'Изменен в',
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
     * Gets query for [[ShopOrderProducts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopOrderProducts()
    {
        return $this->hasMany(ShopOrderProduct::className(), ['order_id' => 'id']);
    }

    public function sourceLabel()
    {
        $s = '';
        switch ($this->source) {
            case self::SOURCE_SITE:
                $s = 'Сайт';
                break;
            case self::SOURCE_BOT:
                $s = 'Бот';
                break;
        }
        return $s;
    }

    public function statusLabel()
    {
        if (isset(self::statuses()[$this->status])) {
            return self::statuses()[$this->status];
        }
        return '';
    }

    public static function sources()
    {
        return [
            self::SOURCE_BOT => 'Бот',
            self::SOURCE_SITE => 'Сайт'
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING => 'Новый',
            self::STATUS_AUTOBUS => 'Автобус',
            self::STATUS_CANCELED => 'Отменен',
            self::STATUS_CLOSED => 'Отправлен',
            self::STATUS_COMPLETE => 'Закрыт',
            self::STATUS_FIRST_CALL => 'Первый звонок',
            self::STATUS_HOLDED => 'Обработка',
            self::STATUS_NOMONEY => 'Нет денег',
            self::STATUS_PAYMENT_REVIEW => 'Соединен',
            self::STATUS_PENDING_PAYMENT => 'Готов',
            self::STATUS_PROCESSING => 'Ждем оплаты',
            self::STATUS_READY_TO_SHIP => 'Готов к доставке'
        ];
    }

    public static function searchStatusByName($name)
    {
        foreach (self::statuses() as $key => $status) {
            if ($status == $name) {
                return $key;
            }
        }
        return null;
    }

    /**
     * Получить 10 записей отсортированных по дате ASC внутри выборки самых последних
     * @param $customer_id
     * @param $offset
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getOrdersOffsetByCustomerId($customer_id, $offset, $limit = 3)
    {
        $order_ids = self::find()
            ->select(['id'])
            ->where(['customer_id' => $customer_id])
            ->limit($limit)
            ->offset($offset)
            ->orderBy(['integration_id' => SORT_DESC, 'id' => SORT_DESC])
            ->all();
        return self::find()
            ->where(['in', 'id', ArrayHelper::getColumn($order_ids, 'id')])
            ->orderBy(['integration_id' => SORT_ASC])
            ->all();
    }

    public function getOrderFullText()
    {
        $text = 'Заказ №' . ($this->integration_id ?? $this->id) . "\n";
        $text .= 'Дата заказа: ' . $this->date . "\n";
        foreach ($this->shopOrderProducts as $order_product) {
            $text .= $order_product->name . ($order_product->option ? ' (' . $order_product->option . ')' : '') . ' - ' . $order_product->quantity . ' * ' . round($order_product->price) . ' грн = ' . round($order_product->total) . ' грн' . "\n";
        }
        $text .= 'Итоговая сумма: ' . round($this->total) . ' грн' . "\n\n";
        $text .= 'Клиент: ' . $this->customer_name . ' ' . $this->customer_last_name . "\n";
        $text .= 'Телефон: ' . $this->customer_phone . "\n";
        if ($this->paymentMethod) {
            $text .= 'Метод оплаты: ' . $this->paymentMethod->name . "\n";
        }
        if ($this->deliveryMethod) {
            $text .= 'Метод доставки: ' . $this->deliveryMethod->name . "\n";
        }
        if ($this->delivery_city) {
            $text .= 'Город доставки: ' . $this->delivery_city . "\n";
            $text .= 'Дополнительные данные: ' . $this->delivery_point . "\n\n";
        }
        if ($this->integration_ttn) {
            $text .= 'ТТН: ' . $this->integration_ttn . "\n\n";
        }
        $text .= 'Статус заказа: ' . $this->statusLabel();
        return $text;
    }

    public static function addOrderFromCart(ShopCart $cart)
    {
        $order = new self();
        $order->customer_id = $cart->customer_id;
        $order->customer_name = $cart->customer_name;
        $order->customer_last_name = $cart->customer_last_name;
        $order->customer_phone = $cart->customer_phone;
        $order->total = $cart->total;
        $order->payment_method_id = $cart->payment_method_id;
        $order->delivery_method_id = $cart->delivery_method_id;
        $order->delivery_city = $cart->delivery_city;
        $order->delivery_point = $cart->delivery_point;
        $order->date = date('Y-m-d H:i:s');
        if ($order->save()) {
            foreach ($cart->shopCartProducts as $cart_product) {
                $order_product = new ShopOrderProduct();
                $order_product->order_id = $order->id;
                $order_product->name = $cart_product->name;
                $order_product->product_id = $cart_product->product_id;
                $order_product->price = $cart_product->price;
                $order_product->quantity = $cart_product->quantity;
                $order_product->option = $cart_product->option;
                $order_product->save();
            }
            return $order;
        }
        return null;
    }

    public static function buyOneClick(Customer $customer, ShopProduct $product, $quantity = 0)
    {
        $order = new self();
        $order->customer_id = $customer->id;
        $order->customer_name = $customer->first_name;
        $order->customer_last_name = $customer->last_name;
        $order->customer_phone = $customer->phone;
        $order->total = $product->step_sale_qty * (!empty((int)$product->special) ? $product->special : $product->price);
        $order->date = date('Y-m-d H:i:s');
        $order->is_buy_one_click = 1;
        if ($order->save()) {
            $order_product = new ShopOrderProduct();
            $order_product->order_id = $order->id;
            $order_product->name = $product->name;
            $order_product->product_id = $product->id;
            $order_product->price = !empty((int)$product->special) ? $product->special : $product->price;
            $order_product->quantity = ($quantity > 0) ? $quantity : $product->step_sale_qty;
            $order_product->save();
            return $order;
        }
        return null;
    }

    public static function getRelationCustomerOrders(Customer $customer)
    {
        if ($customer->relationCustomer) {
            $orders = self::find()->where(['customer_id' => $customer->relationCustomer->id])->andWhere(['not', ['is', 'integration_id', null]])->orderBy(['date' => SORT_DESC]);
            if ($orders->exists()) {
                $response = [];
                foreach ($orders->all() as $order) {
                    $response[] = [
                        'id' => $order->integration_id,
                        'date' => $order->date,
                        'link' => DOMAIN . '/shop/order/update?id=' . $order->id
                    ];
                }
                return $response;
            }
        }
        return null;
    }
}
