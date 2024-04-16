<?php
$room_id = $_GET['room_id'];
$class = Rooms::getClassByRoomId($room_id);
$students = Classes::getAllStudents($class->class_id);
?>

<div class="container">
    <h1>Giv karakter</h1>
    <form method="POST" action="submit_grades.php">
        <label for="release_time">Vælg frigivelsestidspunkt:</label>
        <input type="datetime-local" id="release_time" name="release_time" required>
        <div class="row">
            <table>
                <tr>
                    <th>Navn</th>
                    <th>Karakter</th>
                </tr>
                <?php
                // Loop through the students
                foreach ($students as $student) {
                    // Get the student's full name
                    $name = User::getFullName($student->user_id); // Replace 'user_id' with the actual property for the student's ID

                    // Create a row for each student
                    echo "<tr>";
                    echo "<td>" . $name . "</td>";
                    echo "<td>";
                    echo "<select name='grades[" . $student->user_id . "]'>";
                    echo "<option value='-3'>-3</option>";
                    echo "<option value='00'>00</option>";
                    echo "<option value='02'>02</option>";
                    echo "<option value='4'>4</option>";
                    echo "<option value='7'>7</option>";
                    echo "<option value='10'>10</option>";
                    echo "<option value='12'>12</option>";
                    echo "</select>";
                    echo "</td>";
                    echo "</tr>";
                }

                // End the table
                echo "</table>";
                ?>
        </div>
        <input type="submit" value="Indsend karakterer">
    </form>
</div>

<script>
    document.getElementById('gradeForm').addEventListener('submit', function(event) {
        var releaseTime = document.getElementById('release_time').value;
        if (!releaseTime) {
            event.preventDefault();
            document.getElementById('error').style.display = 'block';
        }
    });
</script>