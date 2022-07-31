<?php

namespace backend\modules\shop\models;

use src\behavior\Timestamp;
use Yii;

/**
 * This is the model class for table "shop_order_product".
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $product_id
 * @property string|null $name
 * @property float|null $price
 * @property int|null $quantity
 * @property string|null $option
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property ShopOrder $order
 * @property ShopProduct $product
 */
class ShopOrderProduct extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_order_product';
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
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['option', 'updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopOrder::className(), 'targetAttribute' => ['order_id' => 'id']],
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
            'order_id' => 'ID заказа',
            'product_id' => 'ID товара',
            'name' => 'Навзание',
            'price' => 'Цена',
            'quantity' => 'Количество',
            'option' => 'Опция',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(ShopOrder::className(), ['id' => 'order_id']);
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
}
