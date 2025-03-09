<?php
include_once('db.php');
include_once('model.php');

/**
 * @return void
 * Output transactions data
 */
function get_data()
{
    /**
     * Get user identifier from query string
     */
    $user_id = isset($_GET['user'])
        ? (int)$_GET['user']
        : null;

    /**
     * Validation
     */
    if (!$user_id) {
        http_response_code(400);
        echo "\"user\" not provided";
        return;
    }

    $conn = get_connect();
    $data = get_user_transactions_balances($user_id, $conn);

    echo json_encode($data);
}
