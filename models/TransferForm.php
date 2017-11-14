<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\behaviors\DefaultUserBehavior;

/**
 * TransferForm is the model for transfering money.
 *
 */
class TransferForm extends Model
{
    public $username;
    public $amount;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'amount'], 'required'],
            [['username'], 'validateNotCurrentUser'],
            [['amount'], 'number'],
            [['amount'], 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            DefaultUserBehavior::className(),
        ];
    }
    
    /**
     * @param $attribute
     * @param $params
     */
    public function validateNotCurrentUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $userModel = new User();
            $user = $userModel->getOrCreateUser($this->username);

            if (!empty($user) && $user->id == Yii::$app->user->id) {
                $this->addError($attribute, 'Not possible to transfer money to own account.');
            }
        }
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function performTransfer()
    {
        $transaction = Yii::$app->db->beginTransaction();

        $userModel = new User();
        $transactionModel = new Transaction();
        $user = $userModel->getOrCreateUser($this->username);

        if ($transactionModel->addRecord($user->id, Yii::$app->user->id, $this->amount)
                && $userModel->changeBalance($user->id, $this->amount)
                && $userModel->changeBalance(Yii::$app->user->id, -$this->amount)) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function transfer()
    {
        if (!$this->validate()) {
            return false;
        }

        return $this->performTransfer();
    }
}
