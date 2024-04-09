<?php

class Files
{
    public static function create($data)
    {
        // Check if the file_type exists in the file_types table
        $fileTypeId = self::getFileTypeByFileTypeId($data['file_type']);

        // Replace the file_type with the file_type_id in the data array
        $data['file_type'] = $fileTypeId;

        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the files table and get the last inserted ID
        $lastInsertedId = $database->insert('files', $data);

        // Return the ID of the last inserted row
        return $lastInsertedId;
    }

    public static function createNote($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the notes table and get the last inserted ID
        $noteData = [
            'title' => $data['title'],
            'text' => $data['text'],
        ];
        $noteId = $database->insert('notes', $noteData);

        // Get the highest sort number in the posts table for the given section_id
        $highestSortNumber = Files::getHighestSortNumber($data['section_id']);

        // Create a post in the posts table
        $postData = [
            'section_id' => $data['section_id'],
            'post_type' => 10, // For notes, post_type will always be 10
            'specific_post_id' => $noteId,
            'sort' => $highestSortNumber + 1,
        ];
        $database->insert('posts', $postData);

        // Return the ID of the created note
        return $noteId;
    }

    public static function createFile($fileContent, $fileType)
    {
        $database = Database::getInstance();

        // Create a new file in the files table
        $fileData = [
            'content' => $fileContent,
            'type' => $fileType,
        ];
        $fileId = $database->insert('files', $fileData);

        // Get the highest sort number in the posts table for the given section_id
        $highestSortNumber = Files::getHighestSortNumber(1); // You'll need to provide a default section ID

        // Create a post in the posts table
        $postData = [
            'section_id' => 1, // For files, section_id will always be 1
            'post_type' => 11, // For files, post_type will always be 11
            'specific_post_id' => $fileId,
            'sort' => $highestSortNumber + 1,
        ];
        $database->insert('posts', $postData);

        // Return the ID of the created file
        return $fileId;
    }

    public static function getHighestSortNumber($sectionId)
    {
        $database = Database::getInstance();
        $database->query("SELECT MAX(sort) AS max_sort FROM posts WHERE section_id = ?", [$sectionId]);
        $results = $database->results();
        return $results[0]->max_sort;
    }

    public static function deleteFile($file_id)
    {
        if (!Database::getInstance()->delete('files', array('file_id', '=', $file_id))) {
            throw new Exception("Unable to delete the file.");
        }
    }

    public static function getFileTypeByFileTypeId($extension)
    {
        $fileTypes = self::getAllFileTypes();
        foreach ($fileTypes as $fileType) {
            $extensions = explode(',', $fileType->extension);
            if (in_array($extension, $extensions)) {
                return $fileType->file_type_id;
            }
        }

        // If file type not found for the given extension, return the ID of the "other" file type
        foreach ($fileTypes as $fileType) {
            if ($fileType->extension == 'other') {
                return $fileType->file_type_id;
            }
        }

        throw new Exception("File type not found for extension: $extension");
    }

    public static function getFileById($fileId)
    {
        $file = Database::getInstance()->get('files', array('file_id', '=', $fileId));
        if ($file->count()) {
            return $file->first();
        }
        throw new Exception("File not found for file ID: $fileId");
    }

    public static function getFilePathById($fileId)
    {
        // the file can be found in uploads/ directory, and it has the name of the id of the file. The extension for the file, will be the same as in it's name
        $file = self::getFileById($fileId);

        // get the extension of the file by it's name
        $extension = explode('.', $file->name);
        $extension = end($extension);

        // return the absolute path to the file
        return '../uploads/' . $fileId . '.' . $extension;
    }

    public static function getFileTypeIdByFileId($fileId)
    {
        $file = self::getFileById($fileId);
        if ($file) {
            return $file->file_type;
        }
        throw new Exception("File not found for file ID: $fileId");
    }

    public static function getAllFiles()
    {
        $files = Database::getInstance()->get('files', array('file_id', '>', '0'));
        //return list of files
        return $files->results();
    }

    public static function getAllFileTypes()
    {
        $fileTypes = Database::getInstance()->get('file_types', array('file_type_id', '>', '0'));
        //return list of file types
        return $fileTypes->results();
    }

    public static function isFileImage($fileId)
    {
        $fileTypeId = self::getFileTypeIdByFileId($fileId);
        $fileTypes = self::getAllFileTypes();
        foreach ($fileTypes as $fileType) {
            if ($fileType->file_type_id == $fileTypeId) {
                $extensions = explode(',', $fileType->extension);
                if (in_array('jpg', $extensions) || in_array('jpeg', $extensions) || in_array('png', $extensions) || in_array('gif', $extensions)) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function fileHasPDF($fileId)
    {
        // check pdf_link table for if the file has a pdf link
        $pdfLink = Database::getInstance()->get('pdf_link', array('original', '=', $fileId));
        if ($pdfLink->count()) {
            return true;
        }
    }

    public static function getLinkedPDF($fileId)
    {
        // get the pdf link for the file
        $pdfLink = Database::getInstance()->get('pdf_link', array('original', '=', $fileId));
        if ($pdfLink->count()) {
            return $pdfLink->first()->pdf;
        }
    }
}
