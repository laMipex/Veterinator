<?php
session_start();
require "../db_config.php";
require "functions.php";

$id_vet = $_SESSION['id_vet'] ?? "";

$sql2 = "SELECT r.id_reservation, r.reservation_time, r.code, CONCAT(u.user_fname, ' ', u.user_lname) AS user_name,
       r.id_pet, p.pet_name, r.id_service, s.service_name, r.service_duration,r.code_verified
FROM reservations r
INNER JOIN pet p ON p.id_pet = r.id_pet
INNER JOIN users u ON p.id_user = u.id_user
INNER JOIN vet v ON v.id_vet = r.id_vet
INNER JOIN services s ON r.id_service = s.id_service
WHERE r.id_vet = :id_vet AND DATE(r.reservation_date) = CURDATE() AND r.code_verified = 0
ORDER BY r.reservation_time ASC";

$query2 = $pdo->prepare($sql2);
$query2->bindParam(':id_vet', $id_vet, PDO::PARAM_STR);
$query2->execute();
$results2 = $query2->fetchAll(PDO::FETCH_OBJ);

if ($query2->rowCount() > 0) {
    echo '
        <tr>
            <th>Time:</th>
            <th>User:</th>
            <th>Pet:</th>
            <th>Service:</th>
            <th>Didn\'t show up:</th>
            <th>Code:</th>
            <th>Submit:</th>
        </tr>';

    foreach ($results2 as $result) {
        echo '<tr style="border-bottom: 2px solid black;">
            <td>' . $result->reservation_time . '</td>
            <td>' . $result->user_name . '</td>
            <td>' . $result->pet_name . '</td>
            <td>' . $result->service_name . '</td>
            <td><input type="number" min="0" max="1" value="0" id="negative_points" style="width: 50px;"></td>
            <td> 
            <div class="clean">         
                <input type="text" id="codeInput" style="width: 100px;">    
                </div>         
                <input type="hidden" id="id_res" value="' . $result->id_reservation . '">  
            </td>
            <td><button type="button" id="submit">Submit</button></td>
        </tr>';
    }
} else {
    echo '<h1>Sorry, no reservation for today!</h1>';
}


