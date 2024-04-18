<h1>Karakter</h1>
<?php
$user_id = $user->data()->user_id;
$grades = Grades::getGradesByUserid($user_id);
?>
<table>
    <tr>
        <th>Release Date</th>
        <th>Klasse</th>
        <th>Karakter</th>
    </tr>
    <?php
    if ($grades) {
        foreach ($grades as $grade) {
            $room_id = $grade->room;
            $room = Rooms::getRoomById($room_id);
            $release_time = new DateTime($grade->release_time, new DateTimeZone('Europe/Copenhagen'));
            $now = new DateTime();
            $now->setTimezone(new DateTimeZone('Europe/Copenhagen'));
            if ($release_time <= $now) {
                echo  "<tr><td>" . $release_time->format('d-m-Y') . "</td><td>" . $room->name . "</td><td>" . $grade->grade . "</td></tr>";
            }
        }
    } else {
        echo "<tr><td colspan='3'>No grades found for this user.</td></tr>";
    }
    ?>
</table>