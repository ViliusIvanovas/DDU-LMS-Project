<?php
$target_dir = "../uploads/"; // replace with your actual target directory

// Print out information about the file
var_dump($_FILES["fileToUpload"]);

// Insert the file record into the database
try {
    $result = Files::create([
        'name' => $_FILES["fileToUpload"]["name"],
        'file_type' => pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION),
    ]);

    // Print out the result of the database operation
    var_dump($result);

    if ($result) {
        // Use the ID from the database to name the file
        $target_file = $target_dir . $result . '.' . pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $_SESSION['success'] = "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error'] = "There was an error inserting the file record into the database.";
    }
} catch (Exception $e) {
    // Print out the exception
    var_dump($e);
    $_SESSION['error'] = 'Error with Database operation: ' . $e->getMessage();
}

// Redirect back to the previous page
//Redirect::to($_POST['return_page']);
//exit;
