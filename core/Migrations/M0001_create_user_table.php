<?php

class M0001_create_user_table {

    public function up()
    {
        return "CREATE TABLE users (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(190) NULL,
            user_name VARCHAR(190) NULL,
            password VARCHAR(190) NULL,
            can_login TINYINT(1) DEFAULT 0,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    }

    public function down()
    {
        return "DROP TABLE IF EXISTS users;";
    }
    
}