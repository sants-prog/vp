<?php
    require_once "../config.php";

    //loon andmebaasiga ühenduse
    //server, kasutaja, parool, andmebaas
    $conn = new mysqli($server_host, $server_user_name, $server_password, $database);
    //määran suhtklemisel kasuatatava kooditabeli
    $conn->set_charset("utf8");

    //valmistame ette andmete saatmise SQL käsu
    $stmt = $conn->prepare("SELECT pealkiri, aasta, kestus, zanr, tootja, lavastaja FROM film"); //Wildcard peaks ka töötama
    echo $conn->error;
    //seome saadavad andmed muutujatega + mingi copy paste mdea mis siin saab
    $stmt->bind_result($pealkiri_from_db, $aasta_from_db, $kestus_from_db, $zanr_from_db, $tootja_from_db, $lavastaja_from_db);
    $stmt->execute();
    //seome SQL käsu õigete andmetega
    //andmetüübid  i - integer   d - decimal    s - string
    $film_html = null;
    while($stmt->fetch()){
        //echo $comment_from_db;
        $film_html .= "<h3>{$pealkiri_from_db}</h3><ul><li>Valmimisaasta: {$aasta_from_db}</li><li>Kestus: {$kestus_from_db} minutit</li><li>Žanr: {$zanr_from_db}</li><li>Tootja: {$tootja_from_db}</li><li>Lavastaja: {$lavastaja_from_db}</li></ul>";
        // //sulgeme käsu
        // $stmt->close();
        // //andmebaasi ühenduse kinni
        // $conn->close();
    }

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Filmide nimekiri</title>
</head>

<body>
    <?php echo $film_html ?>

</body>
</html>
