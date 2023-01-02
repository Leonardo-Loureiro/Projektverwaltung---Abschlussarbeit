<?php
session_start();

//UNbefugte und nicht angemeldete Benutzer nicht erlaubt
if(!$_SESSION["IS_LOGIN"] OR $_SESSION["management_rights"] != 1 AND $_SESSION["management_rights"] != 2)
{
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
                            <h1 style="margin: 15px 0px;font-size: 31.880000000000006px;color: var(--bs-primary);text-align: left;">Projekt bearbeiten</h1>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">

                    <?php
                    //Bearbeitung für Projektleiter
                    if (isset($_POST["editing"]))
                    {
                        require("db_conn.php");
                        //Im Array werden die Werte von dem Formular gespeichert
                        $werte = [
                            'name' => $_POST["name_project"], 
                            'status' => $_POST["edit_project_task"], 
                            'projektleiter' => $_POST["edit_projektmanager"],
                            'aimdate' => $_POST["aimdate_project"]
                        ];
                        $statement = $connection->prepare("UPDATE projects"
                        . "SET p_name = :name_project, status_id = :status_project, project_manager = :manage_project, p_aimdate = :aimdate_project"
                        . "WHERE proj_id = :id");
                        //Die Werte werden verbunden mit bindParam()
                        $statement->bindParam(":id", $_POST["editing"]);
                        $statement->bindParam(":name_project", $werte['name']);
                        $statement->bindParam(":status_project", $werte['status']);
                        $statement->bindParam(":manage_project", $werte['projektleiter']);
                        $statement->bindParam(":aimdate_project", $werte['aimdate']);
                        $statement->execute();

                        if ($statement->rowCount() > 0)
                        {
                            echo "<p>Projekt bearbeitet</p><br>";
                            unset($_SESSION["project_id"]);
                        } else
                        {
                            echo "<p>Fehler, kein Datensatz hinzugefügt</p><br>";
                        }
                    } 
                    else
                    {
                        echo "Keine Informationen erhalten!";
                    } 
                    
                    //Bearbeitung für Projektmitarbeiter
                    if (isset($_POST["editing_staff"]))
                    {

                        require("db_conn.php");
                        $werte = [
                            'status' => $_POST["edit_project_staff_task"]
                        ];
                        $statement = $connection->prepare("UPDATE projects"
                        . "SET status_id = :status_project"
                        . "WHERE proj_id = :id");
                        $statement->bindParam(":id", $_POST["editing_staff"]);
                        $statement->bindParam(":status_project", $werte['status']);
                        $statement->execute();

                        if ($statement->rowCount() > 0)
                        {
                            echo "<p>Projekt bearbeitet</p><br>";
                            unset($_SESSION["project_id"]);
                        } else
                        {
                            echo "<p>Fehler, kein Datensatz hinzugefügt</p><br>";
                        }
                    } 
                    ?>

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