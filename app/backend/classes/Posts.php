<?php

class Posts
{
    public static function create($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the classes table and get the last inserted ID
        $lastInsertedId = $database->insert('posts', $data);

        // Return the ID of the last inserted row
        return $lastInsertedId;
    }

    public static function getAllPostsBySectionId($section_id)
    {
        $posts = Database::getInstance()->get('posts', array('section_id', '=', $section_id));
        //return list of posts
        return $posts->results();
    }

    public static function getPostByPostId($post_id)
    {
        $post = Database::getInstance()->get('posts', array('post_id', '=', $post_id));
        $post_reference = $post->first();
        $specific_post_id = $post_reference->specific_post_id;

        $type_name = Posts::getPostType($post_id);

        // if name is Aflevering, Opslagsværk, Grupper, Fil, Note then call the respective table
        if ($type_name->name == 'Aflevering') {
            $assignment = Database::getInstance()->get('assignments', array('assignment_id', '=', $specific_post_id));
            return $assignment->first();
        } else if ($type_name->name == 'Opslagsværk') {
            $bulletin_board = Database::getInstance()->get('bulletin_boards', array('bulletin_board_id', '=', $specific_post_id));
            return $bulletin_board->first();
        } else if ($type_name->name == 'Grupper') {
            $group = Database::getInstance()->get('groups', array('group_id', '=', $specific_post_id));
            return $group->first();
        } else if ($type_name->name == 'Fil') {
            $file = Database::getInstance()->get('files', array('file_id', '=', $specific_post_id));
            return $file->first();
        } else if ($type_name->name == 'Note') {
            $note = Database::getInstance()->get('notes', array('note_id', '=', $specific_post_id));
            return $note->first();
        }
    }

    public static function getPostType($post_id)
    {
        $post = Database::getInstance()->get('posts', array('post_id', '=', $post_id));

        $type = $post->first()->post_type;

        $post_type = Database::getInstance()->get('post_types', array('post_type_id', '=', $type));
        return $post_type->first();
    }
}