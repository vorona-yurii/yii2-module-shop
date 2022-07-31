<?php

namespace backend\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_filter_value".
 *
 * @property int $filter_id
 * @property int $value
 * @property string|null $name
 *
 * @property ShopFilter $filter
 */
class ShopFilterValue extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_filter_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['filter_id', 'value'], 'required'],
            [['filter_id', 'value'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['filter_id', 'value'], 'unique', 'targetAttribute' => ['filter_id', 'value']],
            [['filter_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopFilter::className(), 'targetAttribute' => ['filter_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'filter_id' => 'Filter ID',
            'value' => 'Значение',
            'name' => 'Название',
        ];
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
}
