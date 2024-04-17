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
            Session::flash('register-error', "Sorry, there was an error uploading your file.");
        } else {
            // If assignment_id is provided, create an assignment submission
            if (isset($_POST['assignment_id'])) {
                $submissionResult = Database::getInstance()->insert('assignment_submissions', [
                    'assignment' => $_POST['assignment_id'],
                    'user' => $_POST['user_id'],
                    'file' => $result,
                    'timestamp' => date('Y-m-d H:i:s'), // current date and time
                ]);

                if (!$submissionResult) {
                    Session::flash('register-error', "There was an error submitting the assignment.");
                } else {
                    Session::flash('update-success', "Assignment submitted successfully.");
                }
                header('Location: assignment.php?assignment_id=' . $_POST['assignment_id']); // Redirect to the assignment page
                exit;
            }
            // If user_id is provided, update the user record
            elseif (isset($_POST['user_id'])) {
                $userResult = Database::getInstance()->update('users', 'user_id', $_POST['user_id'], [
                    'profile_picture' => $result,
                ]);

                if (!$userResult) {
                    Session::flash('register-error', "There was an error updating the user.");
                } else {
                    Session::flash('update-success', "User updated successfully.");
                }
                header('Location: profile.php?id=' . $_POST['user_id']); // Redirect to the user page
                exit;
            }
            // If section_id is provided, create a post
            elseif (isset($_POST['section_id'])) {
                $postResult = Database::getInstance()->insert('posts', [
                    'section_id' => $_POST['section_id'],
                    'post_type' => 11,
                    'specific_post_id' => $result,
                    'sort' => 0,
                ]);

                if (!$postResult) {
                    Session::flash('register-error', "There was an error creating the post.");
                } else {
                    Session::flash('create-post-success', "Post created successfully.");
                }
                header('Location: section.php?id=' . $_POST['section_id']); // Redirect to the section page
                exit;
            }
        }
    } else {
        Session::flash('register-error', "There was an error inserting the file record into the database.");
    }
} catch (Exception $e) {
    Session::flash('register-error', 'Error with Database operation: ' . $e->getMessage());
}

header('Location: ' . (isset($_POST['assignment_id']) ? 'assignment.php?assignment_id=' . $_POST['assignment_id'] : (isset($_POST['user_id']) ? 'profile.php?id=' . $_POST['user_id'] : 'section.php?id=' . $_POST['section_id'])));
exit;