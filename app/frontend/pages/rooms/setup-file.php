<body>
    <?php
    $section_id = $_GET['section_id'];

    $section = Rooms::getSectionById($section_id);
    ?>

    <?php
    
    if (isset($_SESSION['alert'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['alert'] . '</div>';
        unset($_SESSION['alert']); // Remove the alert message from the session
    }

    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']); // Remove the success message from the session
    }
    ?>

    <div id="main" class="container">
        <h1>Upload fil</h1>
        <br>

        <h4>Nuværende sti:</h4>
        <!-- call room.php command to figure -->

        <form action="upload.php" method="post" enctype="multipart/form-data">
            Vælg fil:
            <input type="file" name="fileToUpload" id="fileToUpload" onchange="displayFileType(event)">
            <input type="hidden" name="return_page" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
            <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
            <input type="submit" value="Upload fil" name="submit">
        </form>
        <p id="fileTypeDisplay"></p>
    </div>

    <script>
        function displayFileType(event) {
            var file = event.target.files[0];
            var fileTypeDisplay = document.getElementById('fileTypeDisplay');

            if (file) {
                var fileType = file.name.split('.').pop();
                var fileTypeText = '';

                // remake this to use the MySQL database
                switch (fileType) {
                    case 'docx':
                        fileTypeText = 'Word document';
                        break;
                    case 'svg':
                        fileTypeText = 'Vector file';
                        break;
                    case 'jpg':
                    case 'jpeg':
                        fileTypeText = 'JPEG image';
                        break;
                    case 'png':
                        fileTypeText = 'PNG image';
                        break;
                    case 'gif':
                        fileTypeText = 'GIF image';
                        break;
                    case 'pdf':
                        fileTypeText = 'PDF document';
                        break;
                    case 'txt':
                        fileTypeText = 'Text file';
                        break;
                    case 'exe':
                        fileTypeText = 'Executable file';
                        break;
                    case 'zip':
                        fileTypeText = 'ZIP file';
                        break;
                    case 'xlsx':
                        fileTypeText = 'Excel document';
                        break;
                    case 'pptx':
                        fileTypeText = 'PowerPoint document';
                        break;
                    case 'mp3':
                        fileTypeText = 'MP3 audio';
                        break;
                    case 'mp4':
                        fileTypeText = 'MP4 video';
                        break;
                    case 'mov':
                        fileTypeText = 'MOV video';
                        break;
                    default:
                        fileTypeText = 'Other file type';
                }

                fileTypeDisplay.textContent = 'File type: ' + fileTypeText;
            }
        }
    </script>
</body>