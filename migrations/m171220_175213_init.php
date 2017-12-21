<?php

use yii\db\Migration;

/**
 * Class m171220_175213_init
 */
class m171220_175213_init extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->execute("
            CREATE TABLE app_uploaded_file
            (
                id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                file_name VARCHAR(255) NOT NULL,
                file_extension VARCHAR(3) NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
            );
        ");

        $this->execute("
            CREATE TABLE app_file_usage_stat
            (
                id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                uploaded_file_id INT NOT NULL,
                usage_date DATETIME NOT NULL,
                event_type VARCHAR(20) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                CONSTRAINT app_data_uploaded_file_id_fk FOREIGN KEY (uploaded_file_id) REFERENCES app_uploaded_file (id) ON DELETE CASCADE ON UPDATE CASCADE
            );
        ");

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->execute("DROP TABLE test_application.app_file_usage_stat;");
        $this->execute("DROP TABLE test_application.app_uploaded_file;");

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171220_175213_init cannot be reverted.\n";

        return false;
    }
    */
}
