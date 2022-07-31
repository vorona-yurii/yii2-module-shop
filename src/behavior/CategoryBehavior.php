<?php

namespace backend\modules\shop\src\behavior;

use backend\modules\shop\models\ShopCategoryFilter;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * Class CategoryBehavior
 * @package backend\modules\shop\src\behavior
 */
class CategoryBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'before',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'before',
            ActiveRecord::EVENT_AFTER_FIND => 'find',
            ActiveRecord::EVENT_AFTER_UPDATE => 'after',
            ActiveRecord::EVENT_AFTER_INSERT => 'after'
        ];
    }

    public function find()
    {
        if ($this->owner->shopCategoryFilters) {
            $this->owner->filters = ArrayHelper::getColumn($this->owner->shopCategoryFilters, 'filter_id');
        }
    }

    /**
     * @throws \yii\base\Exception
     */
    public function before()
    {
        if ($this->owner->media) {
            $filename = $this->upload($this->owner->media);
            if ($filename) {
                $this->owner->image = $filename;
            }
        }
    }

    public function after()
    {
        ShopCategoryFilter::deleteAll(['category_id' => $this->owner->id]);

        if ($this->owner->filters) {
            foreach ($this->owner->filters as $filter_id) {
                $category_filter = new ShopCategoryFilter();
                $category_filter->category_id = $this->owner->id;
                $category_filter->filter_id = $filter_id;
                $category_filter->save();
            }
        }
    }

    /**
     * @param $file
     * @return bool|string
     * @throws \yii\base\Exception
     */
    private function upload($file)
    {
        if (isset($file->name)) {
            $directory = \Yii::getAlias('uploads/category/');

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }

            $pathFile = pathinfo(basename($file->name));
            $filename = $directory . time() . '_' . $pathFile['filename'] . '.' . $pathFile['extension'];
            if ($file->saveAs($filename)) {
                return $filename;
            }
        }
        return false;
    }
}