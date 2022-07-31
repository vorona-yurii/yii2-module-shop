<?php

namespace backend\modules\shop\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\shop\models\ShopOrder;

/**
 * ShopOrderSearch represents the model behind the search form of `backend\modules\shop\models\ShopOrder`.
 */
class ShopOrderSearch extends ShopOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'integration_id', 'customer_id', 'payment_method_id', 'delivery_method_id'], 'integer'],
            [['source', 'delivery_city', 'delivery_point', 'updated_at', 'created_at', 'status'], 'safe'],
            [['total'], 'number'],
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
        $query = ShopOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
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
            'integration_id' => $this->integration_id,
            'customer_id' => $this->customer_id,
            'total' => $this->total,
            'payment_method_id' => $this->payment_method_id,
            'delivery_method_id' => $this->delivery_method_id,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'source', $this->source])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'delivery_city', $this->delivery_city])
            ->andFilterWhere(['like', 'delivery_point', $this->delivery_point]);

        return $dataProvider;
    }
}
