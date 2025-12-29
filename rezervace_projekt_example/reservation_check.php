<?php
$conn = mysqli_connect("localhost", "root", "", "reservations_example");

$pers_name = '';
$pers_password = '';
$res_id = '';
$room_name = '';
$confirm_delete_stage = false;

if (isset($_POST["res_check_Submit"])) {

    $pers_name = trim($_POST["pers_name"] ?? '');
    $pers_password = trim($_POST["pers_password"] ?? '');
    $res_id = trim($_POST["pers_ID"] ?? '');
    $room_name = trim($_POST["room_name"] ?? '');
    $confirm_delete = isset($_POST["confirm_delete"]);

    $valid = true;

    // ===== ověření polí =====
    foreach ([$pers_name, $pers_password] as $field) {
        if ($field === '') {
            echo "<script>alert('nezadali jste všechno info!')</script>";
            $valid = false;
            break;
        }
    }

    // ===== ověření uživatele =====
    if ($valid) {
        $result = $conn->query("
            SELECT person_password
            FROM user_list
            WHERE person_name = '$pers_name'
        ");

        if ($row = $result->fetch_assoc()) {
            if (!password_verify($pers_password, $row['person_password'])) {
                echo "<script>alert('špatné heslo!')</script>";
                $valid = false;
            }
        } else {
            echo "<script>alert('uživatel neexistuje!')</script>";
            $valid = false;
        }
    }

    // ===== ověřovaní a odebiraní rezervací =====
    if ($valid && $res_id !== '' && $room_name !== '') {

        $check = $conn->query("
            SELECT 1
            FROM room_res
            WHERE person_id = $res_id
            AND person_name = '$pers_name'
            AND room_name = '$room_name'
        ");

        if ($check && $check->num_rows === 1) {

            if ($confirm_delete) {
                $conn->query("
                    DELETE FROM room_res
                    WHERE person_id = $res_id
                    AND person_name = '$pers_name'
                    AND room_name = '$room_name'
                ");

                if ($conn->affected_rows === 1) {
                    echo "<p style='color:green;'>Rezervace byla úspěšně smazána!</p>";
                } else {
                    echo "<p style='color:red;'>Chyba při mazání rezervace!</p>";
                }

            } else {
                $confirm_delete_stage = true;
            }

        } else {
            echo "<script>alert('Rezervace s tímto ID a místností neexistuje!')</script>";
        }
    }

    // ===== vypis rezervaci =====
    if ($valid) {
        $res_result = $conn->query("
            SELECT
                person_id AS ID,
                room_name AS místnost,
                res_date AS datum,
                res_time_start AS začátek,
                res_time_end AS konec
            FROM room_res
            WHERE person_name = '$pers_name'
            ORDER BY res_date, res_time_start
        ");

        if ($res_result && $res_result->num_rows > 0) {
            echo "<p><strong>Vaše rezervace:</strong></p><hr>";
            while ($row = $res_result->fetch_assoc()) {
                echo "<div class='reservations'>";
                foreach ($row as $key => $value) {
                    echo "<p><strong>$key:</strong> $value</p>";
                }
                echo "</div><hr>";
            }
        } else {
            echo "<p>Zatím nemáte žádné rezervace.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ověření rezervace</title>
    <link rel="stylesheet" href="index1.css">
</head>
<body>
<form method="post" action="./reservation_check.php">
    <p>Jméno:</p>
    <input type="text" name="pers_name" value="<?= htmlspecialchars($pers_name) ?>">

    <p>Heslo:</p>
    <input type="password" name="pers_password">

    <p>ID rezervace:</p>
    <input type="text" name="pers_ID" value="<?=$res_id?>">

    <p>Název místnosti:</p>
    <input type="text" name="room_name" value="<?= htmlspecialchars($room_name) ?>">

    <?php if ($confirm_delete_stage): ?>
        <p style="color:orange;">
            Opravdu chcete tuto rezervaci smazat?
            Potvrďte opětovným odesláním formuláře.
        </p>
        <input type="hidden" name="confirm_delete" value="1">
    <?php endif; ?>

    <br>
    <input type="submit" name="res_check_Submit" value="Odeslat">
</form>
    <div id="overeni_rezervaci" class="section">
    <p>Pokud jsi zapoměl vytvořit uživatela, vrať si na hlavní stránku a vytvoř ho:</p>
    <a href="./index.php">Uvodní stránka</a>
    </div>
</body>
</html>