<?php
//Verbindungs Informationen definieren
$db_host = "mysql:host=localhost;dbname=proj_manage_ect_db";
$db_user = "root";
$db_pw = "";

//Vebindung testen
try {
    //Verbindung herstellen
    $connection = new PDO($db_host , $db_user, $db_pw);
    //echo "Verbindung wurde erfolgreich erstellt";
} catch (PDOExeption $e){
    //Bei Fehler, folgenden Ausgabe:
    exit('Keine Verbindung zur Datenbank möglich');
}
?>