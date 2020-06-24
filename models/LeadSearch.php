<?php

namespace app\models;

use yii\base\Model;
use app\models\Lead;
use yii\data\ActiveDataProvider;

class LeadSearch extends Lead
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return parent::rules();
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = Lead::find()->where(['user_id' => $params['user_id']])->limit(5);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}