<?php
/*
 * Building Web Applications using MySQL and PHP
 * Pedro Dos Passos 
 * -----------------------------------------------------------------------------
 */

$sqlCreate = "CREATE TABLE IF NOT EXISTS img_control (
                id INT(6) AUTO_INCREMENT PRIMARY KEY,
                file_path VARCHAR(100) NOT NULL,
                file_name VARCHAR(30) NOT NULL,
                file_title VARCHAR(50) NOT NULL,
                file_description VARCHAR(255) NOT NULL,
                file_l_name VARCHAR(30) NOT NULL,
                file_l_width INT(5) NOT NULL,
                file_l_height INT(5) NOT NULL
            )";

$sqlThumb = "SELECT
            file_path, file_name
            FROM
                img_control";


$sqlGal = "SELECT
            file_name, file_title, file_description
            FROM
                img_control";


$sqlFile = "SELECT
            file_name, file_title, file_description
            FROM
                img_control";

?>
