<?php

require_once 'start.php';
require_once BACKEND_CLASSES . 'Files.php';

$target_dir = "../uploads/";
$uploadOk = 1;

// Generate a unique file_id
$file_id = uniqid();

// Use the file_id to name the file
$target_file = $target_dir . $file_id;

$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));

// Debugging: output the file extension
var_dump($imageFileType);

// Get the file type ID based on the file extension
$fileTypeId = Files::getFileTypeId($imageFileType);

// Debugging: output the file type ID
var_dump($fileTypeId);

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50000000) {
    $_SESSION['alert'] = "Sorry, your file is too large.";
    $uploadOk = 0;
}

// call the database to get the different file types, these lay in file_types and have file_type_id, text, file_extension
$filetypes = Files::getAllFileTypes();

// Debugging: output the file types
var_dump($filetypes);

foreach ($filetypes as $filetype) {
    $extensions = explode(',', $filetype->extension);
    if (in_array($imageFileType, $extensions)) {
        $fileTypeId = $filetype->file_type_id;
        break; // exit the loop once the file type is found
    }
}

// Debugging: output the file type ID after the loop
var_dump($fileTypeId);

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $_SESSION['alert'] = "Sorry, your file was not uploaded.";
} else {
    $fileType = Files::getFileTypeById($fileTypeId);
    if ($fileType !== null) {
        // Pass the necessary parameters to the Files::create method
        Files::create([
            'file_id' => $file_id,
            'name' => $_FILES["fileToUpload"]["name"],
            'file_type_id' => $fileType->file_type_id,
        ]);
        $_SESSION['success'] = "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        // Redirect back to the previous page
        Redirect::to($_POST['return_page']);
        exit;
    } else {
        $_SESSION['alert'] = "Sorry, there was an error uploading your file.";
    }
}