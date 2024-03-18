<?php
require_once 'app/backend/core/Init.php';

if (Input::exists()) {
    if (Token::check(Input::get('csrf_token'))) {
        $validate   = new Validation();

        $validation = $validate->check($_POST, array(
            'mail'    => array(
                'required'  => true,
            ),

            'password'  => array(
                'required'  => true
            )
        ));

        if ($validation->passed()) {
            $remember   = true;
            $login      = $user->login(Input::get('mail'), Input::get('password'), $remember);
            if ($login) {
                Session::flash('login-success', 'You have successfully logged in!');
                Redirect::to('index.php');
            } else {
                echo '<div class="alert alert-danger"><strong></strong>Incorrect Credentials! Please try again...</div>';
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo '<div class="alert alert-danger"><strong></strong>' . cleaner($error) . '</div>';
            }
        }
    }
}
