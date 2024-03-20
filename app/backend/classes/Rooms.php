<?php

class Rooms
{
    public static function create($fields = array())
    {
        if (!Database::getInstance()->insert('files', $fields)) {
            throw new Exception("Unable to create the file.");
        }
    }

    public static function getSectionById($section_id)
    {
        $section = Database::getInstance()->get('sections', array('section_id', '=', $section_id));
        if ($section->count()) {
            return $section->first();
        }
    }

    public static function getAllRoomsByUserId($user_id)
    {
        $classes = Classes::getAllClassesByUserId($user_id);

        $rooms = array();

        // check "rooms" table for where class_id is in $classes
        foreach ($classes as $class) {
            $room = Database::getInstance()->get('rooms', array('class_id', '=', $class->class_id));
            if ($room->count()) {
                // Add each room object to the rooms array
                $rooms[] = $room->first();
            }
        }

        return $rooms;
    }

    public static function getRoomById($room_id)
    {
        $room = Database::getInstance()->get('rooms', array('room_id', '=', $room_id));
        if ($room->count()) {
            return $room->first();
        }
    }

    public static function getAllSectionsByRoomId($room_id)
    {
        $sections = Database::getInstance()->get('sections', array('room_id', '=', $room_id));
        if ($sections->count()) {
            return $sections->results();
        }
    }
}
