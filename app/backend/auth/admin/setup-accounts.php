<?php
require_once 'app/backend/core/Init.php';
require_once 'app/backend/classes/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();

        // Get the arrays of values from the form
        $emails = $_POST['email'];
        $firstNames = $_POST['firstName'];
        $middleNames = $_POST['middleName'];
        $lastNames = $_POST['lastName'];
        $addresses = $_POST['address'];
        $birthdates = $_POST['birthdate'];
        $schoolStarts = $_POST['schoolStart'];
        $accessLevels = $_POST['accessLevel'];

        // Loop over the arrays
        for ($i = 0; $i < count($emails); $i++) {
            // Validate each set of values...

            // If validation passes, create a new user
            $user_data = array(
                'email' => $emails[$i],
                'first_name' => $firstNames[$i],
                'middle_name' => $middleNames[$i],
                'last_name' => $lastNames[$i],
                'address' => $addresses[$i],
                'birthdate' => date('Y-m-d H:i:s', strtotime($birthdates[$i])),
                'start_date' => date('Y-m-d H:i:s', strtotime($schoolStarts[$i])),
                'access_level' => $accessLevels[$i] == 'student' ? 1 : ($accessLevels[$i] == 'teacher' ? 2 : 3),
            );

            // Insert the user into the database...
            User::create($user_data);
        }
    }
}
?>
