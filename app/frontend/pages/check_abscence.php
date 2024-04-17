<?php
if (!isset($_POST['Day']) || !isset($_POST['classID'])) {
    die('Day or class ID not set in the POST request.');
}

// Check if day and class_id are set in the POST request
if (isset($_POST['day']) && isset($_POST['class_id'])) {
    // Get the day and class_id from the POST request
    $day = $_POST['day'];
    $class_id = $_POST['class_id'];
} else {
    // Handle the case where day or class_id are not set in the POST request
    echo "Day or class ID not set in the POST request.";
    exit;
}
var_dump($_POST);
// Specify the class ID
$class_id = '2'; // Replace this with the actual class ID

// Fetch the students in the class
$students = Classes::getAllStudents($class_id);

// Decide which date to use
$date = date('d') <= 17 ? date('Y-m-d') : date('Y-m-17');

// Fetch the time modules for the class for the given date
$time_modules = Classes::getAllTimeModuleByClass($class_id, $date);

// Fetch the subjects for all students for today
$all_subjects = [];
foreach ($students as $student) {
    $subjects = Calender::getAllSubjectsForPersonForADay($date, $student->user_id);
    if (is_object($subjects) && get_class($subjects) == 'Database' && $subjects->count()) {
        $subjects = $subjects->results();
    }
    // sort
    usort($subjects, function ($a, $b) {
        return strtotime($a->start_time) - strtotime($b->start_time);
    });
    $all_subjects = array_merge($all_subjects, $subjects);
}

usort($time_modules, function ($a, $b) {
    return strtotime($a->start_time) - strtotime($b->start_time);
});

// Start the table
echo "<table>";

// Add main table header
echo "<tr>";
echo "<th>Navn</th>";

// Add a column for each time module
foreach ($time_modules as $time_module) {
    // Find the subject for this time module
    $subject_name = '';
    foreach ($all_subjects as $subject) {
        if ($subject->start_time == $time_module->start_time && $subject->end_time == $time_module->end_time) {
            $subject_name = $subject->name . ": ";
            break;
        }
    }

    // Only display the hours
    $start_time = date('H:i', strtotime($time_module->start_time));
    $end_time = date('H:i', strtotime($time_module->end_time));

    // Add a checkbox next to the time
    echo "<th>" . $subject_name . $start_time . " - " . $end_time . "<input type='checkbox' class='header_checkbox' data-time-module='" . $time_module->time_module . "'></th>";
}
echo "</tr>";

foreach ($students as $student) {
    // Get the student's full name
    $name = User::getFullName($student->user_id); // Replace 'user_id' with the actual property for the student's ID

    // Create a row for each student
    echo "<tr>";
    echo "<td>" . $name . "</td>";

    // Add a cell for each time module
    foreach ($time_modules as $time_module) {
        echo "<td>";
        echo "<input type='checkbox' class='time_module_" . $time_module->time_module . "' name='absent_students_time_module_" . $time_module->time_module . "[]' value='" . $student->user_id . "'>"; // Replace 'user_id' with the actual property for the student's ID
        echo "<select name='attendance_time_module_" . $time_module->time_module . "_" . $student->user_id . "'>";
        echo "<option value='' selected>'''</option>";
        echo "<option value='present'>Tilstede</option>";
        echo "<option value='absent'>Fraværende</option>";
        echo "</select>"; // Replace 'user_id' with the actual property for the student's ID
        echo "</td>";
    }
    echo "</tr>";
}
echo "</table>";
?>

<script>
    // When a checkbox is clicked
    document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            // Display a popup asking for confirmation
            if (!confirm('Are you sure this person or everyone is present?')) {
                // If the user clicked "Cancel", uncheck the checkbox
                this.checked = false;
            }
        });
    });
    // When a checkbox in the header is clicked
    document.querySelectorAll('th input.header_checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            // Check or uncheck all checkboxes in the same column
            var time_module = this.dataset.timeModule;
            var checked = this.checked;
            document.querySelectorAll('td input.time_module_' + time_module).forEach(function (checkbox) {
                checkbox.checked = checked;
            });
        });
    });

    // When the chekbox in the student gets unchecked then no warning message will be shown

    document.querySelectorAll('td input').forEach(function (checkbox) {
        checkbox.addEventListener('click', function () {
            // Display a popup asking for confirmation
            if (!confirm('Are you sure this person or everyone is present?')) {
                // If the user clicked "Cancel", uncheck the checkbox
                this.checked = false;
            }
        });
    });

    // When a dropdown option is selected
    document.querySelectorAll('select').forEach(function (dropdown) {
        dropdown.addEventListener('change', function () {
            // Check the selected option
            var selectedOption = this.options[this.selectedIndex].value;
            if (selectedOption === 'present' || selectedOption === 'absent') {
                // Make the dropdown wider when an option is selected
                this.style.width = '150px';
            } else {
                // Make the dropdown narrower when other options are selected
                this.style.width = '40px';
            }
        });
    });
</script>

<style>
    table th,
    table td {
        padding: 10px;
    }

    select {
        width: 40px;
    }
</style>