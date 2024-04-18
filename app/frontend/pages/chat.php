<div style="width: 90%; margin: auto; position: relative;">
    <h1> Samtaler </h1>
    <button id="open-button" style="position: absolute; top: 10px; right: 0;">+ Opret samtale</button>

    <?php
    $userid = $user->data()->user_id;
    $conversations = Chat::getConversationsByUserId($userid);
    $message_id = $_GET['message_id'] ?? null;

    require_once 'parsedown-1.7.4/Parsedown.php';
    ?>

    <div class="container my-5">
        <div class="row align-items-center rounded-3 border shadow-lg bg-body-tertiary schedule-box">
            <div class="col-sm-4 p-4 all-conversations conversation-box">
                <?php
                usort($conversations, function ($a, $b) {
                    return strtotime($a->sent_at) < strtotime($b->sent_at);
                });

                foreach ($conversations as $conversation) {
                    $sender = $conversation->sender;
                    $message = $conversation->message;
                    $date = $conversation->sent_at;
                    $title = $conversation->title;
                    $name = User::getFullName($sender);
                    $recipients = Chat::getRecipientNamesByMessageId($conversation->message_id);
                    $recipientsString = implode(",", $recipients);


                ?>
                    <a style="text-decoration: none; color: inherit;" href="chat.php?message_id=<?php echo $conversation->message_id; ?>">
                        <div class='bg-body' data-recipients='$recipientsString' style='border: 1px solid gray; padding: 10px; margin: 10px; height: auto; width: auto; '>
                            <?php
                            echo "<div class='conversation' data-message-id='{$conversation->message_id}'>";
                            echo "<h3>$title</h3>";
                            echo "<p>$message</p>";
                            echo "<p>Sender: $name</p>";
                            echo "<p>Date: $date</p>";
                            echo "</div>";
                            ?>
                        </div>
                    </a>
                <?php
                }
                ?>

            </div>


            <div class="col-sm-8 bg-body specific-conversation conversation-box">
                <?php
                if ($message_id) {
                    $chatMessage = Chat::getMessageById($message_id);
                    $sender = $chatMessage->sender;
                    $message = $chatMessage->message;
                    $date = $chatMessage->sent_at;
                    $title = $chatMessage->title;
                    $name = User::getFullName($sender);

                    echo "<p>" . $name . " - " . $date . "</p>";
                    echo "<h3>$title</h3>";
                    echo "<small>" . implode(", ", Chat::getRecipientNamesByMessageId($message_id)) . "</small> <br> <br>";
                ?>

                    <div>
                        <?php
                        $Parsedown = new Parsedown();
                        echo $Parsedown->text($message);
                        ?>
                    </div>

                <?php

                    // Fetch the responses for the selected message
                    $responses = Chat::getResponsesByMessageId($message_id);

                    usort($responses, function ($a, $b) {
                        return strtotime($a->timestamp) > strtotime($b->timestamp);
                    });

                    echo "<div class='responses bg-body-tertiary'>";

                    // Display the responses
                    foreach ($responses as $response) {
                        $responder = User::getFullName($response->sender);
                        $responseContent = $response->message;
                        $responseDate = $response->timestamp;

                        echo "<div class='response'>";
                        echo "<p>" . $responder . " - " . $responseDate . "</p>";
                        $Parsedown = new Parsedown();
                        echo $Parsedown->text($responseContent);
                        echo "</div>";
                    }

                    echo "</div>";

                    echo "<button id='response-button'>Skriv svar her</button>";
                    echo "<button id='reply-button' data-message-type='reply' style='display: none;'>Send svar</button>";
                } else {
                    echo "<h2> Vælg en samtale for at se beskeder </h2>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<form id="reply-form" action="upload_responses.php" method="post">
    <input type="hidden" id="reply-content" name="response">
    <input type="hidden" id="message-id" name="message_id" value="<?php echo $message_id ?>">
    <input type="hidden" id="sender" name="sender" value="<?php echo $userid ?>">
</form>

<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
<script>
    window.onload = function() {
        const stackedit = new Stackedit();

        // Open the StackEdit editor when the "Response" button is clicked
        document.querySelector('#response-button').addEventListener('click', () => {
            stackedit.openFile({
                name: 'Response', // with an optional filename
                content: {
                    text: '' // and the Markdown content.
                }
            });
            document.querySelector('#reply-button').style.display = 'inline';
        });

        // Update the form fields when the file changes
        stackedit.on('fileChange', (file) => {
            document.querySelector('#reply-content').value = file.content.text;
        });

        // Upload the form when the "Send" button is clicked
        document.querySelector('#reply-button').addEventListener('click', () => {
            document.querySelector('#reply-form').submit();
        });

        stackedit.on('close', function() {
            var responseButton = document.querySelector('#response-button');
            responseButton.innerHTML = 'Rediger besked';
        });
    }
</script>


<?php
$users = "";
$classesList = "";

$allUsers = User::getAllUsers();

foreach ($allUsers as $user) {
    $name = User::getFullName($user->user_id);

    switch ($user->access_level) {
        case 1:
            $users .= "<option value='{$user->user_id}'>{$name} (Student)</option>";
            break;
        case 2:
            $users .= "<option value='{$user->user_id}'>{$name} (Teacher)</option>";
            break;
        case 3:
            $users .= "<option value='{$user->user_id}'>{$name} (Admin)</option>";
            break;
        default:
            echo "Unknown access_level: {$user->access_level} for user_id: {$user->user_id}<br>";
    }
}

$classes = Classes::getAllClasses();

foreach ($classes as $class) {
    $classesList .= "<option value='class{$class->class_id}'>{$class->name} (Class)</option>";
}
?>

<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content bg-body-tertiary">
        <span class="close">&times;</span>
        <input type="text" id="textbox1" name="title" placeholder="Emne" required>
        <select id="users" name="users" required>
            <option value="" disabled selected>Vælg modtager</option>
            <?php echo $users;
            echo $classesList;
            ?>
        </select>
        <button type="button" id="add-button">Tilføj modtager</button>
        <ul id="recipients"></ul>
        <input type="hidden" id="textbox3" name="message">
        <button type="button" id="message-button">Skriv besked</button>
        <form id="message-form" action="send_message.php" method="post">
            <input type="hidden" id="title" name="title">
            <input type="hidden" id="message" name="message">
            <input type="hidden" id="sender" name="sender" value="<?php echo $userid ?>">
            <!-- File input -->
            <input type="file" id="fileToUpload" name="file" style="display: none;">
            <button type="button" id="upload-button">Upload File</button>
            <!-- Button to send the message -->
            <button type="submit" id="send-button">Send</button>
        </form>
    </div>
</div>



<script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
<script>
    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("open-button");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal 
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Get the users select element
    var users = <?php echo json_encode(explode("\n", $users)); ?>;

    // Populate the users select element
    var usersSelect = document.getElementById("users");

    // Get the button that adds a recipient
    var addButton = document.getElementById("add-button");

    // Get the select element that contains the users
    var usersSelect = document.getElementById("users");

    // Get the list that will contain the recipients
    var recipientsList = document.getElementById("recipients");

    // Add an event listener to the add button
    addButton.addEventListener("click", function() {
        // Add the selected user to the recipients list
        var option = usersSelect.options[usersSelect.selectedIndex];

        if (option.text !== "Vælg bruger") {
            var li = document.createElement("li");
            li.textContent = option.text;
            li.dataset.value = option.value;

            // Create a hidden input field for the recipient
            var input = document.createElement("input");
            input.type = "hidden";
            input.name = "recipients[]";
            input.value = option.value;
            document.getElementById("message-form").appendChild(input);

            // Create a remove button
            var removeButton = document.createElement("button");
            removeButton.textContent = "Remove";
            removeButton.addEventListener("click", function() {
                // Remove the recipient from the recipients list
                recipientsList.removeChild(li);

                // Remove the hidden input field for the recipient
                document.getElementById("message-form").removeChild(input);

                // Add the recipient back to the users select
                usersSelect.add(option);
            });
            li.appendChild(removeButton);

            recipientsList.appendChild(li);

            // Remove the selected user from the users select
            usersSelect.remove(usersSelect.selectedIndex);
        } else {
            alert("Please select a user.");
        }
    });

    document.getElementById('send-button').addEventListener('click', function(event) {
        var recipients = document.querySelectorAll("#recipients li");
        if (recipients.length === 0) {
            alert("Please add a user or class before sending the message.");
            event.preventDefault();
        }
    });

    const stackedit = new Stackedit();

    // Open the StackEdit editor when the button is clicked
    document.querySelector('#message-button').addEventListener('click', () => {
        stackedit.openFile({
            name: 'Filename', // with an optional filename
            content: {
                text: document.querySelector('#textbox3').value // and the Markdown content.
            }
        });

        stackedit.on('close', function() {
            var responseButton = document.querySelector('#message-button');
            responseButton.innerHTML = 'Rediger besked';
        });
        // Prevent the form submission if the message content is empty
        document.querySelector('#message-form').addEventListener('submit', (event) => {
            if (!document.querySelector('#textbox3').value) {
                alert('Please provide the message content.');
                event.preventDefault();
            }
        });

        document.querySelector('#message-form').addEventListener('submit', (event) => {
            document.querySelector('#title').value = document.querySelector('#textbox1').value;
            document.querySelector('#message').value = document.querySelector('#textbox3').value;
            if (!document.querySelector('#message').value) {
                alert('Please provide the message content.');
                event.preventDefault();
            }
        });
        // Listen to StackEdit events and set the content of the third text box
        stackedit.on('fileChange', (file) => {
            document.querySelector('#textbox3').value = file.content.text;
        });
    });

    // Get the upload button
    var uploadButton = document.getElementById("upload-button");

    // Get the file input
    var fileInput = document.getElementById("fileToUpload");

    // When the user clicks the upload button, trigger the file input click
    uploadButton.onclick = function() {
        fileInput.click();
    }

    // When the user selects a file, display the file name
    fileInput.onchange = function() {
        if (fileInput.files.length > 0) {
            uploadButton.textContent = "Upload File: " + fileInput.files[0].name;
        }
    }

    // Add an event listener to the form
    document.getElementById('message-form').addEventListener('submit', function(event) {
        var messageType = document.getElementById('send-button').getAttribute('data-message-type');

        var recipients = document.querySelectorAll("#recipients li");
        if (recipients.length === 0 && messageType !== 'reply') {
            alert("Please add a user or class before sending the message.");
            event.preventDefault();
        }

        // Prevent the form submission if the message content is empty
        if (!document.querySelector('#textbox3').value) {
            alert('Please provide the message content.');
            event.preventDefault();
        }

        document.querySelector('#title').value = document.querySelector('#textbox1').value;
        document.querySelector('#message').value = document.querySelector('#textbox3').value;
        if (!document.querySelector('#message').value) {
            alert('Please provide the message content.');
            event.preventDefault();
        }

        var selectedClassIds = []; // Replace this with the actual selected class IDs
        document.getElementById('class_recipients').value = JSON.stringify(selectedClassIds);

        document.getElementById('reply-button').addEventListener('click', function() {
            document.getElementById('reply-content').value = document.getElementById('textbox3').value;
            document.getElementById('reply-form').submit();
        });
    });
</script>
<style>
    
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
        align-items: center;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        /* Could be more or less, depending on screen size */
        margin-top: 10% !important;
        margin-bottom: auto !important;
    }

    .modal-content>* {
        margin-bottom: 15px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .specific-conversation {
        height: 100%;
        overflow-y: auto;
        border-top-right-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    .conversation {
        overflow-x: auto;
    }

    .conversation-box {
        height: 500px;
        overflow: auto;
    }

    .no-pointer-events {
        pointer-events: none;
    }

    .response {
        margin-top: 40px;
    }
</style>