<?php

use yii\db\Migration;

/**
 * Class m180225_075233_add_remove_create_update_info
 */
class m180225_075233_add_remove_create_update_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //$this->dropColumn('{{%user}}', 'created_at');
        //$this->dropColumn('{{%user}}', 'updated_at');
        $this->addColumn('{{%user}}', 'type', $this->integer()->notNull()->comment('نوع کاربر%USER_TYPE%'));
        $this->addColumn('{{%user}}', 'created_user_id', $this->integer());
        $this->addColumn('{{%user}}', 'modified_user_id', $this->integer());
        $this->addColumn('{{%user}}', 'created_time', $this->timestamp());
        $this->addColumn('{{%user}}', 'modified_time', $this->timestamp());
        $this->addColumn('{{%user}}', 'deleted_time', $this->timestamp());

        $this->addForeignKey(
            'fk-type-user',
            '{{%user}}',
            'type',
            '{{%domain}}',
            'id'
        );
        $this->addForeignKey(
            'fk-created_user_id-user',
            '{{%user}}',
            'created_user_id',
            '{{%user}}',
            'id'
        );
        $this->addForeignKey(
            'fk-modified_user_id-user',
            '{{%user}}',
            'modified_user_id',
            '{{%user}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%user}}', 'fk-created_user_id-user');
        $this->dropForeignKey('{{%user}}', 'fk-modified_user_id-user');

        $this->dropColumn('{{%user}}', 'created_user_id');
        $this->dropColumn('{{%user}}', 'modified_user_id');
        $this->dropColumn('{{%user}}', 'created_time');
        $this->dropColumn('{{%user}}', 'modified_time');
        $this->dropColumn('{{%user}}', 'deleted_time');

        $this->addColumn('{{%user}}', 'created_at', $this->integer()->notNull());
        $this->addColumn('{{%user}}', 'updated_at', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180225_075233_add_remove_create_update_info cannot be reverted.\n";

        return false;
    }
    */
}
