<!DOCTYPE html>
<html>
<head>
    <title>StackEdit in PHP</title>
    <style>
        #note {
            display: none; /* Makes the textarea invisible */
        }
    </style>
</head>
<body>
    <textarea id="note" name="note" required></textarea>

    <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
    <script>
        const el = document.querySelector('#note');
        const stackedit = new Stackedit();

        // Listen to StackEdit events and apply the changes to the textarea.
        stackedit.on('fileChange', (file) => {
            el.value = file.content.text;
        });

        // Listen to StackEdit close event
        stackedit.on('close', () => {
            // Upload the note to the database
            fetch('upload_note.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    note: el.value
                })
            }).then(() => {
                // Redirect to the previous page after the note is uploaded
                window.history.back();
            });
        });

        // Open StackEdit when the page loads
        window.onload = () => {
            stackedit.openFile({
                name: 'Filename', // with an optional filename
                content: {
                    text: el.value // and the Markdown content.
                }
            });
        };
    </script>
</body>
</html>