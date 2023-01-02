<?php
//Die Sitzung wird gestartet und danach zerstört -> Danach ist ein neuer Login erforderlich
//Weiterleitung auf die Startseite
session_start();
session_destroy();
header('Location: Index.php');
?>