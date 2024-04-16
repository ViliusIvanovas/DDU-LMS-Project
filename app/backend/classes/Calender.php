<?php

class Calender
{
    public static function create($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the time_module table and get the last inserted ID
        $lastInsertedId = $database->insert('time_modules', $data);

        // Return the ID of the last inserted row
        return $lastInsertedId;
    }

    public static function getAllSubjectsForPersonForADay($date, $userId)
    {
        // get all classes for this user
        $classesQuery = Database::getInstance()->query("
        SELECT * 
        FROM class_students 
        WHERE student_id = ?
    ", array($userId));

        $classes = $classesQuery->results();

        // get all subjects for this day
        $subjects = array();
        foreach ($classes as $class) {
            $subjectsQuery = Database::getInstance()->query("
            SELECT tm.* 
            FROM time_modules tm
            JOIN time_module_participating_classes tmpc ON tm.time_module_id = tmpc.time_module
            WHERE tmpc.class = ? AND tm.start_time BETWEEN ? AND ?
        ", array($class->class_id, $date . ' 00:00:00.000000', $date . ' 23:59:59.000000'));

            $subjects = array_merge($subjects, $subjectsQuery->results());
        }

        return $subjects;
    }

    public static function getAllDatesOfTheWeek($weeknumber, $year)
    {
        $dates = array();
        $startOfWeek = strtotime("{$year}-W{$weeknumber}-1");  // '1' is Monday

        for ($i = 0; $i < 5; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i day", $startOfWeek));
        }

        return $dates;
    }

    public static function getTodaysWeekNumber()
    {
        // Get the current week number
        return date('W');
    }

    public static function getTimeModuleById($time_module_id)
    {
        $time_module = Database::getInstance()->get('time_modules', array('time_module_id', '=', $time_module_id));
        if ($time_module->count()) {
            return $time_module->first();
        }
    }

    public static function getTeachersIdsByTimeModuleId($time_module_id)
    {
        $teachers = Database::getInstance()->get('time_module_teachers', array('time_module', '=', $time_module_id));
        if ($teachers->count()) {
            return $teachers->results();
        }
    }

    public static function getTimeModuleLocation($time_module_id)
    {
        $db = Database::getInstance();

        $timeModuleLocations = $db->get('time_module_locations', array('time_module', '=', $time_module_id));

        $locations = array();

        if ($timeModuleLocations->count()) {
            foreach ($timeModuleLocations->results() as $timeModuleLocation) {
                $location = $db->get('locations', array('location_id', '=', $timeModuleLocation->location));
                if ($location->count()) {
                    $locations[] = $location->first();
                }
            }
        }

        return $locations;
    }

    public static function getTimeModuleNotes($time_module_id)
    {
        $notes = Database::getInstance()->get('time_module_notes', array('time_module', '=', $time_module_id));
        if ($notes->count()) {
            return $notes->results();
        }
    }

    public static function getParticipatingClasses($time_module_id)
    {
        $classes = Database::getInstance()->get('time_module_participating_classes', array('time_module', '=', $time_module_id));
        // use the class_ids from here to get the actual classes
        $classIds = $classes->results();
        $classes = array();
        foreach ($classIds as $classId) {
            $class = Database::getInstance()->get('classes', array('class_id', '=', $classId->class));
            if ($class->count()) {
                $classes[] = $class->first();
            }
        }

        return $classes;
    }
}
