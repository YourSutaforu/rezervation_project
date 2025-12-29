<?php
$person_name = '';
$person_password = '';

if(isset($_POST["Submit"])){
    $valid = True;
    $person_name = trim($_POST["person_name"]);
    $person_password = trim($_POST["person_password"]);
    if (
        $person_name == '' ||
        $person_password == ''
    ){
        echo "<script>alert('nezadali jste všechne parametry!')</script>";
        $valid = false;
    }
    if($valid){
        $conn = mysqli_connect("localhost", "root", "", "reservations");
        $result = $conn->query("SELECT person_name FROM user_list");
    $ids = [];
    if ($result) {
    while ($row = $result->fetch_assoc()) {
        $ids[] = $row['person_name'];
    };
    };
    foreach($ids as $value){
        if ((string) $value == $person_name){
            $valid = False;
            break;
        }
    }

    if ($valid){
        $hash = password_hash($person_password, PASSWORD_DEFAULT);
        if ($conn->query("INSERT INTO user_list(person_name, person_password)
        VALUES ('$person_name', '$hash')") === TRUE) {
            echo "<script>alert('Uživatel byl vytvořen!')</script>";
            $person_name = $person_password = '';
        };
    } else {
        echo "<script>alert('Jmeno už je použitý! zádejte, prosím, jiný.')</script>";
    };

};
};
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the pupok reservations</title>
    <link rel="stylesheet" href="index1.css">
</head>
<body>
<header>
    <h1>Pupok reservations</h1>
</header>
<p>
    <a href="#co_jsem">co jsem?</a> 
    <a href="#jak_funguju">jak funguju?</a>
    <a href="#vytvareni_id">vytvaření ID</a>
    <a href="#rovnou_vyzkouset">rovnou vyskoušet?</a>
    <a href="#overeni_rezervaci">ověření rezervací</a>
</p>
<div id="co_jsem" class="section">
    <p><h2>Ahoj! Jsem stránka pro rezervaci mistnosti jmenem pupok reservations!</h1></p>
    <p>jsem udělan společnosti Pupok, která poskytuje místnost pro jakekoliv akce!</p>
</div>
<div id="jak_funguju" class="section">
    <p><h2>Jak funguju? Velice jednoduše!</h2></p>
    <p>Až vytvoříš svého uživatele na teto stránce, budeš moct pomoci něho přidát k sobě rezervaci!</p>
</div>
<div class="section">
<p>Než se pustíš do vytvoření rezervací, zapiš svůj jedinečnej nickname a přidělej mu vhodné heslo!<br> pokud už takový máš, přeskoč tohle pole a rovnou na to!</p>
<p class="important">*DŮLEŽITÉ! Nezapomeň svoje helso ať nemáš potom s rezervacemi problemy. :3</p>
</div>
<form action="./index.php" method="post" id="vytvareni_id" >
    <p>Jmeno:</p>
    <input type="text" name="person_name" value="<?= $person_name?>">
    <p>Heslo:</p>
    <input type="text" name="person_password" value="<?=$person_password?>"><br>
    <input type="submit" name="Submit">

</form>
<div id="rovnou_vyzkouset" class="section">
    <p>Už máš všechno přečteno a hotovo? Tak tady máš výběr prostorů:</p>
    <p>
        <a href="./big_room_calendar.php">velka mistnost (80 m^2)</a>
        <a href="./normal_room_calendar.php">stredni mistnost (50 m^2)</a>
        <a href="./small_room_calendar.php">mala mistnost (20 m^2)</a>
    </p>
</div>
<div id="overeni_rezervaci" class="section">
    <p>Pokud už jsi vytvořel rezervaci a chceš ji zkontrolovat nebo vymazat, mužeš to udělat tady:</p>
    <a href="./reservation_check.php">ověření rezervaci</a>
</div>
</body>
</html>
