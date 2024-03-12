<?php

class Rooms
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

    public static function getSectionById($section_id)
    {
        $section = Database::getInstance()->get('sections', array('section_id', '=', $section_id));
        if ($section->count()) {
            return $section->first();
        }
    }

    public static function deleteFile($file_id)
    {
        if (!Database::getInstance()->delete('files', array('file_id', '=', $file_id))) {
            throw new Exception("Unable to delete the file.");
        }
    }
}
?>

