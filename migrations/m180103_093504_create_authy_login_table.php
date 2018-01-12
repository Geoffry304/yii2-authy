<?php

use yii\db\Migration;

/**
 * Handles the creation of table `authy_login`.
 * Has foreign keys to the tables:
 *
 * - `authy`
 */
class m180103_093504_create_authy_login_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('authy_login', [
            'id' => $this->primaryKey(),
            'authyid' => $this->integer()->notNull(),
            'ip' => $this->string(255)->notNull(),
            'expire_at' => $this->timestamp()->notNull(),
			'hostname' => $this->string()
        ]);

        // creates index for column `authyid`
        $this->createIndex(
            'idx-authy_login-authyid',
            'authy_login',
            'authyid'
        );

        // add foreign key for table `authy`
        $this->addForeignKey(
            'fk-authy_login-authyid',
            'authy_login',
            'authyid',
            'authy',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `authy`
        $this->dropForeignKey(
            'fk-authy_login-authyid',
            'authy_login'
        );

        // drops index for column `authyid`
        $this->dropIndex(
            'idx-authy_login-authyid',
            'authy_login'
        );

        $this->dropTable('authy_login');
    }
}
