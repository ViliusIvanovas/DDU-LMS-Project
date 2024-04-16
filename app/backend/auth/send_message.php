<?php
function handlePostRequest($postData)
{
    $title = $postData['title'];
    $message = $postData['message'];
    $sender = $postData['sender'];
    $recipients = $postData['recipients'];

    if (!empty($title) && !empty($message)) {
        $data = array(
            'title' => $title,
            'message' => $message,
            'sender' => $sender,
        );

        // Separate recipients into users and classes
        $user_recipients = array_filter($recipients, function ($recipient) {
            return strpos($recipient, 'class') === false;
        });

        var_dump($user_recipients);

        $class_recipients = array_filter($recipients, function ($recipient) {
            return strpos($recipient, 'class') !== false;
        });

        // remove it from saying class at the beginning
        $class_recipients = array_map(function ($recipient) {
            return str_replace('class', '', $recipient);
        }, $class_recipients);

        var_dump($class_recipients);

        try {
            // Process class recipients
            foreach ($class_recipients as $class_id) {
                $allStudentsInClass = Classes::getAllStudents($class_id);
            
                if (!empty($allStudentsInClass)) {
                    foreach ($allStudentsInClass as $recipient) {
                        $user_recipients[] = $recipient->user_id;
                    }
                }
                var_dump($allStudentsInClass);
            }

            $lastInsertedId = Chat::createMessage($data, $user_recipients);

             header("Location: chat.php?message_id=$lastInsertedId");
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Error: The title or message content was not provided.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePostRequest($_POST);

    var_dump($_POST);
} else {
    echo 'Error: The request method must be POST.';
}
