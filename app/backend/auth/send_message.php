<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $sender = $_POST['sender'];
    $recipients = $_POST['recipients'];

    if (!empty ($title) && !empty ($message)) {
        foreach ($recipients as $recipient) {
            $data = array(
                'title' => $title,
                'message' => $message,
                'sender' => $sender,
                'recipient' => $recipient,
            );

            try {
                $lastInsertedId = Chat::createMessage($data);

                header("Location: chat.php?message_id=$lastInsertedId"); // Redirect to chat.php with the message id
                exit();
            } catch (Exception $e) {
                echo 'Error: ' . $e->getMessage();
            }
        }
    } else {
        echo 'Error: The title or message content was not provided.';
    }
} else {
    echo 'Error: The request method must be POST.';
}