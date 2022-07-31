<?php

namespace backend\modules\shop\models;

use backend\modules\shop\src\behavior\ProductGroupBehavior;
use src\behavior\Timestamp;
use Yii;

/**
 * This is the model class for table "shop_product_group".
 *
 * @property int $id
 * @property string|null $name
 * @property array|null $products
 * @property string|null $slug
 * @property string|null $updated_at
 * @property string|null $created_at
 */
class ShopProductGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_product_group';
    }

    public function behaviors()
    {
        return [
            [
                'class' => Timestamp::className(),
            ],
            [
                'class' => ProductGroupBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['products', 'updated_at', 'created_at'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
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
            'products' => 'Товары',
            'slug' => 'Slug',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At'
        ];
    }
}
