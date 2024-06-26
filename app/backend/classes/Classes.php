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
        if (empty($classIds)) {
            return null; // Return null if no classes are found
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

    public static function getAllStudents($classId)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Get student IDs from class_students table
        $sql = "SELECT student_id FROM class_students WHERE class_id = ?";
        $studentIds = $database->query($sql, [$classId])->results();

        // Get student details
        $students = [];
        foreach ($studentIds as $studentId) {
            $sql = "SELECT * FROM users WHERE user_id = ?";
            $students[] = $database->query($sql, [$studentId->student_id])->first();
        }

        // Return the students
        return $students;
    }

    public static function getAllTeachers($classId)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Get teacher IDs from class_teachers table
        $sql = "SELECT teacher_id FROM class_teachers WHERE class_id = ?";
        $teacherIds = $database->query($sql, [$classId])->results();

        // Get teacher details
        $teachers = [];
        foreach ($teacherIds as $teacherId) {
            $sql = "SELECT * FROM users WHERE user_id = ?";
            $teachers[] = $database->query($sql, [$teacherId->teacher_id])->first();
        }

        // Return the teachers
        return $teachers;
    }

    public static function getClassBySectionId($sectionId)
    {
        // get room_id from section
        $section = Database::getInstance()->get('sections', array('section_id', '=', $sectionId));
        $room_id = $section->first()->room_id;

        // get class_id from room
        $room = Database::getInstance()->get('rooms', array('room_id', '=', $room_id));
        $class_id = $room->first()->class_id;

        return self::getClassById($class_id);
    }

    public static function getAllAssignmentsByStudent($userId, $role = 'student')
    {
        // get all of users classes
        $classes = Classes::getAllClassesByUserId($userId);

        // get all class IDs
        $classIds = array_map(function ($class) {
            return $class->class_id;
        }, $classes);

        // convert class IDs to a comma-separated string
        $classIdsStr = implode(',', $classIds);

        // get all assignments for the classes
        $order = $role === 'teacher' ? 'DESC' : 'ASC';
        $sql = "SELECT * FROM assignments WHERE class IN ($classIdsStr) ORDER BY due_date $order";
        $assignments = Database::getInstance()->query($sql)->results();

        return $assignments;
    }

    public static function getPostLinkedToAssignment($assignmentId)
    {
        $assignment = Database::getInstance()->get('assignments', array('assignment_id', '=', $assignmentId));
        if ($assignment->count()) {
            // get all posts with post_type = 7
            $posts = Database::getInstance()->get('posts', array('post_type', '=', 7))->results();

            // get post_id from assignment, by finding where specific_post_id = assignment_id
            foreach ($posts as $post) {
                if ($post->specific_post_id == $assignmentId) {
                    return $post;
                }
            }
        }
        throw new Exception("Post not found for assignment ID: $assignmentId");
    }

    public static function getAssignmentById($assignmentId)
    {
        $assignment = Database::getInstance()->get('assignments', array('assignment_id', '=', $assignmentId));
        if ($assignment->count()) {
            return $assignment->first();
        }
        throw new Exception("Assignment not found for assignment ID: $assignmentId");
    }

    public static function getSubmissionsByAssignmentId($assignment_id)
    {
        $db = Database::getInstance();

        $data = $db->get('assignment_submissions', array('assignment', '=', $assignment_id));

        if (!$data->count()) {
            return array(); // Return an empty array if no submissions are found
        }

        return $data->results();
    }

    public static function getSubmissionByUserIdAndAssignmentId($user_id, $assignment_id)
    {
        $db = Database::getInstance();

        // Get all submissions by user_id
        $data = $db->get('assignment_submissions', array('user', '=', $user_id));

        if ($data === false || !$data->count()) {
            return null; // Return null if no submission is found or if a database error occurs
        }

        $filteredData = array_filter($data->results(), function ($submission) use ($assignment_id) {
            return isset($submission->assignment) && $submission->assignment == $assignment_id;
        });

        if (empty($filteredData)) {
            return null; // Return null if no submission is found for the specified assignment_id
        }

        return reset($filteredData); // Return the first submission that matches the assignment_id
    }

    public static function getAllTimeModuleByClass($class_id, $date)
    {
        $db = Database::getInstance();

        $data = $db->query("
        SELECT tmp.*, tm.start_time, tm.end_time
        FROM time_module_participating_classes tmp
        JOIN time_modules tm ON tmp.time_module = tm.time_module_id
        WHERE tmp.class = ? AND DATE(tm.start_time) = ?
    ", array($class_id, $date))->results();

        return $data;
    }
}
