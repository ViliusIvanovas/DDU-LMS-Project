<?php

class Grades
{
    public static function create($fields = array())
    {
        if (!Database::getInstance()->insert('grades', $fields)) {
            throw new Exception("Unable to create the grade.");
        }
    }
}