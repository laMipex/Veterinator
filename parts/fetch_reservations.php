<?php
header('Content-Type: text/html; charset=UTF-8');

require "../db_config.php";
require "functions.php";

$date = isset($_POST['date']) ? $_POST['date'] : '';



$sql2 = "SELECT r.id_reservation, r.reservation_date, r.reservation_time, r.code, CONCAT(u.user_fname, ' ', u.user_lname) AS user_name,
    r.id_pet, p.pet_name, r.id_service, s.service_name, r.service_duration, r.code_verified, CONCAT(v.vet_fname, ' ', v.vet_lname) AS vet_name
    FROM reservations r
    INNER JOIN pet p ON p.id_pet = r.id_pet
    INNER JOIN users u ON p.id_user = u.id_user
    INNER JOIN vet v ON v.id_vet = r.id_vet
    INNER JOIN services s ON r.id_service = s.id_service
    WHERE r.reservation_date = :date
    ORDER BY r.reservation_date ASC, r.reservation_time ASC";

$query2 = $pdo->prepare($sql2);
$query2->execute([':date' => $date]);
$results2 = $query2->fetchAll(PDO::FETCH_ASSOC);

if ($query2->rowCount() > 0) {
    foreach ($results2 as $result) {
        echo '<tr style="border-bottom: 2px solid black;">
            <td>' . htmlspecialchars($result['reservation_date'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($result['reservation_time'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($result['user_name'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($result['pet_name'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($result['service_name'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($result['vet_name'], ENT_QUOTES, 'UTF-8') . '</td>
        </tr>';
    }
} else {
    echo '<tr><td colspan="6">Sorry, no reservation found for this date!</td></tr>';
}
?>
