<?php
$conn = mysqli_connect("localhost", "root", "", "reservations");
$pers_name = '';
$pers_password = '';
$res_id = '';
$confirm_delete_stage = false; // флаг стадии подтверждения
$room_name = '';

if (isset($_POST["res_check_Submit"])) {

    $pers_name = trim($_POST["pers_name"]);
    $pers_password = trim($_POST["pers_password"]);
    $res_id = trim($_POST["pers_ID"] ?? '');
    $confirm_delete = isset($_POST['confirm_delete']) ? true : false;
    $room_name = trim($_POST["room_name"]);

    $valid = true;

    // ===== Проверка имени и пароля =====
    if ($pers_name === '' || $pers_password === '') {
        echo "<script>alert('nezadali jste všechno info!')</script>";
        $valid = false;
    }

    if ($valid) {
        $result = $conn->query("
            SELECT person_password 
            FROM user_list 
            WHERE person_name = '$pers_name'
        ");

        if ($row = $result->fetch_assoc()) {
            if (!password_verify($pers_password, $row['person_password'])) {
                echo "<script>alert('špatné heslo pro toto jméno!')</script>";;
                $valid = false;
            }
        } else {
            echo "<script>alert('uživatel neexistuje!')</script>";
            $valid = false;
        }
    }
    if ($valid && $res_id !== '' && (!ctype_digit($res_id) || strlen($res_id) != 7)) {
        echo "<script>alert('špatný formát ID!')</script>";
        $valid = false;
    }

    // ===== Проверка и стадия подтверждения удаления =====
    if ($valid && $res_id !== '') {
        if ($room_name === '') {
            $valid = false;
    echo "<script>alert('nezadali jste všechna info pro smazaní!')</script>";
    }

    if ($valid) {
    $check_room = $conn->query("
        SELECT 1
        FROM room_res
        WHERE room_name = '$room_name'
        AND person_id = '$res_id'
    ");

    if ($check_room->num_rows === 0) {
        echo "<script>alert('Tato místnost neexistuje nebo nemate v ni rezervaci s temto ID!')</script>";
        $valid = false;
    }
}
    if ($valid){
        
        $check = $conn->query("
            SELECT 1 
            FROM room_res 
            WHERE person_id = '$res_id'
            AND person_name = '$pers_name'
        ");

        if ($check->num_rows === 1) {
            if ($confirm_delete) {
                // Удаляем резерв
                $conn->query("
                    DELETE FROM room_res 
                    WHERE person_id = '$res_id' 
                    AND person_name = '$pers_name'
                    AND room_name = '$room_name'
                ");
                if ($conn->affected_rows > 0) {
                    echo "<p style='color:green;'>Rezervace byla úspěšně smazána!</p>";
                } else {
                    echo "<p style='color:red;'>Chyba při mazání rezervace!</p>";
                }
            } else {
                // Показываем сообщение о подтверждении
                $confirm_delete_stage = true;
            }
        } else {
            echo "<script>alert('nelze smazat rezervaci nebo neexistuje!')</script>";
        }
    }
}

    // ===== Вывод всех резерваций пользователя =====
    if ($valid) {
        $res_result = $conn->query("
            SELECT 
                person_id,
                room_name AS `místnost`,
                res_time_start AS `začátek`,
                res_time_end AS `konec`,
                res_date AS `datum`
            FROM room_res
            WHERE person_name = '$pers_name'
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
            echo "<p>Zatím nemáte žádné rezervace!</p>";
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
    <form action="./reservation_check.php" method="post">
        <p>Jméno:</p>
        <input type="text" name="pers_name" value="<?=htmlspecialchars($pers_name)?>">
        <p>Heslo:</p>
        <input type="password" name="pers_password" value="<?=htmlspecialchars($pers_password)?>">
        <p>ID rezervace (pokud chcete smazat):</p>
        <input type="text" name="pers_ID" value="<?=$res_id?>">
        <p>název mistnosti:</p>
        <input type="text" name="room_name" value="<?=$room_name?>">
        
        <?php if ($confirm_delete_stage): ?>
            <p style="color:orange;">Opravdu chcete tuto rezervaci smazat? Stiskněte tlačítko znovu pro potvrzení.</p>
            <input type="hidden" name="confirm_delete" value="1">
        <?php endif; ?>
        
        <br><br>
        <input type="submit" name="res_check_Submit" value="Odeslat">
    </form>
    <div id="overeni_rezervaci" class="section">
    <p>Pokud jsi zapoměl vytvořit uživatela, vrať si na hlavní stránku a vytvoř ho:</p>
    <a href="./index.php">Uvodní stránka</a>
    </div>
</body>
</html>