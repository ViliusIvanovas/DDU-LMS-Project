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
        $classes = Classes::getAllClassesByUserId($userId);

        // get all connected time_modules to this class, with time_module_participating_classes table
        $timeModules = array();
        foreach ($classes as $class) {
            $timeModuleParticipants = Database::getInstance()->get('time_module_participating_classes', array('class', '=', $class->class_id));
            foreach ($timeModuleParticipants->results() as $timeModuleParticipant) {
                $timeModuleId = $timeModuleParticipant->time_module;
                $timeModule = Calender::getTimeModuleById($timeModuleId);
                if ($timeModule && strtotime($timeModule->start_time) >= strtotime($date) && strtotime($timeModule->end_time) < strtotime($date . ' +1 day')) {
                    $timeModules[] = $timeModule;
                }
            }
        }

        // get all subjects for this day
        $subjects = array();
        foreach ($timeModules as $timeModule) {
            $timeModuleId = $timeModule->time_module_id;
            $subject = Database::getInstance()->get('time_modules', array('time_module_id', '=', $timeModuleId));
            if ($subject->count()) {
                $subjects[] = $subject->first();
            }
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
}
