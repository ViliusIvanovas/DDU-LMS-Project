<?php
echo 'Script started<br>';

require_once 'app/backend/core/Init.php';

echo 'Included Init.php<br>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo 'POST request received<br>';

    var_dump($_POST); // Debug POST data

    
        $requiredFields = ['email', 'firstName', 'lastName', 'address', 'birthdate', 'schoolStart', 'accessLevel'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                $missingFields[] = $field;
            }
        }

        var_dump($missingFields); // Debug missing fields

        if (empty($missingFields)) {
            error_log("No missing fields");
            $password = 'studx' . date('Ymd', strtotime(Input::get('birthdate')));
            $hashedPassword = Password::hash($password);

            $user_data = array(
                'email' => Input::get('email'),
                'password' => $hashedPassword,
                'first_name' => Input::get('firstName'),
                'middle_name' => Input::get('middleName'),
                'last_name' => Input::get('lastName'),
                'address' => Input::get('address'),
                'birthdate' => date('Y-m-d H:i:s', strtotime(Input::get('birthdate'))),
                'start_date' => date('Y-m-d H:i:s', strtotime(Input::get('schoolStart'))),
                'access_level' => Input::get('accessLevel') == 'student' ? 1 : (Input::get('accessLevel') == 'teacher' ? 2 : 3),
                'profile_picture' => null, // Assuming this is an integer field
                'end_date' => null, // Assuming this is a timestamp field
                'last_online' => date('Y-m-d H:i:s'),
            );

            try {
                if (User::create($user_data)) {
                    error_log("User created successfully");
                    Session::flash('register-success', 'User created successfully.');
                    Redirect::to('login.php');
                } else {
                    error_log("Failed to create user");
                    Session::flash('register-error', 'Failed to create user.');
                }
            } catch (Exception $e) {
                error_log('Caught exception: ' . $e->getMessage());
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
        } else {
            error_log("Missing fields: " . implode(", ", $missingFields));
            foreach ($missingFields as $field) {
                Session::flash('register-error', $field . ' is required.');
            }
        }
    } else {
        error_log("Token check failed");
    }


