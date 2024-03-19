<?php

class Classes
{
    public static function create($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the classes table and get the last inserted ID
        $lastInsertedId = $database->insert('classes', $data);

        // Return the ID of the last inserted row
        return $lastInsertedId;
    }

    public static function deleteClass($class_id)
    {
        if (!Database::getInstance()->delete('classes', array('class_id', '=', $class_id))) {
            throw new Exception("Unable to delete the class.");
        }
    }

    public static function getClassById($classId)
    {
        $class = Database::getInstance()->get('classes', array('class_id', '=', $classId));
        if ($class->count()) {
            return $class->first();
        }
        throw new Exception("Class not found for class ID: $classId");
    }

    public static function getAllClasses()
    {
        $classes = Database::getInstance()->get('classes', array('class_id', '>', '0'));
        //return list of classes
        return $classes->results();
    }

    public static function getAllClassesByUserId($userId)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Get class IDs from class_students table
        $sql = "SELECT class_id FROM class_students WHERE student_id = ?";
        $studentClasses = $database->query($sql, [$userId])->results();

        // Get class IDs from class_teachers table
        $sql = "SELECT class_id FROM class_teachers WHERE teacher_id = ?";
        $teacherClasses = $database->query($sql, [$userId])->results();

        // Merge the class IDs
        $classIds = array_merge($studentClasses, $teacherClasses);

        // Check if the user is in any classes
        if (empty ($classIds)) {
            throw new Exception('No classes found for this user');
        }

        // Get class details
        $classes = [];
        foreach ($classIds as $classId) {
            $sql = "SELECT * FROM classes WHERE class_id = ?";
            $classes[] = $database->query($sql, [$classId->class_id])->first();
        }

        // Return the classes
        return $classes;
    }
}