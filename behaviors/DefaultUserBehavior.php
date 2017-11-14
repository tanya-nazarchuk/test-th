<?php
namespace app\behaviors;

use Yii;
use yii\base\Behavior;
use app\models\User;

class DefaultUserBehavior extends Behavior
{
    /**
     * @param null $id
     */
    public function setDefaultUser($id = null)
    {
        if (!empty($id)) {
            $user = User::findOne($id);
            if (!empty($user)) {
                $this->owner->username = $user->username;
            }
        }
    }
}