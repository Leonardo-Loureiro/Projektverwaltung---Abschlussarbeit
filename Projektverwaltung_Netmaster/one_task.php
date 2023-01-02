<?php
session_start();
//Nicht angemeldete Benutzer nicht erlaubt
if(!$_SESSION["IS_LOGIN"]){
    header('Location: Index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Einzelprojekt</title>
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
                    <div class="sidebar-brand-icon rotate-n-15" style="transform: rotate(0deg);width: 185px;"><img style="width: 170px;margin-right: 5px;" src="assets/img/avatars/NetMaster_Logo.png"></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link" href="projects.php"><i class="fas fa-tachometer-alt"></i><span>Alle Projekte</span></a></li>
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
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#"><span class="d-none d-lg-inline me-2 text-gray-600 small"><?php echo $_SESSION["username"];?></span><img class="border rounded-circle img-profile" src="assets/img/avatars/unknown_person.jpg"></a>
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
                        <div class="col-md-8" style="width: 50%; height: 67.25px;">
                            <h1 style="margin: 15px 0px;font-size: 31.880000000000006px;color: var(--bs-primary);text-align: left;">Task</h1>
                        </div>
                        <?php
                        //Nur für Projektleiter
                        if($_SESSION["management_rights"] == 1)
                        {
                        echo '<div class="col-md-4" style="width: 50%; text-align: right;"><a style="color: white;" href="edit_task_1.php"><button class="btn btn-primary" type="button" style="margin: 15px 0px;padding: 2px 12px;width: 176.6875px;">Task bearbeiten</button></a><a style="color: white;" href="delete_task.php"><button class="btn btn-primary" type="button" style="margin: 15px 0px;padding: 2px 12px;width: 176.6875px;">Task löschen</button></a></div>';
                        }
                        //Nur für Projektmitarbeiter
                        if($_SESSION["management_rights"] == 2)
                        {
                        echo '<div class="col-md-4" style="width: 50%; text-align: right;"><a style="color: white;" href="edit_task_1.php"><button class="btn btn-primary" type="button" style="margin: 15px 0px;padding: 2px 12px;width: 200.6875px;">Taskstatus bearbeiten</button></a></div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                    
                    <?php
   
                    //Wurde eine Auswahl getroffen? Wenn nicht Feedback
                    if(isset($_POST["auswahl_task"]))
                    {

                    require("db_conn.php");
                    //SQL Befehl | Auflistung von nur 1 Task mit Informationen von anderen tabellen (inner join)
                    $stmt = $connection->prepare("SELECT tasks.task_id, tasks.t_name, tasks.t_creationdate, tasks.t_aimdate, 
                    tasks.t_lastupdated, tasks.t_description, task_status.ta_status, projects.p_name 
                    FROM ((tasks
                    INNER JOIN task_status ON tasks.status_id = task_status.ta_status_id)
                    INNER JOIN projects on tasks.project_id = projects.proj_id)
                    WHERE task_id = :task_id;
                    ");
                    $stmt->bindParam(":task_id", $_POST["auswahl_task"]);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    //Table Aufbau
                    echo "<table border='1' width='100%' style='color: var(--bs-primary);'>
                            <tr>
                                <th>Taskname</th>
                                <th>Beschreibung</th>
                                <th>Task-Status</th>
                                <th>Projekt</th>
                                <th>Erstelldatum</th>
                                <th>Zuletzt aktualisiert</th>
                                <th>Zieldatum</th>
                            </tr>";
                    $row = $stmt->fetch(); 
                    //Session Variable deklarieren
                    $_SESSION["task_id"] = $row["task_id"];
                    echo "<tr>";
                    echo "<td>".$row["t_name"]."</td>";
                    echo "<td>".$row["t_description"]."</td>"; 
                    echo "<td>".$row["ta_status"]."</td>"; 
                    echo "<td>".$row["p_name"]."</td>";
                    echo "<td>".$row["t_creationdate"]."</td>";
                    echo "<td>".$row["t_lastupdated"]."</td>";
                    echo "<td>".$row["t_aimdate"]."</td>";
                    echo "</tr>";
                    echo "</table>";

                    }
                    else
                    {
                        echo "Es wurde keine Auswahl getroffen";
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