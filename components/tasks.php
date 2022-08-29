<?php

$tasks = Database::query("SELECT * FROM tasks");


    foreach ($tasks->fetch_all(MYSQLI_ASSOC) as $task) {

        $today = time();
        $event = $task["end_date"];
        $id = $task["id"];

        $countDownHours = round(($event - $today) / 3600);

        if ($countDownHours < 0) {
            $status = "closed";
            $statusMessage = "Bidding closed";
        } else {
            $status = "open";
            $statusMessage = "Bidding Open for another " . $countDownHours . " hours";
        }

        echo "<button class='task " . $status . "' id='" . $task["id"] . "'onclick='Application.instance.setSelectedTask(`$id`)'>";
        echo "<h3>" . $task["name"] . "</h3>";
        echo "<p class='task-closes-date'>" . $statusMessage . "</p>";

        echo "</button>";

    }
