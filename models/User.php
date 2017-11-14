<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $balance
 * @property integer $created_at
 *
 * @property Transaction[] $transactions
 * @property Transaction[] $transactions0
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'trim'],
            [['username'], 'string', 'min' => 2],
            [['username', 'access_token'], 'string', 'max' => 255],
            [['username', 'access_token'], 'unique'],
            [['auth_key'], 'string', 'max' => 32],
            [['balance'], 'number'],
            [['created_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => null,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'balance' => 'Balance',
            'created_at' => 'Registered',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @param $username
     * @return User|null
     */
    public function getOrCreateUser($username)
    {
        $user = User::findByUsername($username);
        if (!$user) {
            $user = new User;
            $user->username = $username;
            $user->save();
        }

        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @return boolean
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->generateAuthKey();
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderTransactions()
    {
        return $this->hasMany(Transaction::className(), ['sender_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverTransactions()
    {
        return $this->hasMany(Transaction::className(), ['receiver_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()->select(['username', 'id'])->indexBy('id')->column();
    }

    /**
     * @param int $userId
     * @return null|static
     */
    public function findById($userId)
    {
        return User::findOne($userId);
    }
    
    /**
     * @param $userId
     * @param $amount
     * @return bool
     */
    public function changeBalance($userId, $amount)
    {
        $user = $this->findById($userId);
        
        if (!$user) {
            return false;
        }

        $user->balance += $amount;
        return $user->save();
    }
}
