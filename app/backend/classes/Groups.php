<?php

class Groups
{
    public static function create($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the groups table and get the last inserted ID
        $lastInsertedId = $database->insert('groups', $data);

        // Return the ID of the last inserted row
        return $lastInsertedId;
    }

    public static function getGroupsByGroupRoomID($group_room_id)
    {
        $groups = Database::getInstance()->get('`groups`', array('group_room', '=', $group_room_id));
        //return list of groups
        return $groups->results();
    }

    public static function getGroupsParticipants($group_id)
    {
        $participants = Database::getInstance()->get('`group_participants`', array('group_id', '=', $group_id));
        //return list of participants
        return $participants->results();
    }

    public static function isInGroup($student_id, $group_id)
    {
        $db = Database::getInstance();

        // Fetch the record with the given student_id and group_id
        $group = $db->get('group_participants', ['student', '=', $student_id])->results();

        // Check if a record with the given student_id and group_id exists
        foreach ($group as $participant) {
            if ($participant->group_id == $group_id) {
                return true;
            }
        }

        return false;
    }

    public static function addToGroup($student_id, $group_id)
    {
        // Get the group room of the group
        $group = Database::getInstance()->get('`groups`', array('group_id', '=', $group_id));
        $group_room_id = $group->first()->group_room;

        // Get all groups in the group room
        $groupsInRoom = Database::getInstance()->get('`groups`', array('group_room', '=', $group_room_id));

        // Check if the student is in any group in the group room
        foreach ($groupsInRoom->results() as $group) {
            if (self::isInGroup($student_id, $group->group_id)) {
                throw new Exception("Student is already in a group in this group room.");
            }
        }

        // Check if the student is already in the group
        if (self::isInGroup($student_id, $group_id)) {
            throw new Exception("Student is already in the group.");
        }

        $data = array(
            'student' => $student_id,
            'group_id' => $group_id
        );

        if (!Database::getInstance()->insert('group_participants', $data)) {
            throw new Exception("Unable to add student to the group.");
        }
    }

    public static function removeFromGroup($student_id, $group_id)
    {
        $db = Database::getInstance();

        // Check if the student is in the group
        if (!self::isInGroup($student_id, $group_id)) {
            throw new Exception("Student is not in the group.");
        }

        // Get the record with the matching student_id
        $record = $db->get('group_participants', ['student', '=', $student_id]);

        // Check if the record exists and the group_id matches
        if ($record->count() && $record->first()->group_id == $group_id) {
            // Delete the record
            if (!$db->delete('group_participants', ['student', '=', $student_id])) {
                throw new Exception("Unable to remove student from the group.");
            }
        } else {
            throw new Exception("Record not found.");
        }
    }

    public static function addGroup($group_room_id)
    {
        $db = Database::getInstance();

        // Fetch the highest group name in the current group_room
        $highestGroupName = $db->query("SELECT MAX(CAST(SUBSTRING(name, 7) AS UNSIGNED)) AS max_name FROM `groups` WHERE group_room = ?", [$group_room_id])->first()->max_name;

        // Increment the highest group name by one
        $newGroupName = 'Gruppe ' . ($highestGroupName + 1);

        $fields = array(
            'group_room' => $group_room_id,
            'name' => $newGroupName
        );

        $db->insert('`groups`', $fields);
    }

    public static function deleteGroup($group_id)
    {
        if (!Database::getInstance()->delete('groups', array('group_id', '=', $group_id))) {
            throw new Exception("Unable to delete the group.");
        }
    }

    public static function removeEmptyGroups() {
        $db = Database::getInstance();
    
        // Delete groups with no participants
        $db->query("DELETE FROM `groups` WHERE group_id NOT IN (SELECT group_id FROM group_participants)");
    }

    public static function getCurrentGroup($student_id, $group_room_id)
    {
        $db = Database::getInstance();

        // Get the group ID of the group the student is in
        $group = $db->query("SELECT group_id FROM group_participants WHERE student = ? AND group_id IN (SELECT group_id FROM `groups` WHERE group_room = ?)", [$student_id, $group_room_id])->first();

        if ($group) {
            return $group;
        }

        return null;        
    }

    public static function getGroupById($group_id)
    {
        $group = Database::getInstance()->get('`groups`', array('group_id', '=', $group_id));

        return $group->first();
    }
}
