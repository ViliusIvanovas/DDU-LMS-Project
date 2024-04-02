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
        <input type="hidden" id="section-id" name="section_id" value="1">
        <button type="submit" id="upload-button">Upload</button>
    </form>

    <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
    <script>
        const iframe = document.querySelector('#stackedit-iframe');
        const stackedit = new Stackedit({
            usePostMessage: true
        });

        // Open StackEdit with an iframe
        stackedit.openFile({
            name: 'Filename', // with an optional filename
            content: {
                text: '' // and the Markdown content.
            }
        }, iframe);

        // Listen to StackEdit events and apply the changes to the textarea.
        stackedit.on('fileChange', (file) => {
            document.querySelector('#note-content').value = file.content.text;
            document.querySelector('#note-title').value = file.name;
        });

        // Add event listener to the form submit event
        document.querySelector('#upload-form').addEventListener('submit', (event) => {
            // Get the note content from the StackEdit editor
            const noteContent = document.querySelector('#note-content').value;
            const noteTitle = document.querySelector('#note-title').value;
            const sectionId = document.querySelector('#section-id').value;

            // Prevent the form from being submitted normally
            event.preventDefault();

            // Submit the form using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_note.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200)
                    alert(xhr.responseText);
            }
            xhr.onerror = function() {
                alert('Request failed');
            };
            xhr.send('note=' + encodeURIComponent(noteContent) + '&title=' + encodeURIComponent(noteTitle) + '&section_id=' + encodeURIComponent(sectionId));
        });

        // Add event listener to the window unload event
        window.onbeforeunload = function() {
            // Clear the textarea and title
            document.querySelector('#stackedit-container').innerHTML = '';
        }
    </script>
</body>

</html>