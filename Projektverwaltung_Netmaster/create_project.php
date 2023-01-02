<?php
session_start();
//Unbefugte und nicht angemeldete Benutzer nicht erlaubt
if(!$_SESSION["IS_LOGIN"] OR $_SESSION["management_rights"] != 1){
    header('Location: projects.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Alle Projekte</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
</head>

<body id="page-top">
    <div id="wrapper">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0" style="background: rgb(35,36,38);">
            <div class="container-fluid d-flex flex-column p-0"><a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#" style="height: 94px;">
                    <div class="sidebar-brand-icon rotate-n-15" style="transform: rotate(0deg);width: 185px;"><img style="width: 170px;margin-right: 5px;"src="assets/img/avatars/NetMaster_Logo.png" ></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link active" href="projects.php"><i class="fas fa-tachometer-alt"></i><span>Alle Projekte</span></a></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="far fa-user-circle"></i><span>Logout</span></a></li>
                    <li class="nav-item"></li>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                        <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <h1 style="font-size: 18.88000000000001px;font-weight: bold;margin: 0px;width: 375px;">Willkommen <?php echo $_SESSION["username"];?></h1>
                        </form>
                        <ul class="navbar-nav flex-nowrap ms-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?php echo $_SESSION["username"]; ?></span><img class="border rounded-circle img-profile" src="assets/img/avatars/unknown_person.jpg"></a>
                                    <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8" style="height: 67.25px;">
                            <h1 style="margin: 15px 0px;font-size: 31.880000000000006px;color: var(--bs-primary);text-align: left;">Projekt erstellen</h1>
                        </div>
                        <?php
                        ?>
                    </div>
                </div>
                <div class="container">
                    <div class="row">

                    <?php
                    //Bei Versendung des Formulares
                    if (isset($_POST["send"]))
                    {
                        require("db_conn.php");
                        //SQL Befehl mit einem INSERT
                        $statementone = $connection->prepare("INSERT INTO projects"
                            . "(p_name, p_aimdate, project_manager)"
                            . "VALUES(?, ?, ?)");
                            // Die ??? mit geposteten Werten einfügen
                        $statementone->execute(array($_POST["name_project"], $_POST["aimdate"] , $_POST["projectmanagername"]));
                        //Ist der Eintrag eingekommen? Wenn ja/nein Feedback
                        if ($statementone->rowCount() > 0)
                        {
                            echo "<p>Datensatz hinzugefügt</p><br>";
                            unset($_POST["send"]);
                        } else
                        {
                            echo "<p>Fehler, kein Datensatz hinzugefügt</p><br>";
                        }
                    }
                    else
                    {
                        echo "Keine Informationen erhalten!";
                    }
                    ?>

                    <p>Geben Sie bitte einen Datensatz ein und senden Sie das Formular ab:</p>
                    <a href="projects.php"><u>Zurück</u></a>

                    <form action = "create_project.php" method = "post">
                    <p><input style="width: 25%;" name="name_project"> <strong>Name des Projekts</strong></p>
                    <p><input style="width: 25%;" name="aimdate"> <strong>Zieldatum</strong> (Schreibweise: JJJJ-MM-TT 00:00:00)</p>
                    <p><u><strong>Projektleiter des Projekts auswählen:</strong></u></p>
                    <?php
                    require("db_conn.php");
                    //SQL Alle Projektleiter auflisten
                    $statementtwo = $connection->prepare("SELECT user_id ,l_username FROM user_login WHERE l_roll = 1");
                    $statementtwo->execute();

                    while ($row = $statementtwo->fetch())
                    {
                        echo '<p><input id="projectmanagername" type="radio" name="projectmanagername" value="' . $row["user_id"] . '"> <label for="projectmanagername">' .$row["l_username"]. '</label><br></p>';
                    }
                     
                    ?>          
                    
                    <p><input type="submit" name="send">
                    <input type="reset"></p>
                    </form>
                    
                    </div>
                </div>
            </div>
            <footer class="bg-white sticky-footer">
                <div class="container my-auto">
                    <div class="text-center my-auto copyright"><span>Copyright © NetMaster (Schweiz) AG 2022</span></div>
                </div>
            </footer>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>