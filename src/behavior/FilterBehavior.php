<?php

namespace backend\modules\shop\src\behavior;

use backend\modules\shop\models\ShopFilterValue;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 * Class FilterBehavior
 * @package backend\modules\shop\src\behavior
 */
class FilterBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'find',
            ActiveRecord::EVENT_AFTER_INSERT => 'after',
            ActiveRecord::EVENT_AFTER_UPDATE => 'after'
        ];
    }

    public function find()
    {
        if (!$this->owner->isNewRecord && $this->owner->shopFilterValues) {
            foreach ($this->owner->shopFilterValues as $filterValue) {
                $values[] = [
                    'value' => $filterValue->value,
                    'name' => $filterValue->name
                ];
            }
            $this->owner->values = $values;
        }
    }

    public function after()
    {
        ShopFilterValue::deleteAll(['filter_id' => $this->owner->id]);

        if ($this->owner->values) {
            foreach ($this->owner->values as $value) {
                $filter_value = new ShopFilterValue();
                $filter_value->filter_id = $this->owner->id;
                $filter_value->value = $value['value'];
                $filter_value->name = $value['name'];
                $filter_value->save();
            }
        }
    }
}