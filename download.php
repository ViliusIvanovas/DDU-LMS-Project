<?php
require_once 'start.php'; 
require_once BACKEND_CLASSES . 'Files.php'; 

// Get the file ID from the query parameter
$file_id = $_GET['id'];

// Fetch the file from the database
$file = Files::getFileById($file_id);

// Set the headers
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file->name).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize(Files::getFilePathById($file_id)));

// Clear output buffer
ob_clean();

// Flush output buffer
flush();

// Read the file and send it to the output
readfile(Files::getFilePathById($file_id));

exit;
?>