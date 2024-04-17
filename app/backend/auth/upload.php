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
            // Redirect::to($_POST['return_page']);
            echo "Error: " . $_SESSION['error'];
        } else {
            // If section_id is provided, create a post
            if (isset($_POST['section_id'])) {
                $postResult = Database::getInstance()->insert('posts', [
                    'section_id' => $_POST['section_id'],
                    'post_type' => 11, // Assuming '4' is the post_type for 'File'
                    'specific_post_id' => $result,
                    'sort' => 0, // Assuming 'sort' is a required field and setting it to '0'
                ]);

                if (!$postResult) {
                    error_log("Post creation failed with data: " . print_r([
                        'section_id' => $_POST['section_id'],
                        'post_type' => 11,
                        'specific_post_id' => $result,
                        'sort' => 0,
                    ], true));
                    $_SESSION['error'] = "There was an error creating the post.";
                    // Redirect::to($_POST['return_page']);
                    echo "Error: " . $_SESSION['error'];
                } else {
                    echo "Post created successfully.";
                }
            }

            // Redirect::to('section.php?section_id=' . $_POST['section_id']);
        }
    } else {
        $_SESSION['error'] = "There was an error inserting the file record into the database.";
        // Redirect::to($_POST['return_page']);
        echo "Error: " . $_SESSION['error'];
    }
} catch (Exception $e) {
    error_log('Exception caught during file upload: ' . $e->getMessage());
    $_SESSION['error'] = 'Error with Database operation: ' . $e->getMessage();
    // Redirect::to($_POST['return_page']);
    echo "Exception: " . $_SESSION['error'];
}

exit;