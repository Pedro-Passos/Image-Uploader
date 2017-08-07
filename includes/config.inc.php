<?php
/*
 * Building Web Applications using MySQL and PHP
 * Pedro Dos Passos
 * -----------------------------------------------------------------------------
 */
// Database settings
$config['db_host'] = 'localhost:3306';
$config['db_name'] = 'pandra02db';
$config['db_user'] = 'root';
$config['db_pass'] = '';

//Absolute path to application root directory (one level above current dir)
$config['app_dir'] = dirname(__DIR__);

//Absolute path to application root directory
$config['main_dir'] = __DIR__;

//Absolute path to directory where uploaded files will be stored
$config['original_dir'] = $config['app_dir'].'/images/original/';
$config['large_dir'] = $config['app_dir'].'/images/large/';
$config['thumbs_dir'] = $config['app_dir'].'/images/thumbnail/';

?>
