<?php
/*
 * Building Web Applications using MySQL and PHP FMA
 * Pedro Dos Passos
 * -----------------------------------------------------------------------------
 */

// Select files to be included
require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/functions.inc.php';
require_once __DIR__.'/includes/db_connect.php';
require_once __DIR__.'/sql/queries.php';

// Select templates we will be using.
include_once $config['app_dir'].'/templates/head.html'; // Include the HTML head
$template = __DIR__.'/templates/lrg_template.html';
$tpl = file_get_contents($template);

// We retreive the filename from the url and sanitize it.
$img_id = filter_input(INPUT_GET,'pageid', FILTER_SANITIZE_STRING);
// Escaping before querying the database.
mysqli_real_escape_string($link, $sqlGal);
// Inserting results into the $result variable.
$result = mysqli_query($link, $sqlGal);
// Passing the $result along with the image name and template to our lrg_template() function and inserting into $content variable. 
$content= lrg_template($result, $img_id, $tpl);
// Close link.
mysqli_close($link);
// Output our completed page.
echo $content;
?>