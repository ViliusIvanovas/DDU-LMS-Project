<?php 

$assignment_id = $_GET['assignment_id'];

$assignments = Classes::getSubmissionsByAssignmentId($assignment_id);

foreach ($assignments as $assignment) {
    $student = User::getUserById($assignment->student_id);
    $grade = $assignment->grade;
    $comment = $assignment->comment;
    $submission_date = $assignment->submission_date;
    $submission = $assignment->submission;
    $submission_id = $assignment->submission_id;

    ?>

    <div class="card bg-body-tertiary mb-3">
        <div class="card-header">
            <h5><?php echo $student->first_name . ' ' . $student->last_name; ?></h5>
        </div>
        <div class="card-body">
            <p class="card-text"><?php echo $comment; ?></p>
            <p class="card-text">Karakter: <?php echo $grade; ?></p>
            <p class="card-text">Afleveret: <?php echo $submission_date; ?></p>
            <a href="submission.php?submission_id=<?php echo $submission_id; ?>">Se aflevering</a>
        </div>
    </div>


    <?php
}
?>
