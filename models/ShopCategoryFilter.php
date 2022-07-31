<?php

namespace backend\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_category_filter".
 *
 * @property int $category_id
 * @property int $filter_id
 *
 * @property ShopCategory $category
 * @property ShopFilter $filter
 */
class ShopCategoryFilter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_category_filter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'filter_id'], 'required'],
            [['category_id', 'filter_id'], 'integer'],
            [['category_id', 'filter_id'], 'unique', 'targetAttribute' => ['category_id', 'filter_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['filter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopFilter::className(), 'targetAttribute' => ['filter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'Category ID',
            'filter_id' => 'Filter ID',
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
     * Gets query for [[Filter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilter()
    {
        return $this->hasOne(ShopFilter::className(), ['id' => 'filter_id']);
    }

    public static function hasActionFilters($category_id)
    {
        return ShopCategoryFilter::find()
            ->joinWith('filter')
            ->joinWith('filter.shopFilterValues')
            ->where(['category_id' => $category_id])
            ->andWhere(['shop_filter.status' => STATUS_ACTIVE])
            ->andWhere(['not', ['is', 'shop_filter_value.value', null]])
            ->exists();
    }
}
