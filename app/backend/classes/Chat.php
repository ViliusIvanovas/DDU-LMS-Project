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
        $messages_received = $database->get('message_recipients', array('recipient', '=', $userId));

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

    public static function getRecipientNamesByMessageId($messageId)
    {
        $database = Database::getInstance();

        // Fetch all recipients of the message
        $recipients = $database->get('message_recipients', array('message', '=', $messageId))->results();

        // Fetch the full name of each recipient
        $recipientNames = array();
        foreach ($recipients as $recipient) {
            $recipientNames[] = User::getFullName($recipient->recipient);
        }

        return $recipientNames;
    }

    public static function createResponse($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the responses table
        $responseData = [
            'message_id' => $data['message_id'],
            'message' => $data['message'],
            'sender' => $data['sender'],
            'timestamp' => date('Y-m-d H:i:s'), // Current date and time
        ];
        $database->insert('message_responses', $responseData);
    }

    public static function getResponsesByMessageId($message_id)
    {
        return Database::getInstance()->get('message_responses', ['message_id', '=', $message_id])->results();
    }

    public static function createMessage($data, $recipients)
    {
        $database = Database::getInstance();

        // Insert the message into the messages table
        $messageData = [
            'title' => $data['title'],
            'message' => $data['message'],
            'sender' => $data['sender'],
            'sent_at' => date('Y-m-d H:i:s'), // Current date and time
        ];
        $lastInsertedId = $database->insert('messages', $messageData);

        // Insert a record into the message_recipients table for each recipient
        foreach ($recipients as $recipient) {
            // Check if the recipient exists in the users table
            $user = $database->get('users', ['user_id', '=', $recipient])->first();
            if (!$user) {
                throw new Exception("User not found: $recipient");
            }

            $recipientData = [
                'message' => $lastInsertedId,
                'recipient' => $recipient,
            ];
            $database->insert('message_recipients', $recipientData);
        }

        return $lastInsertedId;
    }
}
