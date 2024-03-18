<?php

class User
{
    private $_db,
    $_data,
    $_sessionName,
    $_cookieName,
    $_isLoggedIn;

    public function __construct($user = null)
    {
        $this->_db = Database::getInstance();

        $this->_sessionName = Config::get('session/session_name');

        $this->_cookieName = Config::get('remember/cookie_name');

        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function update($fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->user_id;
        }

        if (!$this->_db->update('users', 'user_id', $id, $fields)) {
            throw new Exception('Unable to update the user.');
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception("Unable to create the user.");
        }
    }

    public function find($email = null)
    {
        if ($email) {
            $field = (is_numeric($email)) ? 'user_id' : 'email';
            $data = $this->_db->get('users', [$field, '=', $email]);

            if ($data->count()) {
                $this->_data = $data->first();
                return true;
            }
        }

        return false;
    }

    public function login($email = null, $password = null)
    {
        $user = $this->find($email);

        if ($user) {
            if (Password::check($password, $this->data()->password)) {
                Session::put($this->_sessionName, $this->data()->user_id);
                return true;
            }
        }

        return false;
    }

    public function exists()
    {
        return (!empty ($this->_data)) ? true : false;
    }

    public function logout()
    {
        $this->_db->delete('users_sessions', array('user_id', '=', $this->data()->user_id));

        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }

    public function isAdmin()
    {
        // rebuild this method
    }

    public function isDeceased()
    {
        // rebuild this method
    }

    public function inExamPeriod()
    {
        // rebuild this method
    }

    public function getClasses()
    {
        // rebuild this method
    }

    public static function getAllUsers()
    {
        $users = Database::getInstance()->query("SELECT * FROM users ORDER BY user_id ASC");
        //return list of users
        return $users;
    }

    public static function getUserById($user_id)
    {
        $user = Database::getInstance()->get('users', array('user_id', '=', $user_id));
        return $user->first();
    }

    public static function getGrades($user_id)
    {
        $grades = Database::getInstance()->query("SELECT * FROM grades WHERE user_id = $user_id ORDER BY grade_id ASC");
        //return list of grades
        return $grades;
    }

    public static function getAbsenceStatistics($user_id)
    {
        $absences = Database::getInstance()->query("SELECT * FROM absences WHERE user_id = $user_id ORDER BY absence_id ASC");
        //return list of absences
        return $absences;
    }

    public static function getAbsenceStatisticsInTimeFrame($user_id, $start_date, $end_date)
    {
        $absences = Database::getInstance()->query("SELECT * FROM absences WHERE user_id = $user_id AND date >= '$start_date' AND date <= '$end_date' ORDER BY absence_id ASC");
        //return list of absences
        return $absences;
    }
}
?>