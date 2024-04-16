<h1>Karakter</h1>
<?php
$user_id = $user->data()->user_id;
$grades = Grades::getGradesByUserid($user_id);
?>
<table>
    <tr>
        <th style="padding-right: 20px;">Release Date</th>
        <th style="padding-right: 20px;">Klasse</th>
        <th>Karakter</th>
    </tr>
<?php
if ($grades) {
    foreach ($grades as $grade) {
        // Get the room id from the grade
        $room_id = $grade->room;
        // Get the room by id
        $room = Rooms::getRoomById($room_id);
        // Convert release_time to a DateTime object and set the time zone
        $release_time = new DateTime($grade->release_time, new DateTimeZone('Europe/Copenhagen'));
        // Get the current time and set the time zone
        $now = new DateTime(null, new DateTimeZone('Europe/Copenhagen'));
        // If the release_time is in the future, display the grade
        if ($release_time <= $now) {
            echo  "<tr><td style='padding-right: 20px;'>" . $release_time->format('d-m-Y') . "</td><td style='padding-right: 20px;'>" . $room->name . "</td><td>" . $grade->grade . "</td></tr>";
        }
    }
} else {
    echo "<tr><td colspan='3'>No grades found for this user.</td></tr>";
}
?>
</table>