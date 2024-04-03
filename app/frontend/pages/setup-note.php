<!DOCTYPE html>
<html>

<head>
    <title>StackEdit in PHP</title>
</head>

<body>
    <?php
    $section_id = $_GET['section_id'];
    ?>

    <form id="upload-form" action="upload_note.php" method="post">
        <input type="hidden" id="note-content" name="note">
        <label for="note-title">Title:</label>
        <input type="text" id="note-title" name="title">
        <input type="hidden" id="section-id" name="section_id" value="<?php echo $section_id ?>">
        <button type="button" id="open-button">Rediger tekst</button>
        <button type="button" id="upload-button" style="display: none;">Upload</button>
    </form>

    <script src="https://unpkg.com/stackedit-js@1.0.7/docs/lib/stackedit.min.js"></script>
    <script>
        const stackedit = new Stackedit();

        // Open the StackEdit editor when the "Open Editor" button is clicked
        document.querySelector('#open-button').addEventListener('click', () => {
            stackedit.openFile({
                name: document.querySelector('#note-title').value, // with an optional filename
                content: {
                    text: '' // and the Markdown content.
                }
            });
            document.querySelector('#upload-button').style.display = 'inline';
        });

        // Update the form fields when the file changes
        stackedit.on('fileChange', (file) => {
            document.querySelector('#note-content').value = file.content.text;
            document.querySelector('#note-title').value = file.name;
        });

        // Upload the form when the "Upload" button is clicked
        document.querySelector('#upload-button').addEventListener('click', () => {
            document.querySelector('#upload-form').submit();
        });
    </script>
</body>

</html>