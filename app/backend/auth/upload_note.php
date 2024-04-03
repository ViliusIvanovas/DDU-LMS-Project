<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                header("Location: section.php?section_id=$sectionId"); // Redirect to section.php with the section id
                exit();
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