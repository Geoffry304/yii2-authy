<?php

use yii\db\Migration;

/**
 * Handles the creation of table `authy`.
 */
class m180103_093141_create_authy_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('authy', [
            'id' => $this->primaryKey(),
            'userid' => $this->integer()->notNull(),
            'authyid' => $this->integer()->notNull(),
            'cellphone' => $this->string()->notNull(),
            'countrycode' => $this->integer(2)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('authy');
    }
}
