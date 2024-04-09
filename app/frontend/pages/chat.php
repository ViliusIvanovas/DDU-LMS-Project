<div style="width: 90%; margin: auto; position: relative;">
    <h1> Samtaler </h1>
    <button id="open-button" style="position: absolute; top: 10px; right: 0;">+ Opret samtale</button>

    <?php
    $userid = $user->data()->user_id;
    $conversations = Chat::getConversationsByUserId($userid);
    $message_id = $_GET['message_id'] ?? null;
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
                    echo "<small>" . implode(", ", Chat::getRecipientNamesByMessageId($message_id)) .  "</small> <br> <br>";
                    echo "<p>$message</p>";
                    echo "<textarea id='response' placeholder='Skriv dit svar her...'></textarea>";
                    echo "<button id='send-response'>Send svar</button>";
                } else {
                    echo "<h2> Vælg en samtale for at se beskeder </h2>";
                }
                ?>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content bg-body-tertiary">
        <span class="close">&times;</span>
        <input type="text" id="textbox1" placeholder="Emne">
        <input type="text" id="textbox2" placeholder="Tilføj person">
        <input type="hidden" id="textbox3" placeholder="Text Box 3">
        <button id="message-button">Rediger besked</button>
        <!-- File input -->
        <input type="file" id="fileToUpload" style="display: none;">
        <button id="upload-button">Upload File</button>

        <!-- Button to send the message -->
        <button id="send-button">Send</button>
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

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    const stackedit = new Stackedit();

    // Open the StackEdit editor when the button is clicked
    document.querySelector('#message-button').addEventListener('click', () => {
        stackedit.openFile({
            name: 'Filename', // with an optional filename
            content: {
                text: document.querySelector('#textbox3').value // and the Markdown content.
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
</style>