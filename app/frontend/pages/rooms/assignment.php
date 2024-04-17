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
            <p>Class: <a
                    href="class.php?class_id=<?php echo htmlspecialchars($assignment->class); ?>"><?php echo $class->name; ?></a>
            </p>
            <p>Due Date: <?php echo date('d-m-Y, H:i', strtotime($assignment->due_date)); ?></p>

            <div class="bg-body-tertiary p-3 my-3">
                <?php
                $note = Posts::getNoteByNoteId($assignment->note);

                echo $Parsedown->text($note->text);
                ?>
            </div>

            <?php
            if ($assignment->group_room) {
                $group_room_id = $assignment->group_room;
                $groups = Groups::getGroupsByGroupRoomID($group_room_id);
            
                $post = Classes::getPostLinkedToAssignment($assignment_id);
                $section_id = $post->section_id;
            
                $class = Classes::getClassBySectionId($section_id);
                $allClassStudents = Classes::getAllStudents($class->class_id);
                $studentsNotInGroup = array_filter($allClassStudents, function ($student) use ($group_room_id) {
                    return !Groups::getCurrentGroup($student->user_id, $group_room_id);
                });
            
                if (!$is_teacher && Groups::getCurrentGroup($user->data()->user_id, $group_room_id)) {
                    $group_id = Groups::getCurrentGroup($user->data()->user_id, $group_room_id);
                    $group = Groups::getGroupById($group_id->group_id);
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
                    echo "</div>";
                }
            
                echo "<a href='groups.php?group_room_id=" . urlencode($group_room_id) . "&section_id=" . urlencode($section_id) . "'>Rediger grupper</a>";
            }
            ?>

            <?php
            if (User::isUserTeacherForClass($user->data()->user_id, $assignment->class)) {
                $submissions = Classes::getSubmissionsByAssignmentId($assignment_id);

                $students = Classes::getAllStudents($assignment->class);

                echo '<p>Afleveret: ' . count($submissions) . " ud af " . count($students) . '</p>';
                ?>

                <a href="assignment-responses.php?assignment_id=<?php echo $assignment_id ?>">Gennemse afleveringer</a>

            <?php
            } else {
                $submission = Classes::getSubmissionByUserIdAndAssignmentId($user->data()->user_id, $assignment_id);
                ?>

                <?php if ($submission !== null) { ?>
                    <p>You have submitted this assignment. <a
                            href="retract_submission.php?submission_id=<?php echo $submission->id; ?>">Click here to retract
                            it.</a></p>
                <?php } else { ?>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        Select file to submit:
                        <input type="file" name="fileToUpload" id="fileToUpload" onchange="displayFileType(event)">
                        <input type="hidden" name="return_page" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user->data()->user_id; ?>">
                        <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                        <input type="submit" value="Submit Assignment" name="submit">
                    </form>
                    <p id="fileTypeDisplay"></p>
                <?php } ?>

            <?php } ?>
        </div>
    </div>
</div>