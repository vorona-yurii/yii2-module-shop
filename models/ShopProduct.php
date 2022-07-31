<?php

namespace backend\modules\shop\models;

use backend\modules\shop\src\behavior\ProductBehavior;
use backend\modules\support\models\ConversationMessage;
use src\behavior\Timestamp;

/**
 * This is the model class for table "shop_product".
 *
 * @property int $id
 * @property int|null $import_id
 * @property string|null $name
 * @property string|null $sku
 * @property float|null $price
 * @property float|null $special
 * @property string|null $description
 * @property string|null $image
 * @property array|null $additional_images
 * @property int|null $min_sale_qty
 * @property int|null $step_sale_qty
 * @property int|null $quantity
 * @property array|null $options
 * @property int|null $sort
 * @property int|null $status
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property ShopProductCategory[] $shopProductCategories
 * @property ConversationMessage[] $supportConversationMessages
 */
class ShopProduct extends \yii\db\ActiveRecord
{
    public $media;
    public $categories;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shop_product';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'product_behavior' => [
                'class' => ProductBehavior::className(),
            ],
            'timestamp' => [
                'class' => Timestamp::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'special'], 'number'],
            [['description', 'image', 'sku'], 'string'],
            [['status', 'sort', 'import_id', 'min_sale_qty', 'step_sale_qty', 'quantity'], 'integer'],
            [['categories', 'additional_images', 'options'], 'safe'],
            [['media'], 'file', 'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 8],
            [['name'], 'string', 'max' => 255],
            [['name', 'status'], 'required']
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
            'sku' => 'Артикул',
            'price' => 'Цена',
            'special' => 'Акционная цена',
            'description' => 'Описание',
            'image' => 'Картинка',
            'media' => 'Картинка',
            'min_sale_qty' => 'min_sale_qty',
            'step_sale_qty' => 'step_sale_qty',
            'quantity' => 'Остаток',
            'status' => 'Статус',
            'sort' => 'Сортировка',
            'options' => 'Опции',
            'categories' => 'Категории'
        ];
    }

    /**
     * Gets query for [[ShopProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShopProductCategories()
    {
        return $this->hasMany(ShopProductCategory::className(), ['product_id' => 'id']);
    }

    /**
     * Gets query for [[SupportConversationMessages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupportConversationMessages()
    {
        return $this->hasMany(ConversationMessage::className(), ['product_id' => 'id']);
    }

    public function getFirstJoinCategoryId()
    {
        return $this->shopProductCategories[count($this->shopProductCategories) - 1]->category_id;
    }

    /**
     * @param $category_id
     * @param $offset
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getPrevNextProduct($category_id, $offset)
    {
        return self::find()
            ->joinWith('shopProductCategories')
            ->where(['shop_product_category.category_id' => $category_id])
            ->andWhere(['shop_product.status' => STATUS_ACTIVE])
            ->orderBy(['sort' => SORT_ASC])
            ->offset($offset)
            ->limit(1)->one();
    }

    public function getProductTextBlock()
    {
        $text = '<b>' . $this->name . ($this->sku ? ' (' . $this->sku . ')' : '') . '</b>' . "\n\n";
        if ($this->description) {
            $text .= $this->description . "\n\n";
        }
        if ((int)$this->special > 0) {
            $text .= "\n<b>Старая цена:</b> <s>" . $this->price . 'грн</s>';
            $text .= "\n<b>Новая цена:</b> " . $this->special . 'грн';
        } else {
            $text .= "\n<b>Цена:</b> " . $this->price . 'грн';
        }
        $text .= "\n" . '<b>Упаковка:</b> ' . $this->min_sale_qty . 'шт';
        $text .= "\n" . '<b>Остаток на складе:</b> ' . $this->quantity . 'шт';

        return $text;
    }
}