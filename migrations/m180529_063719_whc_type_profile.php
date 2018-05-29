<?php

use yii\db\Migration;

/**
 * Class m180529_063719_whc_type_profile
 */
class m180529_063719_whc_type_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_user_profile', '{{%profile}}');
        $this->alterColumn('{{%profile}}', 'user_id', $this->integer()->notNull());
        $this->dropPrimaryKey('user_id' , '{{%profile}}');

        $this->addColumn('{{%profile}}', 'id', $this->primaryKey()->first());
        $this->addColumn('{{%profile}}', 'created_user_id', $this->integer());
        $this->addColumn('{{%profile}}', 'modified_user_id', $this->integer());
        $this->addColumn('{{%profile}}', 'created_time', $this->timestamp());
        $this->addColumn('{{%profile}}', 'modified_time', $this->timestamp());
        $this->addColumn('{{%profile}}', 'deleted_time', $this->timestamp());
        $this->addForeignKey(
            'fk-created_user_id-profile',
            '{{%profile}}',
            'created_user_id',
            '{{%profile}}',
            'id'
        );
        $this->addForeignKey(
            'fk-modified_user_id-profile',
            '{{%profile}}',
            'modified_user_id',
            '{{%profile}}',
            'id'
        );
        $this->addForeignKey(
            'fk_profile_copy_user_id',
            '{{%profile}}',
            'user_id',
            '{{%user}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180529_063719_whc_type_profile cannot be reverted.\n";

        return false;
    }
    */
}
