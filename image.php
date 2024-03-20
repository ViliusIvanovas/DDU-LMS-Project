<?php 
require_once 'start.php'; 
require_once BACKEND_CLASSES . 'Files.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // get the file path
    $file_path = Files::getFilePathById($id);

    // check if the file exists
    if (file_exists($file_path)) {
        // get the file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_path);
        finfo_close($finfo);

        // output the file
        header('Content-Type: ' . $mime_type);
        readfile($file_path);
    } else {
        echo "File does not exist: $file_path";
    }
}