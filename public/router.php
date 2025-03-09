<?php
include_once('data.php');

$route = $_GET['route'] ?? null;

/**
 * List of available routes
 */
switch ($route) {
    case 'data':
        fetch_data();
        break;
    default:
}

/**
 * Implementation of a particular route
 */
function fetch_data()
{
    get_data();
    die();
}
