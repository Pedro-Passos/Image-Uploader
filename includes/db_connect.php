<?php
/*
 * Building Web Applications using MySQL and PHP
 * Pedro Dos Passos
 * -----------------------------------------------------------------------------
 */
// Connect to mysql server
$link = mysqli_connect(
    $config['db_host'],
    $config['db_user'],
    $config['db_pass'],
    $config['db_name']
);

// Check connection succeeded
if (!$link) {
    echo '<p>There has been an error with the database please contact an administrator at admin@webadmin.com with the following error message:</p>';
    exit(mysqli_connect_error());
}

?>
