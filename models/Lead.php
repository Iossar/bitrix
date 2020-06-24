<?php

namespace app\models;

use yii\db\ActiveRecord;

class Lead extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'leads';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'string'],
        ];
    }
}