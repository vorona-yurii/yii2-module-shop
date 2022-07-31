<?php

namespace backend\modules\shop\models;

use backend\modules\shop\src\behavior\FilterBehavior;
use Yii;

/**
 * This is the model class for table "shop_filter".
 *
 * @property int $id
 * @property int|null $import_id
 * @property string|null $name
 * @property string|null $code
 * @property int|null $sort
 * @property int|null $status
 *
 * @property ShopCategoryFilter[] $shopCategoryFilters
 * @property ShopCategory[] $categories
 * @property ShopFilterValue[] $shopFilterValues
 */
class ShopFilter extends \yii\db\ActiveRecord
{
    public $values;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_filter';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => FilterBehavior::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['import_id', 'sort', 'status'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['values'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'import_id' => 'Import ID',
            'name' => 'Название',
            'code' => 'Код (для интеграции)',
            'sort' => 'Порядок сортировки',
            'status' => 'Статус',
            'values' => 'Значения фильтра'
        ];
    }

    /**
     * Gets query for [[ShopCategoryFilters]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopCategoryFilters()
    {
        return $this->hasMany(ShopCategoryFilter::className(), ['filter_id' => 'id']);
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(ShopCategory::className(), ['id' => 'category_id'])->viaTable('shop_category_filter', ['filter_id' => 'id']);
    }

    /**
     * Gets query for [[ShopFilterValues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopFilterValues()
    {
        return $this->hasMany(ShopFilterValue::className(), ['filter_id' => 'id']);
    }
}
