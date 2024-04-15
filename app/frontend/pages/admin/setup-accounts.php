<!DOCTYPE html>
<html>

<body>
    <div class="container">
        <h1>Opstil konto</h1>

        <?php
        // Enable error reporting for debugging
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once 'app/backend/core/Init.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            error_log("POST request received");
            if (Token::check(Input::get('csrf_token'))) {
                error_log("Token check passed");
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
                        'middle_name' => Input::get('middleName'), // Add this line
                        'last_name' => Input::get('lastName'),
                        'address' => Input::get('address'),
                        'birthdate' => date('Y-m-d H:i:s', strtotime(Input::get('birthdate'))),
                        'start_date' => date('Y-m-d H:i:s', strtotime(Input::get('schoolStart'))),
                        'access_level' => Input::get('accessLevel') == 'student' ? 1 : (Input::get('accessLevel') == 'teacher' ? 2 : 3),
                        // Assuming default values for these fields
                        'profile_picture' => null,
                        'end_date' => null,
                        'last_online' => date('Y-m-d H:i:s'),
                    );

                    var_dump($user_data); // Debug user data

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
        }
        ?>

        <form action="setup-accounts.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="firstName" class="form-label">Fornavn</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>
            <div class="mb-3">
                <label for="middleName" class="form-label">Mellemnavn</label>
                <input type="text" class="form-control" id="middleName" name="middleName"> <!-- Add this line -->
            </div>
            <div class="mb-3">
                <label for="lastName" class="form-label">Efternavn</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Adresse</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Fødselsdato</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
            </div>
            <div class="mb-3">
                <label for="schoolStart" class="form-label">Skolestart</label>
                <input type="date" class="form-control" id="schoolStart" name="schoolStart" required>
            </div>
            <div class="mb-3">
                <label for="accessLevel" class="form-label">Adgangsniveau</label>
                <select class="form-select" id="accessLevel" name="accessLevel" required>
                    <option value="student">Studerende</option>
                    <option value="teacher">Lærer</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Opret konto</button>
        </form>

        <?php
        if (Session::exists('register-success')) {
            echo '<div class="alert alert-success"><strong></strong>' . Session::flash('register-success') . '<a href="login.php"> Login Here</a></div>';
        } else if (Session::exists('register-error')) {
            echo '<div class="alert alert-danger"><strong></strong>' . Session::flash('register-error') . '</div>';
        }
        ?>
    </div>
</body>

</html>