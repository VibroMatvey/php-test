<?php

/**
 * Return list of users.
 */
function get_users($conn): array
{
    $users = [];

    $users_data = $conn->query('
        SELECT u.id, u.name
        FROM users u
                 INNER JOIN user_accounts ua ON u.id = ua.user_id
                 INNER JOIN transactions t ON t.account_from = ua.id OR t.account_to = ua.id
        GROUP BY u.id
    ');

    while ($row = $users_data->fetch()) {
        $users[$row['id']] = $row['name'];
    }

    return $users;
}

/**
 * Return transactions balances of given user.
 */
function get_user_transactions_balances($user_id, $conn): array
{
    /**
     * Now 2025 year, and in migration 2024 year, imagine that now 2024 year :)
     * If there were current data in migration, use this:
     *
     * $now_year = (new \DateTime())->format("Y");
     */
    $now_year = "2024";
    $result = [];

    $user_transactions_data = $conn->query("
        WITH user_accounts_filtered AS (
            SELECT id FROM user_accounts WHERE user_id = $user_id
        ),
             month_transactions AS (
                 SELECT
                     t.account_from,
                     t.account_to,
                     t.amount,
                     t.trdate,
                     ua_from.user_id AS user_id,
                     (ua_from.user_id = ua_to.user_id) AS is_internal,
                     ua_from.id IN (SELECT id FROM user_accounts_filtered) AS is_outgoing,
                     ua_to.id IN (SELECT id FROM user_accounts_filtered) AS is_incoming
                 FROM transactions t
                          JOIN user_accounts ua_from ON ua_from.id = t.account_from
                          JOIN user_accounts ua_to ON ua_to.id = t.account_to
                 WHERE strftime('%Y', t.trdate) = '$now_year'
                   AND (ua_from.user_id = $user_id OR ua_to.user_id = $user_id)
             )
        SELECT
            strftime('%m', mt.trdate) AS month,
            SUM(
                    CASE
                        WHEN NOT mt.is_internal AND mt.is_outgoing THEN -mt.amount
                        WHEN NOT mt.is_internal AND mt.is_incoming THEN mt.amount
                        ELSE 0
                        END
            ) AS amount,
            COUNT(*) AS count
        FROM month_transactions mt
        GROUP BY month
        ORDER BY month;
    ");

    while ($row = $user_transactions_data->fetch()) {
        $result[] = [
            "month" => $row['month'],
            "amount" => $row['amount'],
            "count" => $row['count'],
        ];
    }

    return $result;
}
