<?php

# Admin Controller

// Admin Dashboard
function admin_dashboard()
{
    require_admin();

    // Stats
    $totalEvents        = event_count();
    $upcomingEvents     = event_count('upcoming');
    $totalRegistrations = registration_count();
    $totalUsers         = user_count();

    // Recent events
    $recentEvents = event_get_all([], 5);

    // LIVE SEAT SUMMARY 
    foreach ($recentEvents as &$event) {
        $seatSummary = event_get_seat_summary($event['id']);

        $event['total_capacity']  = (int)$seatSummary['total_capacity'];
        $event['available_seats'] = max(0, (int)$seatSummary['available_seats']);
    }
    unset($event);

    view('admin/dashboard', [
        'stats' => [
            'total_events'        => $totalEvents,
            'upcoming_events'     => $upcomingEvents,
            'total_registrations' => $totalRegistrations,
            'total_users'         => $totalUsers
        ],
        'recentEvents' => $recentEvents
    ]);
}

# Manage Events

// View Events
function admin_events()
{
    require_admin();

    $filters = [];
    if (!empty($_GET['search'])) {
        $filters['search'] = sanitize($_GET['search']);
    }

    $events = event_get_all($filters);

    // 🔥 LIVE SEAT SUMMARY
    foreach ($events as &$event) {
        $seatSummary = event_get_seat_summary($event['id']);

        $event['total_capacity']  = (int)$seatSummary['total_capacity'];
        $event['available_seats'] = max(0, (int)$seatSummary['available_seats']);
    }
    unset($event);

    view('admin/events', [
        'events' => $events,
        'search' => $filters['search'] ?? null
    ]);
}

// View Event Details
function admin_viewEvent($id)
{
    require_admin();

    $event = event_get_by_id($id);
    if (!$event) {
        redirect('/admin/events');
    }

    // ✅ CORRECT: ticket availability from registrations
    $ticketTypes = ticket_types_with_availability($id);

    // ✅ CORRECT: event seat summary
    $seatSummary = event_get_seat_summary($id);

    $registrations = registration_get_by_event($id);

    view('admin/event-details', [
        'event'               => $event,
        'ticketTypes'         => $ticketTypes,
        'registrations'       => $registrations,
        'totalRegistrations'  => count($registrations),
        'calculatedAvailable' => (int)$seatSummary['available_seats'],
        'calculatedCapacity'  => (int)$seatSummary['total_capacity']
    ]);
}


// Create Event
function admin_createEvent()
{
    require_admin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'title'       => sanitize($_POST['title']),
            'category_id' => $_POST['category_id'],
            'description' => sanitize($_POST['description']),
            'venue'       => sanitize($_POST['venue']),
            'event_date'  => $_POST['event_date'],
            'event_time'  => $_POST['event_time'],
            'capacity'    => $_POST['capacity'],
            'status'      => sanitize($_POST['status']),
            'created_by'  => $_SESSION['user_id'],
        ];

        // Image upload
        $data['image'] = 'default.jpg';
        if (!empty($_FILES['event_image']['name'])) {
            $ext = pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION);
            $data['image'] = 'event_' . time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file(
                $_FILES['event_image']['tmp_name'],
                __DIR__ . '/../public/uploads/events/' . $data['image']
            );
        }

        $eventId = event_create($data);

        // Ticket tiers
        if ($eventId && !empty($_POST['tier_name'])) {
            $tiers = [];
            foreach ($_POST['tier_name'] as $i => $name) {
                if (!empty($name)) {
                    $tiers[] = [
                        'name'          => $name,
                        'description'   => $_POST['tier_description'][$i] ?? '',
                        'price'         => $_POST['tier_price'][$i],
                        'capacity'      => $_POST['tier_capacity'][$i],
                        'display_order' => $_POST['tier_order'][$i] ?? 0
                    ];
                }
            }
            ticket_type_create_bulk($eventId, $tiers);
        }

        redirect('/admin/events');
    }

    view('admin/create-event', [
        'categories' => category_get_all()
    ]);
}

// Edit Event
function admin_editEvent($id)
{
    require_admin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'title'       => sanitize($_POST['title']),
            'category_id' => $_POST['category_id'],
            'description' => sanitize($_POST['description']),
            'venue'       => sanitize($_POST['venue']),
            'event_date'  => $_POST['event_date'],
            'event_time'  => $_POST['event_time'],
            'capacity'    => $_POST['capacity'],
            'status'      => $_POST['status'],
        ];

        event_update($id, $data);
        $_SESSION['success'] = "Event updated successfully.";
        redirect('/admin/events');
    }

    view('admin/edit-event', [
        'event'       => event_get_by_id($id),
        'categories'  => category_get_all(),
        'ticketTypes' => ticket_types_with_availability($id)
    ]);
}

// Delete Event
function admin_deleteEvent($id)
{
    require_admin();
    event_delete($id);
    $_SESSION['success'] = "Event deleted successfully.";
    redirect('/admin/events');
}

// Manage Categories

// View Categories
function admin_categories()
{
    require_admin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (category_name_exists(sanitize($_POST['name']))) {
            $_SESSION['error'] = "Category name already exists.";
            redirect('/admin/categories');
        }

        category_create(sanitize($_POST['name']));
        $_SESSION['success'] = "Category created successfully.";
        redirect('/admin/categories');
    }

    view('admin/categories', [
        'categories' => category_get_all()
    ]);
}


// Edit Category
function admin_editCategory($id)
{
    require_admin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (category_name_exists(sanitize($_POST['name']))) {
            $_SESSION['error'] = "Category name already exists.";
            redirect('/admin/categories');
        }
        category_update($id, sanitize($_POST['name']));
        $_SESSION['success'] = "Category updated successfully.";
        redirect('/admin/categories');
    }

    view('admin/categories', [
        'categories' => category_get_all()
    ]);
}

// Delete Category
function admin_deleteCategory($id)
{
    require_admin();

    category_delete($id);
    $_SESSION['success'] = "Category deleted successfully.";
    redirect('/admin/categories');
}


// Manage Registrations
function admin_registrations()
{
    require_admin();

    view('admin/registrations', [
        'registrations' => registration_get_all()
    ]);
}

// User Management

// View Users
function admin_users()
{
    require_admin();

    view('admin/users', [
        'users' => user_get_all()
    ]);
}

function admin_createUser()
{
    require_admin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'first_name' => sanitize($_POST['first_name']),
            'last_name'  => sanitize($_POST['last_name']),
            'email'      => sanitize($_POST['email']),
            'phone'      => sanitize($_POST['phone']),
            'password'   => $_POST['password'],
            'role'       => $_POST['role']
        ];

        // Basic validation
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
            $_SESSION['error'] = "All fields are required.";
            redirect('/admin/create-user');
        }

        if (user_email_exists($data['email'])) {
            $_SESSION['error'] = "Email already exists.";
            redirect('/admin/create-user');
        }

        user_create($data);
        $_SESSION['success'] = "User created successfully.";
        redirect('/admin/users');
    }

    view('admin/create-user');
}


// Delete User
function admin_deleteUser($id)
{
    require_admin();

    if ($id != $_SESSION['user_id']) {
        user_delete($id);
    }

    $_SESSION['success'] = "User deleted successfully.";
    redirect('/admin/users');
}
