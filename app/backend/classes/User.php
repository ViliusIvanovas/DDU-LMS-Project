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

    public static function create($data)
    {
        // Get the instance of the Database class
        $database = Database::getInstance();

        // Insert the record into the users table and get the last inserted ID
        $lastInsertedId = $database->insert('users', $data);

        // If insertion failed, throw an exception
        if ($lastInsertedId === false) {
            throw new Exception("Unable to create the user.");
        }

        // Return the ID of the last inserted row
        return $lastInsertedId;
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
        return (!empty($this->_data)) ? true : false;
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
        $al = $this->data()->access_level;

        if ($al == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function isTeacher()
    {
        $al = $this->data()->access_level;

        if ($al == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isDeceased()
    {
        // rebuild this method
    }

    public function inExamPeriod()
    {
        // rebuild this method
    }

    public static function getAllUsers() {
        $db = Database::getInstance();
        $results = $db->query("SELECT * FROM users ORDER BY user_id ASC")->results();
        return $results;
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

    public static function getFullName($user_id)
    {
        $user = Database::getInstance()->get('users', array('user_id', '=', $user_id));
        $user = $user->first();
        return $user->first_name . " " . $user->middle_name . " " . $user->last_name;
    }

    public static function getAllStudents()
    {
        $students = Database::getInstance()->query("SELECT * FROM users WHERE access_level = 1 ORDER BY user_id ASC");
        //return list of students
        return $students;
    }

    public static function getAllTeachers()
    {
        $teachers = Database::getInstance()->query("SELECT * FROM users WHERE access_level = 2 ORDER BY user_id ASC");
        //return list of teachers
        return $teachers;
    }

    public static function getAllAdmins()
    {
        $admins = Database::getInstance()->query("SELECT * FROM users WHERE access_level = 3 ORDER BY user_id ASC");
        //return list of admins
        return $admins;
    }

    public static function isUserTeacherForClass($user_id, $class_id)
    {
        $teacher = Database::getInstance()->query("SELECT * FROM class_teachers WHERE teacher_id = $user_id AND class_id = $class_id");
        if ($teacher->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function isUserStudentForClass($user_id, $class_id)
    {
        $student = Database::getInstance()->query("SELECT * FROM class_students WHERE student_id = $user_id AND class_id = $class_id");
        if ($student->count() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
