<?php
if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate = new Validation();

        $validation = $validate->check($_POST, array(
            'current_password' => array(
                'required' => true,
                'min' => 6,
                'verify' => 'password'
            ),
            'new_password' => array(
                'optional' => true,
                'min' => 6,
                'bind' => 'confirm_new_password'
            ),
            'confirm_new_password' => array(
                'optional' => true,
                'min' => 6,
                'match' => 'new_password',
                'bind' => 'new_password',
            ),
            'email' => array(
                'required' => true
            ),
        ));

        if ($validation->passed()) {
            try {
                $updateFields = array();
                if (Input::get('new_password') != null) {
                    $updateFields['password'] = Password::hash(Input::get('new_password'));
                }
                if (Input::get('email') != null) {
                    $email = Input::get('email');
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $updateFields['email'] = $email;
                    } else {
                        throw new Exception('Invalid email format.');
                    }
                }

                if (!empty($updateFields)) {
                    $user->update($updateFields);
                    Session::flash('update-success', 'Profile successfully updated!');
                    Redirect::to('index.php');
                } else {
                    Redirect::to('logout.php');
                }
            } catch (Exception $e) {
                die($e->getMessage());
            }
        } else {
            echo '<div class="alert alert-danger"><strong></strong>' . cleaner($validation->error()) . '</div>';
        }
    }
}
