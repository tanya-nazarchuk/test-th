<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m171109_204210_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'access_token' => $this->string()->unique()->defaultValue(null),
            'balance' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'created_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
