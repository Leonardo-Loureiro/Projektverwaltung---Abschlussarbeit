<?php
session_start();
$user_feedback = "";

/*  User hinzufügen  */
/*
if(isset($_POST["submit"]))
{
require("db_conn.php");
$stmt = $connection->prepare("INSERT INTO user_login (l_username, l_password, l_roll) VALUES (:user, :passwort, 2)");
$stmt->bindParam(":user", $_POST["username"]);
$hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
$stmt->bindParam(":passwort", $hash);
$stmt->execute();
}*/

//Überprüfen ob das Formular gesendet und gefüllt ist
if (isset($_POST["submit"]) AND isset($_POST["username"]) AND isset($_POST["password"]))
{
//Datenbank Verbindung herstellen
require("db_conn.php");

//Datenbank Benutzer-Informationen abrufen 
$statement_user = $connection->prepare("SELECT * FROM user_login WHERE l_username = :user");
$statement_user->bindParam(":user", $_POST["username"]);
$statement_user->execute();
$count = $statement_user->rowCount();


    //Login versuch - Existiert die eingegebene Person?
    if ($count == 1) 
    {
    $row = $statement_user->fetch();

        if (password_verify($_POST["password"], $row["l_password"]))
        {
            //Bei 3 Versuche ist der Benutzer gesperrt
            if ($row["l_attempts"] >= 3)
            {
                $user_feedback = "Konto wurde gesperrt. Melden sie sich beim Support!";
            }
            else
            {
                // Fehlversuche nach einem erfolgreichem Login zurücksetzen
                $statement_update_attempt = $connection->prepare("UPDATE user_login SET l_attempts = 0 WHERE l_username = :username");
                $statement_update_attempt->bindParam(":username", $_POST["username"]);
                $statement_update_attempt->execute();

                //Sitzungs Infos bestimmen und Seite ändern
                $_SESSION["IS_LOGIN"] = true;
                $_SESSION["username"] = $row["l_username"];
                $_SESSION["management_rights"] = $row["l_roll"];
                $_SESSION["global_feedback"] = " ";
                header('Location: projects.php');
            }
        }
        else
        {
            $user_feedback = "Benutzername oder Passwort falsch.";
            // Versuche erhöhen
            if ($row["l_attempts"] < 3)
            {
                $user_feedback = "<p>Versuche:" . $row["l_attempts"] + 1 . "</p>";
                //SQL Befehl | Versuche Increment (+1)
                $statement_increment_attempt = $connection->prepare("UPDATE user_login SET l_attempts = l_attempts+1 WHERE l_username = :username");
                $statement_increment_attempt->bindParam(":username", $_POST["username"]);
                $statement_increment_attempt->execute();
            }
            else
            {
                $user_feedback = "Konto wurde gesperrt. Melden sie sich beim Support!";
            }
        }
    } 
    else 
    {
        // Falscher Benutzer
        $user_feedback = "Benutzername oder Passwort falsch.";

    }    
} 
else
{
    //Default Feedback
    $user_feedback = "Bitte geben Sie Ihre Login Informationen.";
}


?>
<!DOCTYPE html>
<html style="color: var(--bs-blue);background: var(--bs-gray-200);">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Projektverwaltungs-Tool</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
</head>

<body class="bg-gradient-primary" style="color: var(--bs-cyan);background: var(--bs-gray-200);">
    <div class="container">
        <div class="row justify-content-center" style="background: var(--bs-gray-200);">
            <div class="col-md-9 col-lg-12 col-xl-10" style="background: var(--bs-gray-200);">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0"></div>
                </div>
                <section class="login-clean" style="background: var(--bs-gray-200);">
                    <form method="post" action="Index.php" style="width: 395px;height: 397px;" >
                        <h2 class="visually-hidden">Login Form</h2><img style="align-items: center;width: 240px;" src="assets/img/avatars/NetMaster_Logo.png" />
                        <div class="illustration"></div>
                        <div class="mb-3"><input class="form-control" type="text" name="username" placeholder="Benutzername" required></div>
                        <div class="mb-3"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
                        <div class="mb-3"><button class="btn btn-primary d-block w-100" name="submit" type="submit" style="background: var(--bs-info);">Anmelden</button></div>
                        <hr />
                        <?php
                        //Benutzer Feedback
                        echo $user_feedback;
                        ?>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>