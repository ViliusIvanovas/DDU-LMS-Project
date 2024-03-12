<?php

class Files
{
    public static function create($fields = array())
    {
        if (!Database::getInstance()->insert('files', $fields)) {
            throw new Exception("Unable to create the file.");
        }
    }

    public static function getAllFiles()
    {
        $files = Database::getInstance()->get('files', array('file_id', '>', '0'));
        //return list of files
        return $files;
    }

    public static function getFileById($file_id)
    {
        $file = Database::getInstance()->get('files', array('file_id', '=', $file_id));
        if ($file->count()) {
            return $file->first();
        }
    }

    public static function deleteFile($file_id)
    {
        if (!Database::getInstance()->delete('files', array('file_id', '=', $file_id))) {
            throw new Exception("Unable to delete the file.");
        }
    }

    public static function getFileTypeId($fileType)
    {
        $fileTypeId = '';

        // call the database to get the different file types, these lay in file_types and have file_type_id, text, file_extension
        $filetypes = Files::getAllFileTypes();
        foreach ($filetypes as $filetype) {
            echo $filetype->file_extension;
            if ($fileType == $filetype->file_extension) {
                $fileTypeId = $filetype->file_type_id;
                break; // exit the loop once the file type is found
            }
        }
    
        return $fileTypeId;
    }

    public static function getFileTypeById($fileTypeId)
    {
        $fileType = Database::getInstance()->get('file_types', array('file_type_id', '=', $fileTypeId));
        if ($fileType->count()) {
            return $fileType->first();
        }
    }

    public static function getAllFileTypes()
    {
        $fileTypes = Database::getInstance()->get('file_types', array('file_type_id', '>', '0'));
        //return list of file types
        return $fileTypes;
    }
}
