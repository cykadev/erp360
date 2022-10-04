<?php

class M0002_create_user_token_table {

    public function up()
    {
        return "CREATE TABLE user_tokens (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) UNSIGNED NOT NULL,
            token VARCHAR(190) NOT NULL,
            mode VARCHAR(20) NOT NULL DEFAULT 'FORGOT_PASSWORD',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        
        ALTER TABLE user_tokens
            ADD CONSTRAINT fk_ut_cascade FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;
        ";
    }

    public function down()
    {
        return "DROP TABLE IF EXISTS user_tokens;";
    }
    
}