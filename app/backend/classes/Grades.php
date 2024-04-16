<?php

class Grades
{
    public static function create($fields = array())
    {
        if (!Database::getInstance()->insert('grades', $fields)) {
            throw new Exception("Unable to create the grade.");
        }
    }

    public static function getGradesByUserid($user_id)
    {
        $db = Database::getInstance();
        $query = "SELECT grade_id, grade, room, student, release_time FROM grades WHERE student = ?";
        $grades = $db->query($query, array($user_id));
        if ($grades->count()) {
            return $grades->results();
        }
        return false;
    }
}