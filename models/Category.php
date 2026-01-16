<?php

// Category Model

// Get All Categories
function category_get_all()
{
    $sql = "SELECT * FROM categories WHERE is_deleted = 0 ORDER BY name ASC";
    return db_query_all($sql);
}

// Get Category Name by ID
function category_get_name($id)
{
    $sql = "SELECT name FROM categories WHERE id = :id AND is_deleted = 0";
    $params = [];
    $params[':id'] = $id;

    $result = db_query_one($sql, $params);

    if ($result) {
        return $result['name'];
    }
    return false;
}

// Create Category
function category_create($name)
{
    $sql = "INSERT INTO categories (name) VALUES (:name)";
    $params = [':name' => $name];
    if (db_execute($sql, $params)) {
        return db_last_id();
    }
    return false;
}

// Get Category by ID
function category_get_by_id($id)
{
    $sql = "SELECT * FROM categories WHERE id = :id AND is_deleted = 0";
    return db_query_one($sql, [':id' => $id]);
}

function category_name_exists($name)
{
    $sql = "SELECT id FROM categories WHERE name = :name and is_deleted = 0";
    $result = db_query_one($sql, [':name' => $name]);
    return $result ? true : false;
}

function category_id_exists($id)
{
    $sql = "SELECT id FROM categories WHERE id = :id";
    $result = db_query_one($sql, [':id' => $id]);
    return $result ? true : false;
}


function category_update($id, $name)
{
    echo $name;
    $sql = "UPDATE categories SET name = :name, updated_at = NOW() WHERE id = :id";
    return db_execute($sql, [':name' => $name, ':id' => $id]);
}

function category_delete($id)
{

    $sql = "UPDATE categories SET is_deleted = 1, updated_at = NOW() WHERE id = :id";
    return db_execute($sql, [':id' => $id]);
}
