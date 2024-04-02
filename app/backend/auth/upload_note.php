<?php
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the 'note' and 'title' fields are set
        if (isset($_POST['note']) && isset($_POST['title'])) {
            // Get the note content and title
            $noteContent = $_POST['note'];
            $noteTitle = $_POST['title'];

            // Prepare the data array
            $data = array(
                'title' => $noteTitle,
                'text' => $noteContent,
                'file_type' => 'note'
            );

            // Use the Files class to create a new note
            try {
                $lastInsertedId = Files::createNote($data);
                echo 'Note uploaded successfully. ID: ' . $lastInsertedId;
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            // Send an error response
            echo 'Error: The note content and title were not provided.';
        }
    } else {
        // Send an error response
        echo 'Error: The request method must be POST.';
    }
?>