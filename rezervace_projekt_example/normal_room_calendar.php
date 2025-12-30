<?php
$conn = mysqli_connect("localhost", "root", "", "reservations");

$reservation_date = '';
$person_name = '';
$hour_start = '';
$hour_end = '';
$minute_start = '';
$minute_end = '';
$person_id = '';
$person_password = '';
$room_name = "normální mistnost 50^2";

/* ===== VYPIS REZERVACÍ ===== */
$result = $conn->query("
    SELECT person_id AS `id rezervace`,
           res_time_start AS `začatek rezervace`,
           res_time_end AS `konec rezervace`,
           res_date AS `datum`
    FROM room_res
    WHERE room_name = '$room_name'
");

if ($result && $result->num_rows > 0) {
    echo "<p class='all_res'>všechny rezervace:</p><hr>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='reservations-list'>";
        foreach ($row as $key => $value) {
            echo "<p><strong>$key</strong>: $value</p>";
        }
        echo "</div><hr>";
    }
}

/* ===== ZPRACOVÁNÍ FORM ===== */
if (isset($_POST['normal_room_res_Submit'])) {

    $reservation_date = trim($_POST["reservation_date"]);
    $person_name = trim($_POST["name"]);
    $hour_start = trim($_POST["hour_start"]);
    $minute_start = trim($_POST["minute_start"]);
    $hour_end = trim($_POST["hour_end"]);
    $minute_end = trim($_POST["minute_end"]);
    $person_id = trim($_POST["person_id"]);
    $person_password = trim($_POST["person_password"]);

    $valid = true;

    /* ===== VALIDACE VŠECH POLÍ ===== */
    foreach ([
        $reservation_date, $person_name, $hour_start,
        $minute_start, $hour_end, $minute_end,
        $person_id, $person_password
    ] as $field) {
        if ($field === '') {
            $valid = false;
            echo "<script>alert('nezadali jste všechna info!')</script>";
            break;
        }
    }

    /* ===== NORMALIZACE DATUMU ===== */
    if ($valid) {
        $parts = explode('.', $reservation_date);
        if (count($parts) === 3) {
            $parts[0] = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
            $parts[1] = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
            $reservation_date = implode('.', $parts);
        }
    }

    /* ===== VALIDACE DATUMU ===== */
    if ($valid) {
        $date_obj = DateTime::createFromFormat('d.m.Y', $reservation_date);
        if (!$date_obj || $date_obj->format('d.m.Y') !== $reservation_date) {
            echo "<script>alert('špatný format datumu!')</script>";
            $valid = false;
        }
    }

    /* ===== FORMATOVANÍ ČASU ===== */
    if ($valid) {
        foreach ([$hour_start, $minute_start, $hour_end, $minute_end] as $t) {
            if (!ctype_digit($t)) {
                echo "<script>alert('čas musí být číslo!')</script>";
                $valid = false;
                break;
            }
        }
    }

    if ($valid &&
        (
            $hour_start < 7 || $hour_start > 21 ||
            $hour_end   < 7 || $hour_end   > 21 ||
            $minute_start > 59 || $minute_end > 59
        )
    ) {
        echo "<script>alert('špatný formát času!')</script>";
        $valid = false;
    }

    if ($valid) {
        $time_start = str_pad($hour_start, 2, '0', STR_PAD_LEFT) . ':' .
                      str_pad($minute_start, 2, '0', STR_PAD_LEFT);

        $time_end   = str_pad($hour_end, 2, '0', STR_PAD_LEFT) . ':' .
                      str_pad($minute_end, 2, '0', STR_PAD_LEFT);

        $res_start = strtotime("$reservation_date $time_start");
        $res_end   = strtotime("$reservation_date $time_end");

        if ($res_start >= $res_end) {
            echo "<script>alert('Začatek rezervace musí být dříve než konec!')</script>";
            $valid = false;
        }

        if ($res_start <= time()) {
            echo "<script>alert('Nelze rezervovat v minulosti!')</script>";
            $valid = false;
        }
    }

    /* ===== KOLIZE ČASŮ ===== */
    if ($valid) {
        $result = $conn->query("
            SELECT res_time_start, res_time_end
            FROM room_res
            WHERE res_date = '$reservation_date'
            AND room_name = '$room_name'
        ");

        while ($row = $result->fetch_assoc()) {
            $existing_start = strtotime("$reservation_date " . $row['res_time_start']);
            $existing_end   = strtotime("$reservation_date " . $row['res_time_end']);

            if (!($res_end <= $existing_start || $res_start >= $existing_end)) {
                echo "<script>alert('čas se překrývá s jinou rezervací!')</script>";
                $valid = false;
                break;
            }
        }
    }
    /* ===== HESLO ===== */
    if ($valid) {
        $result = $conn->query("
            SELECT person_password
            FROM user_list
            WHERE person_name = '$person_name'
        ");

        if ($row = $result->fetch_assoc()) {
            if (!password_verify($person_password, $row['person_password'])) {
                echo "<script>alert('špatné heslo!')</script>";
                $valid = false;
            }
        } else {
            echo "<script>alert('uživatel neexistuje!')</script>";
            $valid = false;
        }
    }

    /* ===== ID ===== */
    if ($valid && (!ctype_digit($person_id) || strlen($person_id) != 7)) {
        echo "<script>alert('špatný formát ID!')</script>";
        $valid = false;
    }

    if ($valid) {
        $check = $conn->query("SELECT 1 FROM room_res WHERE person_id = '$person_id' AND room_name = '$room_name'");
        if ($check->num_rows > 0) {
            echo "<script>alert('ID už je použité!')</script>";
            $valid = false;
        }
    }

    /* ===== INSERT ===== */
    if ($valid) {
        $conn->query("
            INSERT INTO room_res
            (room_name, person_name, person_id, res_time_start, res_time_end, res_date)
            VALUES
            ('$room_name', '$person_name', '$person_id', '$time_start', '$time_end', '$reservation_date')
        ");

        echo "<script>alert('Rezervace byla úspěšně vytvořena')</script>";

        $reservation_date = $person_name = $hour_start = $hour_end =
        $minute_start = $minute_end = $person_id = $person_password = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index1.css">
</head>
<body>
<div class="calendar-page">
    <p class="important">*PŘEČÍST PŘED VYTVAŘENÍM REZERVACÍ:<br>
    -pracujeme od 8 do 21 hodin, proto mužete rezervovat jen v tomto úseku času! <br>
    -rezervaci lze udělát jen 12 měsiců od aktualního měsíce! <br>
    -ID je unikatní pro každou rezervaci v jednotlivých mistnostech, proto přečtěte seznam rezervací před jejich vytvařením!<br>
    a taky má delku 7, takže zadavejte ho správně!</p>

    <div class="calendar-container">
        <form action="./normal_room_calendar.php"
              method="post"
              id="room_form"
              class="reservation-form">

            <p>den rezervace:</p> 
            <input type="text" name="reservation_date" id="reserv_date" value="<?=$reservation_date ?>">

            <p>žadaný čas:</p>
            <input type="text" name="hour_start" value="<?=htmlspecialchars($hour_start) ?>"> :
            <input type="text" name="minute_start" value="<?= htmlspecialchars($minute_start) ?>"> 
            -
            <input type="text" name="hour_end" value="<?= htmlspecialchars($hour_end) ?>"> :
            <input type="text" name="minute_end" value="<?= htmlspecialchars($minute_end) ?>">

            <p>jmeno rezervujicího:</p>
            <input type="text" value="<?= htmlspecialchars($person_name) ?>" name="name">

            <p>id:</p> 
            <input type="text" name="person_id" value="<?= htmlspecialchars($person_id) ?>">

            <p>heslo:</p>
            <input type="password" name="person_password" value="<?= htmlspecialchars($person_password) ?>">

            <br><br>
            <input type="submit" name="normal_room_res_Submit">
        </form>

        <div class="calendar-box">

            <div class="calendar-controls">
                <button class="non-clackable-button"
                        id="prev_month"
                        onclick="calendar_back('calendar')">
                    minuly mesic
                </button>

                <button onclick="calendar_forward('calendar')" id="next_month">
                    pristi mesic
                </button>
            </div>

            <div id="calendar"></div>

        </div>

    </div>
</div>
<div id="overeni_rezervaci" class="section">
    <p>Pokud už jsi vytvořel rezervaci a chceš ji zkontrolovat nebo vymazat, mužeš to udělat tady:</p>
    <a href="./reservation_check.php">ověření rezervaci</a>
    <p>A pokud jsi zapoměl vytvořit uživatela, vrať si na hlavní stránku a vytvoř ho:</p>
    <a href="./index.php">Uvodní stránka</a>
    <script src="index.js"></script>
</div>
</body>
</html>

