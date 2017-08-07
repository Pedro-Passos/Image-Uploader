CREATE TABLE IF NOT EXISTS img_control (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(30) NOT NULL,
    file_name VARCHAR(30) NOT NULL,
    file_title VARCHAR(30) NOT NULL,
    file_description VARCHAR(255) NOT NULL,
    file_l_name VARCHAR(30) NOT NULL,
    file_l_width INT(5) NOT NULL,
    file_l_height INT(5) NOT NULL);