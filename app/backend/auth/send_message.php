<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $sender = $_POST['sender'];
    $recipients = $_POST['recipients'];

    var_dump($title, $message, $sender, $recipients);

    if (!empty($title) && !empty($message)) {
        $data = array(
            'title' => $title,
            'message' => $message,
            'sender' => $sender,
        );

        try {
            $lastInsertedId = Chat::createMessage($data, $recipients);

            //header("Location: chat.php?message_id=$lastInsertedId"); // Redirect to chat.php with the message id
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Error: The title or message content was not provided.';
    }
} else {
    echo 'Error: The request method must be POST.';
}