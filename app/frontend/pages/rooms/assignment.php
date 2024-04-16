<?php 
$assignment_id = $_GET['assignment_id'];
$assignment = Classes::getAssignmentById($assignment_id); // Replace with your function to fetch assignment details
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo htmlspecialchars($assignment->name); ?></h1>
            <p>Opened: <?php echo date('d-m-Y, H:i', strtotime($assignment->open_date)); ?></p>
            <p>Closes: <?php echo date('d-m-Y, H:i', strtotime($assignment->due_date)); ?></p>
            <p>Points: <?php echo htmlspecialchars($assignment->points); ?></p>
            <p>Files: <?php echo htmlspecialchars($assignment->files); ?></p>
            <p>Comments: <?php echo htmlspecialchars($assignment->comments); ?></p>
            <p>Grades: <?php echo htmlspecialchars($assignment->grades); ?></p>

            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="file">Upload file:</label>
                <input type="file" id="file" name="file">
                <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                <input type="submit" value="Upload">
            </form>

            <form action="comment.php" method="post">
                <label for="comment">Note:</label>
                <textarea id="comment" name="comment"></textarea>
                <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                <input type="submit" value="Submit Comment">
            </form>
        </div>
    </div>
</div>