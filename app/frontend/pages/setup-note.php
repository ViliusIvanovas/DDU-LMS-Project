<!DOCTYPE html>
<html>

<head>
    <title>StackEdit in PHP</title>
    <style>
        #stackedit-iframe {
            width: 100%;
            height: 500px;
            /* Adjust height as per your requirement */
        }

        #note-content {
            display: none;
        }
    </style>
</head>

<body>
    <iframe id="stackedit-iframe" src="https://stackedit.io/app#" allowfullscreen></iframe>
    <textarea id="note-content"></textarea>
    <button id="upload-button">Upload</button>

    <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
    <script>
        const iframe = document.querySelector('#stackedit-iframe');
        const stackedit = new Stackedit();

        // Listen to StackEdit events and apply the changes to the textarea.
        stackedit.on('fileChange', (file) => {
            document.querySelector('#note-content').value = file.content.text;
        });

        // Add event listener to the upload button
        document.querySelector('#upload-button').addEventListener('click', uploadNote);

        function uploadNote() {
            // Get the note content from the textarea
            const noteContent = document.querySelector('#note-content').value;

            // Create a FormData object
            const formData = new FormData();
            formData.append('note', noteContent);

            // Send a POST request to your server with the note content
            fetch('upload_note.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(() => {
                    // Clear the textarea
                    document.querySelector('#note-content').value = '';
                })
                .catch(error => console.error('Error:', error));
        }

        // Add event listener to the window unload event
        window.onbeforeunload = function() {
            // Clear the textarea
            document.querySelector('#note-content').value = '';
        }
    </script>
</body>

</html>