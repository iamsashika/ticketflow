<?php


// Registration Page or Handle Registration
function user_register_page()
{
    if (is_logged_in()) {
        redirect('/home');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        $data = [];
        $data['first_name'] = sanitize($_POST['first_name'] ?? '');
        $data['last_name'] = sanitize($_POST['last_name'] ?? '');
        $data['email'] = sanitize($_POST['email'] ?? '');
        $data['phone'] = sanitize($_POST['phone'] ?? '');
        $data['role'] = 'user';
        $data['password'] = $_POST['password'] ?? '';
        $confirmPassword  = $_POST['confirm_password'] ?? '';



        $nameRegex  = '/^[A-Za-z][A-Za-z\s]{2,}$/'; // At least 3 letters, letters and spaces only
        $phoneRegex = '/^0\d{9}$/';               // 10 digits starting with 0

        if (
            !preg_match($nameRegex, $data['first_name']) ||
            !preg_match($nameRegex, $data['last_name']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            !preg_match($phoneRegex, $data['phone']) ||
            strlen($data['password']) < 3 ||
            $data['password'] !== $confirmPassword
        ) {
            $_SESSION['error'] = 'Input validation failed';
            redirect('/user/register');
        }


        if (user_email_exists($data['email'])) {
            $_SESSION['error'] = 'Input validation failed';
            redirect('/user/register');
        }


        $userId = user_create($data);

        if ($userId) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            redirect('/user/login');
        } else {
            $_SESSION['error'] = 'Registration failed';
            redirect('/user/register');
        }
    } else {
        view('user/register');
    }
}

// Login Page or Handle Login
function user_login_page()
{
    if (is_logged_in()) {
        redirect('/home');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email    = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
            $_SESSION['error'] = 'Invalid email or password';
            redirect('/user/login');
        }

        $user = user_login($email, $password);

        if ($user) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['role']      = $user['role'];

            $_SESSION['success'] = 'Welcome back, ' . $user['first_name'] . '!';

            if ($user['role'] === 'admin') {
                redirect('/admin/dashboard');
            } else {
                redirect('/home');
            }
        } else {
            $_SESSION['error'] = 'Invalid email or password';
            redirect('/user/login');
        }
    } else {
        view('user/login');
    }
}

// Handle Logout
function user_logout()
{
    session_destroy();

    session_start();
    $_SESSION['success'] = 'Logged out successfully';

    redirect('/home');
}

// User Profile
function user_profile()
{
    require_login();

    $userId = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $data = [];
        $data['first_name'] = sanitize($_POST['first_name'] ?? '');
        $data['last_name']  = sanitize($_POST['last_name'] ?? '');
        $data['email']      = sanitize($_POST['email'] ?? '');
        $data['phone']      = sanitize($_POST['phone'] ?? '');


        $nameRegex  = '/^[A-Za-z][A-Za-z\s]{2,}$/'; // At least 3 letters, letters and spaces only
        $phoneRegex = '/^0\d{9}$/';               // 10 digits starting with 0


        if (
            !preg_match($nameRegex, $data['first_name']) ||
            !preg_match($nameRegex, $data['last_name']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            !preg_match($phoneRegex, $data['phone'])
        ) {
            $_SESSION['error'] = 'Input validation failed';
            redirect('/user/profile');
        }

        // check if email is changing and already exists
        $currentUser = user_get_by_id($userId);

        if ($currentUser['email'] !== $data['email'] && user_email_exists($data['email'])) {
            $_SESSION['error'] = 'Email already exists';
            redirect('/user/profile');
        }

        user_update($userId, $data);
        $_SESSION['success'] = 'Profile updated';
        $_SESSION['user_name'] = $data['first_name'];
    }

    $user = user_get_by_id($userId);

    $data = [];
    $data['user'] = $user;

    view('user/profile', $data);
}

//  Event registrations
function user_registrations()
{
    require_login();

    $userId = $_SESSION['user_id'];
    $registrations = registration_get_by_user($userId);

    $data = [];
    $data['registrations'] = $registrations;

    view('user/my-registrations', $data);
}
