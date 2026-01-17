<?php

# User Model

// Create User
function user_create($data)
{
    $role = $data['role'] ?? 'user';


    if (!in_array($role, ['user', 'admin'])) {
        $role = 'user';
    }

    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users
            (first_name, last_name, email, phone, password, role)
            VALUES
            (:first_name, :last_name, :email, :phone, :password, :role)";

    $params = [
        ':first_name' => $data['first_name'],
        ':last_name'  => $data['last_name'],
        ':email'      => $data['email'],
        ':phone'      => $data['phone'],
        ':password'   => $passwordHash,
        ':role'       => $role
    ];

    if (db_execute($sql, $params)) {
        return db_last_id();
    }

    return false;
}

// User Login
function user_login($email, $password)
{
    $sql = "SELECT *
            FROM users
            WHERE email = :email
              AND is_deleted = 0";

    $user = db_query_one($sql, [':email' => $email]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['email']     = $user['email'];
        $_SESSION['role']      = $user['role'];
        return $user;
    }

    return false;
}


// Check if email exists
function user_email_exists($email, $excludeId = null)
{
    $sql = "SELECT id
            FROM users
            WHERE email = :email
              AND is_deleted = 0";

    $params = [':email' => $email];

    if ($excludeId) {
        $sql .= " AND id != :id";
        $params[':id'] = $excludeId;
    }

    return (bool) db_query_one($sql, $params);
}

// Get User by ID
function user_get_by_id($id)
{
    $sql = "SELECT *
            FROM users
            WHERE id = :id
              AND is_deleted = 0";

    return db_query_one($sql, [':id' => $id]);
}

// Update User
function user_update($id, $data)
{
    $sql = "UPDATE users SET
                first_name = :first_name,
                last_name  = :last_name,
                email      = :email,
                phone      = :phone
            WHERE id = :id
              AND is_deleted = 0";

    $params = [
        ':id'         => $id,
        ':first_name' => $data['first_name'],
        ':last_name'  => $data['last_name'],
        ':email'      => $data['email'],
        ':phone'      => $data['phone']
    ];

    return db_execute($sql, $params);
}

// Get All Users
function user_get_all()
{
    $sql = "SELECT *
            FROM users
            WHERE is_deleted = 0
            ORDER BY created_at DESC";

    return db_query_all($sql);
}

// Count Users
function user_count()
{
    $sql = "SELECT COUNT(*) as count
            FROM users
            WHERE is_deleted = 0";

    $result = db_query_one($sql);
    return $result ? $result['count'] : 0;
}

// Soft Delete User
function user_delete($id)
{
    db_transaction_start();

    try {
        $sql = "UPDATE users
                SET is_deleted = 1
                WHERE id = :id";

        if (!db_execute($sql, [':id' => $id])) {
            throw new Exception('Failed to soft delete user');
        }

        $sql = "UPDATE events
                SET created_by = NULL
                WHERE created_by = :id";
        db_execute($sql, [':id' => $id]);

        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}
