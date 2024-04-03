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
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Get user's classes
        $classes = Classes::getAllClassesByUserId($userId);

        // Extract class IDs
        $classIds = array_map(function ($class) {
            return $class->class_id;
        }, $classes);

        // Prepare the SQL query
        $inQuery = implode(',', array_fill(0, count($classIds), '?'));
        $sql = "SELECT * FROM time_modules WHERE start_time = ? AND class IN ($inQuery)";

        // Prepare the parameters
        $params = array_merge([$date], $classIds);

        // Execute the query and return the results
        return $database->query($sql, $params);
    }

    public static function getAllDatesOfTheWeek($weeknumber, $year)
    {
        // convert the week and year number into timestamps
        $date = strtotime($year . 'W' . $weeknumber);

        // Get the first day of the week
        $firstDay = strtotime('last monday', $date);

        // Get the last day of the week
        $lastDay = strtotime('next sunday', $date);

        // Get all the dates of the week
        $dates = [];

        // Loop through the days of the week
        for ($i = $firstDay; $i <= $lastDay; $i = strtotime('+1 day', $i)) {
            $dates[] = date('Y-m-d', $i);
        }

        // Return the dates
        return $dates;
    }
}
