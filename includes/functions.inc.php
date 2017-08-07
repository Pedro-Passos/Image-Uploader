<?php
/*
 * Building Web Applications using MySQL and PHP
 * Pedro Dos Passos
 * -----------------------------------------------------------------------------
 */

//Function to return the mime type of an uploaded file
function getMimeType($file) {
    $file_info = new finfo(FILEINFO_MIME);  // object oriented approach!
    $mime_type = $file_info->buffer(file_get_contents($file));  // e.g. gives "image/jpeg"
    return $mime_type;
}

// Function to process a file upload.
function processUpload($file) {
    // Get the error code
    $error = $file['error'];
    if ($error == UPLOAD_ERR_OK) {
        // Get the mime type
        $mime_type = getMimeType($_FILES['userimage']['tmp_name']);
        if (strpos($mime_type, "image/jpeg") !== false) {
            // Defining variables for use within the function such as filepaths.
            $updir = dirname(__DIR__).'/images/original/';
            $upfilename = basename($file['name']);
            $tmpname = $file['tmp_name'];
            $newname = $updir.$upfilename;
            
            // Check file doesn't already exist
            if (file_exists($newname)) {
                $uploaded = "The file $upfilename already exists! Please rename your file and try again.";
            } else {
                // Moving the file to it's new location.
                if (move_uploaded_file($tmpname, $newname)) {
                    $uploaded = 0;
                } else {
                    $uploaded = 'File upload failed';
                }
            }
        } else {
            $uploaded = 'File type not permitted. Please upload a JPEG image file.';
        }
    // Checks if the file exceeds the maximum permitted size and displays a notification.
    } else if ($error == UPLOAD_ERR_INI_SIZE) {
        $uploaded = 'Maximum file size exceeded!';
    // If any other unacounted for error occurs user still receives an error notification.
    } else {
        $uploaded = 'Oops. Something went wrong.';
    }
    return $uploaded;
}


// Function for injecting data into the database.
function inject_data($link, $pic, $name, $title, $description, $lName, $width, $height) {
    if (!$link) {
         exit('Sorry, there has been an error. Please try again later.');
    }else {
        mysqli_query($link, "INSERT INTO img_control (file_path, file_name, file_title, file_description, file_l_name, file_l_width, file_l_height)
                            VALUES ('$pic', '$name', '$title', '$description', '$lName', '$width', '$height')");
    }
}

// Function for resizing images, and is used to create both the small and large version from the original image.
function img_resize($in_img_file, $out_img_file, $req_width, $req_height, $quality) {

    // Get image file details.
    list($width, $height, $type, $attr) = getimagesize($in_img_file);

    // Open file according to file type.
    if (isset($type)) {
        switch ($type) {
            case IMAGETYPE_JPEG:
                $src = @imagecreatefromjpeg($in_img_file);
                break;
            default:
                $error = 1;
                return $error;
        }
        
        // Check if image is smaller (in both directions) than required image.
        if ($width < $req_width and $height < $req_height) {
            // Use original image dimensions.
            $new_width = $width;
            $new_height = $height;
        }else {
            // Test orientation of image and set new dimensions appropriately.
            // Makes sure largest dimension never exceeds the target thumb size.
            if ($width > $height) {
                // landscape
                $sf = $req_width / $width;
            }else {
                // portrait                 
                $sf = $req_height / $height;
            }
            $new_width = round($width * $sf);
            $new_height = round($height * $sf);
        }

        // Creates a new canvas ready for the resampled image to be inserted into it.
        $new = imagecreatetruecolor($new_width, $new_height);

        // Resample input image into newly created image.
        imagecopyresampled($new, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        // Create output jpeg.
        imagejpeg($new, $out_img_file, $quality);
        // Destroy any intermediate image files.
        imagedestroy($src);
        imagedestroy($new);
    }else {
        $error = 1;
        return $error;
    }
}

// This function generates the gallery after querying data from the database.
function gallery($link, $result){ 
    // Verify connection, if true then generate gallery. 
    if ($result === FALSE) {
        echo "Sorry, there has been an error. Please try again later.";
    }
    echo "<h2>Image Gallery</h2>";
    if (mysqli_num_rows($result) == 0) {
        exit("No results to display."); // If the database doesn't have anything to ouput this message is displayed.
    }else {
        // Loops through the results from the database search and displays the title along with the respective image.
        while ($row = mysqli_fetch_assoc($result)) {
            // Making sure data is safe before output and then displaying to the user.
            $fileTitle = htmlentities($row['file_title'], ENT_QUOTES, 'UTF-8');
            $fileName = htmlentities($row['file_name'], ENT_QUOTES, 'UTF-8');
            echo "\n\t"."<h2>".$fileTitle."</h2>".PHP_EOL;
            echo "\t<a href='img_view.php?pageid=".$fileName."'><img src='images/thumbnail/small_".$fileName."' alt='".$fileTitle."'/></a>".PHP_EOL;
        }
        mysqli_free_result($result); // Finished with the result and clear it from memory.
    }
    mysqli_close($link); // Close link
}

// This function is responsible for querying the databse for a matching file name.
function lrg_template ($result, $img_id, $tpl){
    $large ='';
    if ($result === FALSE) {
        echo "Sorry, there has been an error. Please try again later.";
    } else {
        // Once a match has been found we then replace the placeholder values from the lrg_template.html and insert it into the $large variable.
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['file_name'] == $img_id) {
                $pass1 = str_replace('[+title+]',htmlentities($row['file_title'], ENT_QUOTES, 'UTF-8'),$tpl);
                $pass2 = str_replace('[+image+]','images/large/lrg_'.htmlentities($row['file_name'], ENT_QUOTES, 'UTF-8'),$pass1);
                $pass3 = str_replace('[+fileTitle+]', htmlentities($row['file_name'], ENT_QUOTES, 'UTF-8'),$pass2);
                $final = str_replace('[+description+]',htmlentities($row['file_description'], ENT_QUOTES, 'UTF-8'),$pass3);
                $large.= $final;
                }
        }
    }
    mysqli_free_result($result); // Finished with the result and clear it from memory.
    return $large; // Returns the completed output ready to be displayed.
}
?>
