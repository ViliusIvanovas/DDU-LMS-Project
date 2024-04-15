<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responseContent = $_POST['response'];
    $messageId = $_POST['message_id'];
    $sender = $_POST['sender'];

    if (!empty($responseContent)) {
        $data = array(
            'message_id' => $messageId,
            'message' => $responseContent,
            'sender' => $sender,
        );

        try {
            $lastInsertedId = Chat::createResponse($data);
            var_dump($lastInsertedId); // Output the value and type of $lastInsertedId

            header("Location: chat.php?message_id=$messageId"); // Redirect to chat.php with the message id
            exit();
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'Error: The response content was not provided.';
    }
} else {
    echo 'Error: The request method must be POST.';
}
