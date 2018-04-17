<?php

use yii\db\Migration;

/**
 * Class m180417_121921_create_admin_account
 */
class m180417_121921_create_admin_account extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%user}}',
            ['username', 'role', 'auth_key', 'password_hash', 'email', 'status', 'created_at', 'updated_at'],
            [['admin', '1', 'ZAEw2FnapHMbJGIeQLSmJ3EUAZihMMFR', '$2y$13$kemcBW3sa2J6jSnIIntMnecsuSwDF9ZVYjc/y6P94gfb2l26pPjc2', 'admin@127.0.0.1', '10', '1523970390', '1523970390']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180417_121921_create_admin_account cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180417_121921_create_admin_account cannot be reverted.\n";

        return false;
    }
    */
}
