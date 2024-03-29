<?php
$target_dir = "../uploads/";

try {
    $result = Files::create([
        'name' => $_FILES["fileToUpload"]["name"],
        'file_type' => pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION),
    ]);

    if ($result) {
        $target_file = $target_dir . $result . '.' . pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);

        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    } else {
        $_SESSION['error'] = "There was an error inserting the file record into the database.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Error with Database operation: ' . $e->getMessage();
}

Redirect::to($_POST['return_page']);
exit;