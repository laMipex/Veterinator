    <?php
    header('X-Frame-Options: SAMEORIGIN');
    session_start();
    require "../db_config.php";
    require "functions.php";

    header("Content-Type: application/json");
    $data = json_decode(file_get_contents("php://input"), true);

    $idUser = $data['id_user'] ?? '';
    $negativePoints = $data['negative_points'] ?? '';

    $error = false;
    $responseMessage = "User banned successfully!";

    if($negativePoints <= 2){
        $error = true;
        $responseMessage = "User must have at least 3 negative points to be banned";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {


        if (empty($idUser)) {
            $error = true;
            $responseMessage = "User ID is missing.";
        }

        if (!$error) {
            try {

                // Update the reservation to cancel it
                $banSql = "UPDATE users SET is_banned = 1 WHERE id_user = :id_user";
                $stmt = $pdo->prepare($banSql);
                $stmt->bindParam(':id_user', $idUser, PDO::PARAM_INT);
                $stmt->execute();

            } catch (PDOException $e) {
                $error = true;
                $responseMessage = "Database error: " . $e->getMessage();
            }
        }

    } else {
        $error = true;
        $responseMessage = "Data are not sent using method POST";
    }

$response = [
    "success" => !$error,
    "message" => $responseMessage,
    "status" => $error ? 'ERROR' : 'OK'
];

echo json_encode($response);
?>
