<?php

namespace backend\modules\shop\models;

use backend\modules\shop\src\behavior\CategoryBehavior;

/**
 * This is the model class for table "shop_category".
 *
 * @property int $id
 * @property int|null $import_id
 * @property int|null $parent_id
 * @property string|null $name
 * @property string|null $image
 * @property int|null $sort
 * @property int|null $status
 *
 * @property ShopCategory $parent
 * @property ShopCategory[] $shopCategories
 * @property ShopProductCategory[] $shopProductCategories
 * @property ShopCategoryFilter[] $shopCategoryFilters
 */
class ShopCategory extends \yii\db\ActiveRecord
{
    public $media;
    public $filters;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_category';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => CategoryBehavior::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'status', 'sort', 'import_id'], 'integer'],
            [['image'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['filters'], 'safe'],
            [['media'], 'file', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 8],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'parent_id' => 'Родительская категория',
            'name' => 'Название',
            'image' => 'Картинка',
            'status' => 'Статус',
            'sort' => 'Сортировка',
            'media' => 'Изображение',
            'filters' => 'Связанные фильтры'
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(ShopCategory::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[ShopCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopCategories()
    {
        return $this->hasMany(ShopCategory::className(), ['parent_id' => 'id']);
    }

    /**
     * Gets query for [[ShopProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopProductCategories()
    {
        return $this->hasMany(ShopProductCategory::className(), ['category_id' => 'id']);
    }

    /**
     * Gets query for [[ShopCategoryFilter]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopCategoryFilters()
    {
        return $this->hasMany(ShopCategoryFilter::className(), ['category_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function isParent()
    {
        $children = static::find()
            ->where(['parent_id' => $this->id])
            ->all();
        if (empty($children)) {
            return false;
        } else {
            return true;
        }
    }

    public function getPath()
    {
        $path = [];
        $element = $this;
        do {
            array_unshift($path, $element->name);
            $element = $element->parent;
        } while (isset($element->parent));

        return implode(' > ', $path);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    private static function getTreeRoot()
    {
        $categories = static::find()->where(['parent_id' => null])->all();
        return $categories;
    }

    /**
     * @return array
     */
    public static function getCategoriesTree()
    {
        $result = [];
        $root = self::getTreeRoot();
        self::buildCategoriesTree($root, $result);
        return $result;
    }

    /**
     * @param $root
     * @param array $result
     * @param string $prefix
     * @return array
     */
    private static function buildCategoriesTree($root, array &$result, $prefix = '')
    {
        /* @var self $category */
        foreach ($root as $category) {
            if ($category->isParent()) {
                $children = $category->shopCategories;
                self::buildCategoriesTree(
                    $children,
                    $result,
                    $prefix . $category->name . ' → '
                );
            } else {
                $result[$category->id] = $prefix . $category->name;
            }
        }
        return $result;
    }
}
