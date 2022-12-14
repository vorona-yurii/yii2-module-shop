<?php

namespace backend\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\shop\models\ShopProduct;

/**
 * ShopProduct represents the model behind the search form of `backend\modules\shop\models\ShopProduct`.
 */
class ShopProductSearch extends ShopProduct
{
    public $category_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'sort', 'category_id'], 'integer'],
            [['name', 'description', 'sku'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ShopProduct::find()->joinWith('shopProductCategories')->groupBy('shop_product.id');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'sort' => $this->sort,
        ]);

        $query->andFilterWhere(['shop_product_category.category_id' => $this->category_id]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
