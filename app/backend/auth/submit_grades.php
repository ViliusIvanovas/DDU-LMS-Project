<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grades = $_POST['grades'];
    $release_time = $_POST['release_time'];
    $room_id = $_POST['room_id'];

    foreach ($grades as $student_id => $grade) {
        $fields = array(
            'student_id' => $student_id,
            'grade' => $grade,
            'release_time' => $release_time,
            'room_id' => $room_id
        );

        Grades::create($fields);
    }

    header('Location: rooms.php room_id=' . $room_id);
}