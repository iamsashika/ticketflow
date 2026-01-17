<?php

// Index.php

session_start();

// core config and setup
require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'core/helpers.php';

// Models
require_once 'models/User.php';
require_once 'models/Event.php';
require_once 'models/Registration.php';
require_once 'models/Category.php';
require_once 'models/TicketType.php';


// Helpers
require_once 'helpers/event_helpers.php';

// Controllers
require_once 'controllers/HomeController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/EventController.php';
require_once 'controllers/RegistrationController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/PaymentController.php';


// Url parsing
$url = isset($_GET['url']) ? $_GET['url'] : 'home';
$url = trim($url, '/');
$parts = explode('/', $url);

$page   = $parts[0] ?? 'home';
$action = $parts[1] ?? 'index';
$id     = $parts[2] ?? null;


# Route

// Home Page
if ($page === 'home') {
    if ($action === 'about') {
        home_about();
    } elseif ($action === 'functionality') {
        home_functionality();
    } elseif ($action === 'help') {
        home_help();
    } else {
        home_index();
    }
}


// General Pages
elseif ($page === 'events') {
    event_index();
} elseif ($page === 'login') {
    user_login_page();
} elseif ($page === 'register') {
    user_register_page();
} elseif ($page === 'logout') {
    user_logout();
} elseif ($page === 'profile') {
    user_profile();
} elseif ($page === 'my-registrations') {
    user_registrations();
}


// Event Routes

elseif ($page === 'event') {
    if ($action === 'index') {
        event_index();
    } elseif ($action === 'details') {
        event_details($id);
    } else {
        event_index();
    }
}

// Payment Routes

elseif ($page === 'payment') {
    if ($action === 'review') {
        payment_review($id);
    } elseif ($action === 'form') {
        payment_form($id);
    } elseif ($action === 'process') {
        payment_process($id);
    } else {
        redirect('/home');
    }
}


// User Routes

elseif ($page === 'user') {
    if ($action === 'login') user_login_page();
    elseif ($action === 'register') user_register_page();
    elseif ($action === 'logout') user_logout();
    elseif ($action === 'profile') user_profile();
    elseif ($action === 'myRegistrations') user_registrations();
    else user_login_page();
}


// Registration Routes

elseif ($page === 'registration') {
    if ($action === 'register') {
        registration_register($id);
    } elseif ($action === 'cancel') {
        registration_cancel_action($id);
    } else {
        redirect('/home');
    }
}


// Admin Routes
elseif ($page === 'admin') {
    if ($action === 'dashboard') admin_dashboard();
    elseif ($action === 'events') admin_events();
    elseif ($action === 'viewEvent') admin_viewEvent($id);
    elseif ($action === 'createEvent') admin_createEvent();
    elseif ($action === 'editEvent') admin_editEvent($id);
    elseif ($action === 'deleteEvent') admin_deleteEvent($id);

    elseif ($action === 'registrations') admin_registrations();
    elseif ($action === 'users') admin_users();
    elseif ($action === 'deleteUser') admin_deleteUser($id);
    elseif ($action === 'createUser') admin_createUser();

    elseif ($action === 'categories') admin_categories();
    elseif ($action === 'editCategory') admin_editCategory($id);
    elseif ($action === 'deleteCategory') admin_deleteCategory($id);

    else redirect('/admin/dashboard');
}

// 404 Not Found

else {
    require_once 'views/errors/404.php';
}
