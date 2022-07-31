<?php

namespace backend\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_payment_method".
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $api_id
 *
 * @property ShopCart[] $shopCarts
 * @property ShopOrder[] $shopOrders
 */
class ShopPaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['api_id'], 'integer'],
            [['name', 'api_id'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'api_id' => 'Api ID'
        ];
    }

    /**
     * Gets query for [[ShopCarts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopCarts()
    {
        return $this->hasMany(ShopCart::className(), ['payment_method_id' => 'id']);
    }

    /**
     * Gets query for [[ShopOrders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopOrders()
    {
        return $this->hasMany(ShopOrder::className(), ['payment_method_id' => 'id']);
    }
}
