<?php
/**
 * This file acts as a basic router that handles incoming requests
 * and directs them to the appropriate function based on the 'route'
 * parameter in the URL.
 *
 * This router was created due to the fact that the configuration
 * of the server’s web does not call for
 * the turn to *.php the file directly.
 */

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
