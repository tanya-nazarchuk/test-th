<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%transaction}}".
 *
 * @property integer $id
 * @property integer $receiver_id
 * @property integer $sender_id
 * @property string $amount
 * @property integer $created_at
 *
 * @property User $sender
 * @property User $receiver
 */
class Transaction extends ActiveRecord
{
    const TYPE_SENDING = 0;
    const TYPE_RECEIVING = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction}}';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiver_id', 'sender_id', 'amount'], 'required'],
            [['receiver_id', 'sender_id', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
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
            'receiver_id' => 'Receiver',
            'sender_id' => 'Sender',
            'amount' => 'Amount',
            'created_at' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return (int) ($this->receiver_id == Yii::$app->user->id);
    }

    /**
     * @param $receiverId
     * @param $senderId
     * @param $amount
     * @return bool
     */
    public function addRecord($receiverId, $senderId, $amount)
    {
        $model = new self();
        $model->receiver_id = $receiverId;
        $model->sender_id = $senderId;
        $model->amount = $amount;

        return $model->save();
    }
}
