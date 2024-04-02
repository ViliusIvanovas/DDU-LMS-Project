<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        var_dump($_POST); // Add this line to print the POST data
        $noteContent = $_POST['note'];
        $noteTitle = $_POST['title'];
        $sectionId = $_POST['section_id'];

        if (!empty($noteContent)) {
            $data = array(
                'title' => $noteTitle,
                'text' => $noteContent,
                'section_id' => $sectionId,
            );

            try {
                $lastInsertedId = Files::createNote($data);
                echo 'Note uploaded successfully. ID: ' . $lastInsertedId;
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        } else {
            echo 'Error: The note content was not provided.';
        }
    } else {
        echo 'Error: The request method must be POST.';
    }
?>