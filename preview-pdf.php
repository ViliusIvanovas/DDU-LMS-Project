<?php
require_once 'start.php';
require_once BACKEND_CLASSES . 'Files.php';

echo "Debug: Starting script<br>";

if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];
    echo "Debug: file_id is set to $id<br>";

    // get the file path
    $file_system_path = Files::getFilePathById($id);
    echo "Debug: file_system_path is $file_system_path<br>";

    // check if the file exists
    if (file_exists($file_system_path)) {
        echo "Debug: File exists<br>";
        // get the file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_system_path);
        finfo_close($finfo);
        echo "Debug: mime_type is $mime_type<br>";

        // output the file
        if ($mime_type == 'application/pdf') {
            // Send the headers
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($file_system_path) . '"');
            header('Content-Length: ' . filesize($file_system_path));

            // Output the PDF content
            readfile($file_system_path);
        } else {
            header('Content-Type: ' . $mime_type);
            readfile($file_system_path);
        }
    } else {
        echo "File does not exist: $file_system_path";
    }
} else {
    echo "Debug: file_id is not set<br>";
}