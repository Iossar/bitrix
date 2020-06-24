<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\Lead;

/**
 * Class User
 * @package app\models
 * @property int $id
 * @property string $email
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'email'],
        ];
    }

    public static function login($external_user)
    {
        $user = User::find()->where(['id' => $external_user['ID']])->one();
        if ($user == null) {
            $user = new self();
            $user->id = $external_user['ID'];
            $user->email = $external_user['EMAIL'];
            $user->save();
        }
        return $user;

    }

    public function getLeads()
    {
        return $this->hasMany(Lead::class, ['user_id' => 'id'])->limit(5);
    }
}
