<?php

class Chat
{
    public static function create($data)
    {
        $database = Database::getInstance();

        $lastInsertedId = $database->insert('messages', $data);

        return $lastInsertedId;
    }

    public static function getConversationsByUserId($userId)
    {
        $database = Database::getInstance();

        $messages = array();

        // get all message_ids where user_id is the recipient according to the message_recipients table
        $messages_received = $database->get('message_recepients', array('recepient', '=', $userId));

        if ($messages_received->count()) {
            foreach ($messages_received->results() as $message) {
                $messageId = $message->message;
                $messages[] = Chat::getMessageById($messageId);
            }
        }

        // get all message_ids where user_id is the sender according to the messages table
        $messages_sent = $database->get('messages', array('sender', '=', $userId));

        if ($messages_sent->count()) {
            foreach ($messages_sent->results() as $message) {
                $messageId = $message->message_id;
                $messages[] = Chat::getMessageById($messageId);
            }
        }

        return $messages;
    }

    public static function getMessageById($messageId)
    {
        $message = Database::getInstance()->get('messages', array('message_id', '=', $messageId));
        return $message->first();
    }
}
