<?php
$assignment_id = $_GET['assignment_id'];
$assignment = Classes::getAssignmentById($assignment_id); // Replace with your function to fetch assignment details

$class = Classes::getClassById($assignment->class);

require_once 'parsedown-1.7.4/Parsedown.php';
$Parsedown = new Parsedown();
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?php echo htmlspecialchars($assignment->name); ?></h1>
            <p>Class: <a href="class.php?class_id=<?php echo htmlspecialchars($assignment->class); ?>"><?php echo $class->name; ?></a></p>
            <p>Due Date: <?php echo date('d-m-Y, H:i', strtotime($assignment->due_date)); ?></p>

            <div class="bg-body-tertiary p-3 my-3">
                <?php
                $note = Posts::getNoteByNoteId($assignment->note);

                echo $Parsedown->text($note->text);
                ?>
            </div>

            <?php
            if ($assignment->group) {
                $group = Groups::getGroupById($assignment->group);
                $participants = Groups::getGroupsParticipants($group->group_id);

                echo "<div class='bg-body-tertiary p-3 my-3'>";
                echo "<h2>" . $group->name . "</h2>";
                foreach ($participants as $participant) {
                    echo "<div>";
                    echo "<h5>";
                    if (isset($participant->student)) {
                        echo User::getFullName($participant->student);
                    }
                    echo "</h5>";
                    echo "</div>";
                }
                ?>
                <?php 
                $post = Classes::getPostLinkedToAssignment($assignment_id);
                $section_id = $post->section_id;

                ?>

                <a href="groups.php?group_room_id=<?php echo Groups::getGroupRoomByGroupId($assignment->group); ?>&section_id=<?php echo $section_id ?>">Rediger grupper</a>
                <?php
                echo "</div>";
            }
            ?>

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