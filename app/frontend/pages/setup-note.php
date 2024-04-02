<!DOCTYPE html>
<html>

<head>
    <title>StackEdit in PHP</title>
    <style>
        #stackedit-container {
            width: 100%;
            height: 500px;
            /* Adjust height as per your requirement */
        }

        #stackedit-iframe {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <div id="stackedit-container">
        <iframe id="stackedit-iframe" src="https://stackedit.io/app#" allowfullscreen></iframe>
    </div>

    <form id="upload-form" action="upload_note.php" method="post">
        <input type="hidden" id="note-content" name="note">
        <input type="hidden" id="note-title" name="title">
        <button type="submit" id="upload-button">Upload</button>
    </form>

    <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
    <script>
        const iframe = document.querySelector('#stackedit-iframe');
        const stackedit = new Stackedit();

        // Listen to StackEdit events and apply the changes to the textarea.
        stackedit.on('fileChange', (file) => {
            document.querySelector('#stackedit-container').innerHTML = file.content.html;
            document.querySelector('#note-content').value = file.content.text;
            document.querySelector('#note-title').value = file.name;
        });

        // Add event listener to the form submit event
        document.querySelector('#upload-form').addEventListener('submit', () => {
            // Get the note content and title from the StackEdit editor
            const noteContent = document.querySelector('#stackedit-container').innerHTML;
            const noteTitle = stackedit.file.name;

            // Set the note content and title as the values of the hidden input fields
            document.querySelector('#note-content').value = noteContent;
            document.querySelector('#note-title').value = noteTitle;
        });

        // Add event listener to the window unload event
        window.onbeforeunload = function() {
            // Clear the textarea and title
            document.querySelector('#stackedit-container').innerHTML = '';
        }
    </script>
</body>

</html>