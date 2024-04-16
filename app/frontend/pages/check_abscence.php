<?php
// Specify the class ID
$class_id = '2'; // Replace this with the actual class ID

// Fetch the students in the class
$students = Classes::getAllStudents($class_id);

// Fetch the time modules for the class for today
//$time_modules = TimeModule::getAllForClassToday($class_id);

// Start the table
echo "<table>";

// Add main table header
echo "<tr>";
echo "<th colspan='3'>Student Attendance</th>";
echo "</tr>";

// Add table headers
echo "<tr>";
echo "<th>Navn</th>";
echo "<th>Tilstede</th>";
echo "<th>Time Modules</th>";
echo "</tr>";

// Loop through the students
foreach ($students as $student) {
    // Get the student's full name
    $name = User::getFullName($student->user_id); // Replace 'user_id' with the actual property for the student's ID

    // Create a row for each student
    echo "<tr>";
    echo "<td>" . $name . "</td>";
    echo "<td><input type='checkbox' name='absent_students[]' value='" . $student->user_id . "'></td>"; // Replace 'user_id' with the actual property for the student's ID

    // Display the time modules
    echo "<td>";
    foreach ($time_modules as $time_module) {
        echo $time_module->start_time . " - " . $time_module->end_time . "<br>"; // Replace 'start_time' and 'end_time' with the actual properties for the time module's start and end times
    }
    echo "</td>";

    echo "</tr>";
}

// End the table
echo "</table>";
?>