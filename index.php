<?php
/*
 * Building Web Applications using MySQL and PHP
 * Pedro Dos Passos
 * -----------------------------------------------------------------------------
 */

// Select files to be included.
require_once __DIR__.'/includes/config.inc.php';
require_once __DIR__.'/includes/functions.inc.php';
require_once __DIR__.'/includes/db_connect.php';
require_once __DIR__.'/sql/queries.php';

// Select templates to be included.
$head = $config['app_dir'].'/templates/head.html';
$form = $config['app_dir'].'/templates/form.html';
$foot = $config['app_dir'].'/templates/footer.html';
// Inserting templates into variables for later use.
$indexpage = file_get_contents($head); // The HTML head template.
$indexpage.= file_get_contents($form); // The HTML form template.
$footer = file_get_contents($foot); // The HTML footer template.
//Code for creating tables in the database.
$creator = mysqli_query($link, $sqlCreate);
if(!$creator) {
    exit('Could not create table: ' . mysql_error());
}else {
    echo "Table created successfully\n";
}


// Initialising some variables for later use.
$uploaded = 1;
$error_count = 0;
$error_msg = array();

// Checks if form has been submitted
if (isset($_POST['submit'])) {
    // $pic is used later to pass the location of the original uploaded file to be resized.
    $pic = ($config['original_dir'].$_FILES['userimage']['name']);
    
    // Check to see if an image has been selected.
    if (is_uploaded_file($_FILES['userimage']['tmp_name'])) {
        $name =  mysqli_real_escape_string($link, $_FILES['userimage']['name']);
        $lName = 'lrg_'.$_FILES['userimage']['name'];
    }else {
        $error_count++;
        $error_msg[] = "Please select a file to submit!";
    }
    
    // Check to see if a title has been submitted and meets our validation criteria.
    if ((!empty($_POST['userTitle'])) AND (ctype_alnum(str_replace(' ', '', $_POST['userTitle']))) AND (strlen($_POST['userTitle']) > 3)){
        $trim = strip_tags(trim($_POST['userTitle']));
        $title = mysqli_real_escape_string($link, $trim);
    }else {
        $error_count++;
        $error_msg[] = "A title is required and should contain atleast 4 characters (letters, numbers and spaces only).";
    }
    
    // Check to see if a description has been submitted and meets our validation criteria.
    if ((!empty($_POST['userTextbox'])) AND (strlen($_POST['userTextbox']) > 5)){
        $trim = strip_tags(trim($_POST['userTextbox']));
        $description =  mysqli_real_escape_string($link, $trim);
    }else{
        $error_count++;
        $error_msg[] = "A description is required and should contain atleast 5 characters.";
    }
    
    // Checking to see if form has been successfully submitted without errors then proceed with uploading, resizing and submitting data to the db.
    if ($error_count === 0) {
        // Passes the uploaded file to the processUpload() function to test for validity and move it to it's new location.
        $uploaded = processUpload($_FILES['userimage']);
        // If processUpload() is successful we can then proceed to resize it and insert data into the db.
        if($uploaded === 0) {
            // If upload of the image is successful the image is then resized into a large and small version
            img_resize($pic, $config['thumbs_dir'].'small_'.$name, 150, 150, 90);
            img_resize($pic, $config['large_dir'].'lrg_'.$name, 600, 600, 90);
            // Retreiving the image dimensions of the large version of the image to insert into the db.
            list($width, $height) = getimagesize($config['large_dir'].'lrg_'.$name);
            // Injecting the necessary escaped data into the db.
            $inject = inject_data($link, $pic, $name, $title, $description, $lName, $width, $height);
            // If all has gone well the user will receive a success notification.
            if($inject === FALSE) {
                exit('There was an error inserting data into the database' . mysql_error());
            }else {
                echo 'File upload Successful!';
            }
        }else {
            // If processUpload() does not equal 0 we echo the error message instead.
            echo $uploaded;
        }
    }else{
        // Loop for cycling through the error messages from incorrectly submitted form.
        foreach ($error_msg as $error) { // Lists all the errors when form is submitted
            echo "<p>$error</p>";
        }
    }
}

// Variable with the head template and form.
echo $indexpage;

// Query the database.
$result = mysqli_query($link, $sqlGal);
// Checking if query was successful
if (isset($result)) {
    // Passing the data to our gallery function.
    gallery($link, $result);
} else {
    echo 'There was an error accessing our database';
}

// Include the HTML footer
echo $footer;
?>