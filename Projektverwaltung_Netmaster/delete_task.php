<?php
session_start();

//Unbefugte und nicht angemeldete Benutzer nicht erlaubt
if(!$_SESSION["IS_LOGIN"] AND $_SESSION["management_rights"] != 1)
{
    header('Location: projects.php');
} 
else 
{
    //Wurde ein Task gewählt? Wenn ja...
    if(isset($_SESSION["task_id"]))
    {
        //Datenbank verbindung
        require("db_conn.php");
        //SQL Befehl | Zeige alle Tasks die folgendem Projekt gehört
        $statement_call = $connection->prepare("SELECT * FROM tasks WHERE project_id = :id");
        $statement_call->bindParam(":id", $_SESSION["project_id"]);
        $statement_call->execute();
        $count = $statement_call->rowCount();

        //Bei 1 oder mehrere tasks gefunden, lösche Task mit folgender ID
        if ($count >= 1)
        {
            $statement_delete_task = $connection->prepare("DELETE FROM tasks WHERE task_id = :task_id");
            $statement_delete_task->bindParam(":task_id", $_SESSION["task_id"]);
            $statement_delete_task->execute();

            //SESSION Variable, nur Inhalt löschen!
            unset($_SESSION['task_id']);
            //Zuweisung auf Projekt Seite
            header('Location: projects.php');
        } 
        else
        {
            echo "Keine Tasks vorhanden!";
        }
    }
    else
    {
        echo "Keine Auswahl getroffen!";
    }
}
?>