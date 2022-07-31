<?php

namespace backend\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_product_category".
 *
 * @property int|null $product_id
 * @property int|null $category_id
 *
 * @property ShopCategory $category
 * @property ShopProduct $product
 */
class ShopProductCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'category_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProduct::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ShopCategory::className(), ['id' => 'category_id']);
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
}
