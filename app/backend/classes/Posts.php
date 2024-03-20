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

    public static function deletePost($post_id)
    {
        if (!Database::getInstance()->delete('posts', array('post_id', '=', $post_id))) {
            throw new Exception("Unable to delete the post.");
        }
    }

    public static function getPostById($post_id)
    {
        $post = Database::getInstance()->get('posts', array('post_id', '=', $post_id));
        if ($post->count()) {
            return $post->first();
        }
        throw new Exception("Post not found for post ID: $post_id");
    }

    public static function getAllPostsBySectionId($section_id)
    {
        $posts = Database::getInstance()->get('posts', array('section_id', '=', $section_id));
        //return list of posts
        return $posts->results();
    }
}