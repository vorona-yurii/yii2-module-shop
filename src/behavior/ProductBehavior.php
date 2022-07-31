<?php

namespace backend\modules\shop\src\behavior;

use backend\modules\shop\models\ShopProductCategory;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * Class ProductBehavior
 * @package backend\modules\shop\src\behavior
 */
class ProductBehavior extends Behavior
{
    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'handlerBefore',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'handlerBefore',
            ActiveRecord::EVENT_AFTER_INSERT => 'handlerAfter',
            ActiveRecord::EVENT_AFTER_UPDATE => 'handlerAfter',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete'
        ];
    }

    /**
     * @throws \yii\base\Exception
     */
    public function handlerBefore()
    {
        if ($this->owner->media) {
            $filename = $this->upload($this->owner->media);
            if ($filename) {
                $this->owner->image = $filename;
            }
        }
    }

    public function handlerAfter()
    {
        ShopProductCategory::deleteAll(['product_id' => $this->owner->id]);
        if ($this->owner->categories) {
            foreach ($this->owner->categories as $product_category) {
                $product_c = new ShopProductCategory();
                $product_c->category_id = $product_category;
                $product_c->product_id = $this->owner->id;
                $product_c->save();
            }
        }
    }

    public function afterFind()
    {
        $this->owner->categories = ($this->owner->isNewRecord == false) ? ArrayHelper::getColumn($this->owner->shopProductCategories, 'category_id') : [];
    }

    public function beforeDelete()
    {
        if (is_file(\Yii::getAlias('@web/' . $this->owner->image))) {
            unlink(\Yii::getAlias('@web/' . $this->owner->image));
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
            $directory = \Yii::getAlias('uploads/product/');

            if (!is_dir($directory)) {
                FileHelper::createDirectory($directory);
            }

            $pathFile = pathinfo(basename($file->name));
            $filename = $directory . time() . '_' . $pathFile['filename'] . '.' . $pathFile['extension'];
            if ($file->saveAs($filename)) {
                return '/' . $filename;
            }
        }
        return false;
    }
}