<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 */
class m171110_102531_create_transaction_table extends Migration
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

        $this->createTable('{{%transaction}}', [
            'id' => $this->primaryKey(),
            'receiver_id' => $this->integer()->notNull(),
            'sender_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(), // Think if the field can be negative
            'created_at' => $this->integer()->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_transaction_receiver_user', '{{%transaction}}', 'receiver_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('fk_transaction_sender_user', '{{%transaction}}', 'sender_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_transaction_sender_user', '{{%transaction}}');
        $this->dropForeignKey('fk_transaction_receiver_user', '{{%transaction}}');

        $this->dropTable('{{%transaction}}');
    }
}
