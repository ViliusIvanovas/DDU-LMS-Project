<?php
$section_id = $_GET['section_id'];
$group_room_id = $_GET['group_room_id'];
$groups = Groups::getGroupsByGroupRoomID($group_room_id);

// Get the list of students without a group and the list of groups
$class = Classes::getClassBySectionId($section_id);
$allClassStudents = Classes::getAllStudents($class->class_id);
$studentsNotInGroup = array_filter($allClassStudents, function ($student) {
    $group_room_id = $_GET['group_room_id'];
    return !Groups::getCurrentGroup($student->user_id, $group_room_id);
});

// Handle form submission for adding a student to a group
if (isset($_POST['addToGroupButton'])) {
    $studentId = $_POST['studentSelect'];
    $groupId = $_POST['groupSelect'];

    // Add the student to the group
    Groups::addToGroup($studentId, $groupId);

    // Construct the redirect URL
    $url = 'groups.php?group_room_id=' . urlencode($group_room_id) . '&section_id=' . urlencode($section_id);

    // Redirect to the same page
    header("Location: " . $url);
    exit;
}

// Handle form submission for adding a new group
if (isset($_POST['addGroupButton'])) {
    // Add a new group
    Groups::addGroup($group_room_id);

    // Redirect to the same page
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Handle form submission for leaving a group
if (isset($_POST['leaveGroupButton'])) {
    $studentId = $_POST['studentId'];
    $groupId = $_POST['groupId'];

    // Remove the student from the group
    Groups::removeFromGroup($studentId, $groupId);

    // Redirect to the same page
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// Handle form submission for removing empty groups
if (isset($_POST['removeEmptyGroupsButton'])) {
    // Remove any empty groups
    Groups::removeEmptyGroups();

    // Redirect to the same page
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!-- Form for selecting a student and a group and adding the student to the group -->
<form method="post">
    <select name="studentSelect">
        <?php foreach ($studentsNotInGroup as $student) : ?>
            <option value="<?php echo $student->user_id; ?>"><?php echo User::getFullName($student->user_id) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="groupSelect">
        <?php foreach ($groups as $groupOption) : ?>
            <option value="<?php echo $groupOption->group_id; ?>"><?php echo $groupOption->name; ?></option>
        <?php endforeach; ?>
    </select>

    <input type="submit" name="addToGroupButton" value="Add to Group">
</form>
<div class="group-container">
    <?php
    foreach ($groups as $group) {
    ?>
        <div class="group-box">
            <h4 class="group-name"><? echo $group->name; ?> medlemmer:</h4>
            <?php
            $participants = Groups::getGroupsParticipants($group->group_id);
            ?>

            <div>
                <?php foreach ($participants as $participant) : ?>
                    <div class="participant">
                        <h6><?php
                            if (isset($participant->student)) {
                                echo User::getFullName($participant->student);
                            }
                            ?></h6>

                        <!-- Form for leaving a group -->
                        <?php if (Groups::isInGroup($participant->student, $group->group_id) && $participant->student == $user->data()->user_id) : ?>
                            <form method="post">
                                <input type="hidden" name="studentId" value="<?php echo $participant->student; ?>">
                                <input type="hidden" name="groupId" value="<?php echo $group->group_id; ?>">
                                <input type="submit" name="leaveGroupButton" value="Leave Group">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
    }
    ?>
</div>

<!-- Form for adding a new group -->
<form method="post">
    <input type="submit" name="addGroupButton" value="Add Group">
</form>

<!-- Form for removing empty groups -->
<form method="post">
    <input type="submit" name="removeEmptyGroupsButton" value="Remove Empty Groups">
</form>