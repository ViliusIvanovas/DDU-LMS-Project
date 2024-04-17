<?php
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
        echo "<select name='attendance_time_module_" . $time_module->time_module . "_" . $student->user_id . "'><option value='present'>Tilstede</option><option value='absent'>Fraværende</option></select>"; // Replace 'user_id' with the actual property for the student's ID
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

</script>

<style>
    table th,
    table td {
        padding: 10px;
    }

    .dropdown_wrapper {
        position: relative;
        display: inline-block;
    }

    .attendance_dropdown {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: transparent;
        width: 30px;
        overflow: hidden;
        border: none;
        outline: none;
        color: transparent;
        /* Hide the text */
        text-shadow: 0 0 0 #000;
        /* Provide a shadow in a color users can see */
    }

    .dropdown_wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 15px;
        height: 100%;
        background: url('dropdown_arrow.png') no-repeat center;
        pointer-events: none;
    }

    .attendance_dropdown option {
        color: #000;
        /* Make the text visible again when the dropdown is open */
    }
</style>