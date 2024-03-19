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

    public static function getFileTypeIdByFileId($fileId)
    {
        $file = self::getFileById($fileId);
        if ($file) {
            return $file->file_type_id;
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
}