<?php

$assignment_id = $_GET['assignment_id'];
$assignments = Classes::getSubmissionsByAssignmentId($assignment_id);

foreach ($assignments as $assignment) {
    $student = User::getUserById($assignment->student);
    $grade = $assignment->grade;
    $comment = $assignment->comment;
    $submission_date = $assignment->submission_date;
    $submission = $assignment->submission;
    $submission_id = $assignment->submission_id;

    // Fetch data from assignment_submissions table
    $assignment_submission = Classes::getSubmissionByUserIdAndAssignmentId($student->user_id, $assignment_id);
    $file_id = $assignment_submission->file;

    // Fetch file data from files table
    $file_data = Files::getFileById($file_id);
    $submission_file = $file_data->file; // Replace 'file' with the actual field name in the files table
    $submission_status = $assignment_submission->status;

    $group_assignment = $assignment->group_room !== null;
    $group_members = [];
    if ($group_assignment) {
        $group_room_id = $assignment->group_room;
        $group_id = Groups::getCurrentGroup($student->user_id, $group_room_id);
        $group = $group_id ? Groups::getGroupById($group_id->group_id) : null;
        $group_members = $group ? Groups::getGroupsParticipants($group->group_id) : null;
    }
?>

    <div class="card bg-body-tertiary mb-3">
        <div class="card-header">
            <?php
            if ($group_assignment && $group_members) {
                echo "<h5>Group Members:</h5>";
                foreach ($group_members as $member) {
                    if (isset($member->student)) {
                        echo "<p>" . User::getFullName($member->student) . "</p>";
                    }
                }
            } else {
                echo "<h5>" . $student->first_name . ' ' . $student->last_name . "</h5>";
            }
            ?>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo $comment; ?></p>
            <p class="card-text">Karakter: <?php echo $grade; ?></p>
            <p class="card-text">Afleveret: <?php echo $submission_date; ?></p>
            <p class="card-text">File: <?php echo $submission_file; ?></p>
            <p class="card-text">Status: <?php echo $submission_status; ?></p>
            <a href="submission.php?submission_id=<?php echo $submission_id; ?>">Se aflevering</a>
        </div>
    </div>

<?php
}
?>