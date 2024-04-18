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
            <p>Class: <a href="class.php?class_id=<?php echo htmlspecialchars($assignment->class); ?>"><?php echo $class->name; ?></a>
            </p>
            <p>Due Date: <?php echo date('d-m-Y, H:i', strtotime($assignment->due_date)); ?></p>

            <div class="bg-body-tertiary p-3 my-3">
                <?php
                $note = Posts::getNoteByNoteId($assignment->note);

                echo $Parsedown->text($note->text);
                ?>
            </div>

            <?php
            if ($assignment->group_room !== null) {
                $group_room_id = $assignment->group_room;
                $group_room = Groups::getGroupRoomById($group_room_id);
                $groups = Groups::getGroupsByGroupRoomID($group_room_id);

                $post = Classes::getPostLinkedToAssignment($assignment_id);
                $section_id = $post->section_id;

                $class = Classes::getClassBySectionId($section_id);
                $allClassStudents = Classes::getAllStudents($class->class_id);
                $studentsNotInGroup = array_filter($allClassStudents, function ($student) use ($group_room_id) {
                    return !Groups::getCurrentGroup($student->user_id, $group_room_id);
                });

                $group_id = Groups::getCurrentGroup($user->data()->user_id, $group_room_id);
                $group = $group_id ? Groups::getGroupById($group_id->group_id) : null;
                $participants = $group ? Groups::getGroupsParticipants($group->group_id) : null;
            ?>
                <div class="bg-body-tertiary p-3 my-3">
                    <div class="text-field">
                        <h5 class="card-title"><?php echo $group_room->name; ?></h5>
                        <?php if ($group && $participants) : ?>
                            <div class='bg-body-tertiary p-3 my-3'>
                                <h2><?php echo $group->name; ?></h2>
                                <?php foreach ($participants as $participant) : ?>
                                    <div>
                                        <h5><?php if (isset($participant->student)) echo User::getFullName($participant->student); ?></h5>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class='bg-body-tertiary p-3 my-3'>
                                <h2>You are not in a group</h2>
                            </div>
                        <?php endif; ?>
                        <a href='groups.php?group_room_id=<?php echo urlencode($group_room_id); ?>&section_id=<?php echo urlencode($section_id); ?>'>Rediger grupper</a>
                    </div>
                </div>
            <?php
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
                if ($group && $participants) {
                    $submission = null;
                    foreach ($participants as $participant) {
                        if (isset($participant->student)) {
                            $tempSubmission = Classes::getSubmissionByUserIdAndAssignmentId($participant->student, $assignment_id);
                            if ($tempSubmission !== null) {
                                echo "Debug: Found submission from participant with ID: " . $participant->student . "<br>";
                                $submission = $tempSubmission;
                                break;
                            } else {
                                echo "Debug: No submission found from participant with ID: " . $participant->student . "<br>";
                            }
                        }
                    }
                } else {
                ?>
                    <div class="submission-section">
                        <?php
                        if ($submission !== null) { ?>
                            <div class="submission-box">
                                <p>You have submitted this assignment.</p>
                                <p>Submitted file: <a href="<?php echo $submission->file; ?>" download>Download</a></p>
                                <p><a href="retract_submission.php?submission_id=<?php echo $submission->id; ?>">Click here to retract it.</a></p>
                            </div>
                        <?php } else { ?>
                            <div class="submission-box">
                                <form action="upload.php" method="post" enctype="multipart/form-data">
                                    Select file to submit:
                                    <input type="file" name="fileToUpload" id="fileToUpload" onchange="displayFileType(event)">
                                    <input type="hidden" name="return_page" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user->data()->user_id; ?>">
                                    <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>">
                                    <input type="submit" value="Submit Assignment" name="submit">
                                </form>
                                <p id="fileTypeDisplay"></p>
                            </div>
                        <?php } ?>
                    </div>
        </div>
    </div>
<?php
                }
            }
?>
</div>