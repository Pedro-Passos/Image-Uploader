<?php

$link = mysqli_connect(
        'host',
        'username',
        'password',
        'database name');
// If the connection fails we stop all processing using "exit".
if(mysqli_connect_errno()) {
    exit("Error connecting to database.");
}

// Send appropriate header.
header('Content-type: application/json');

// Generate sql based on query string parameter.
if ($_GET['type'] == 'lrg_imgs') {
    // Creating our query and inserting it into $sql.
    $sql = "SELECT
            file_title, file_description, file_l_name, file_l_width, file_l_height
            FROM
                img_control";
}else {
    // Incase there is an invalid parameter passed to the application.
    echo "Invalid parameter";
}

if(isset($sql)) {
    // Execute the query, assigning the result to $result.
    $result = mysqli_query($link, $sql);
    // If the query failed, $result will be "false", so we test for this, and exit if it is.
    if($result === false) {
        exit("Error retreiving records from database.");
    }
    // Check if the query returned anything.
    if (mysqli_num_rows($result) == 0) {
        exit("No results to display.");
    }else {
        // Make result into array of JSON objects.
        $structure = array();
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($structure, json_encode($row));
        }
        // Check for errors.
        if (json_last_error() == JSON_ERROR_NONE) {
            // No errors occurred, so echo json objects.
            foreach ($structure as $json) {
                echo $json.PHP_EOL;
            }
        }else {
            // Errors encountered.
            echo 'There was a problem with JSON please contact an administrator at admin@web.com';
            echo 'CODE: ' . json_last_error();
        }
    }
}

?>