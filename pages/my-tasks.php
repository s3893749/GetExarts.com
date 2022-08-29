<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 15/06/2022
 */

if(!$authed){
    echo "<div id=redirect>login</div>";
}else{
    $user_id = $user->getId();
    $tasks = Database::query("SELECT * FROM tasks WHERE user_id='$user_id'")->fetch_all(MYSQLI_ASSOC);
    ?>

    <main>
        <section id="my-tasks">
            <h1>My Tasks</h1>

            <form action="javascript:Application.instance.showNewTaskWindow()"><button style="width: 128px; left:64px!important; margin-bottom: 32px">New task</button></form>

            <table>
                <thead>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Bids</th>
                    <th>Remaining Time</th>
                    <th>Winner</th>
                </thead>
                <tbody>
                <?php

                foreach ($tasks as $task){
                    echo "<tr>";
                    echo "<td>".$task["name"];
                    echo "<td>".$task["description"];
                    $id = $task["id"];
                    $bids = count(Database::query("SELECT * FROM bids WHERE task_id='$id'")->fetch_all(MYSQLI_ASSOC));
                    echo "<td>".$bids."</td>";
                    //calculate the time remaining on the task
                    $today = time();
                    $event = $task["end_date"];
                    $countDownHours = round(($event - $today) / 3600);
                    if($countDownHours < 0){
                        echo "<td> <p style='color: red'>Bidding finished</p></td>";
                        echo "<td> Feature not implemented</td>";
                    }else{
                        echo "<td >".$countDownHours." hours</td>";
                        echo "<td> awaiting end of bidding period</td>";

                    }
                    echo "</tr>";

                }


                ?>
                </tbody>
            </table>
        </section>
    </main>

    <div id="new-task-window">
        <div>
            <h2>New Task</h2>
            <form action="javascript:Application.instance.saveNewTaskWindowButton()">
                <p id="new-task-window-error" style="width: 100%; color: red; text-align: center"></p>
                <label>
                    Name
                    <input type="text" placeholder="My new task!" required id="task-name">
                </label>
                <label>
                    Description
                    <input type="text" placeholder="I will develop a new website for your company"  required id="task-description">
                </label>
                <label>
                    Image Link (including https://)
                    <input type="text" placeholder="https://example.com/image.png" required id="task-image">
                </label>
                <label>
                    Bidding end date
                    <input type="date" required id="task-date">
                </label>
                <button style="background-color: green">Save</button>
                <button style="background-color: red" onclick="Application.instance.cancelNewTaskWindowButton()">Cancel</button>
            </form>
        </div>
    </div>


    <style>

        table{
            color: white;
            width: 100%;
            border-collapse: collapse;
        }
        table thead{
            background-color: #383636;
            color: white;
            border: 1px solid #595959;

        }
        table thead th{
            font-weight: normal;
            text-align: left;
            padding: 16px;
        }
        table tbody td{
            padding: 8px;
            border: 1px solid #595959;
            overflow: scroll;
        }

        #new-task-window{
            background-color: rgba(0,0,0,0.5);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
        }

        #new-task-window div{
            background-color: #ffffff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 32px;
            border-top: 64px solid rebeccapurple;

        }
        #new-task-window div h2{
            margin-top: -80px;
            margin-bottom: 32px;
            text-align: center;
            color:white;
        }

        #new-task-window div form{
            width: 100%;
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            margin: auto;
        }

        #new-task-window div form label{
            font-weight: bold;
            margin: 8px;
            width: 45%;

        }

        #new-task-window div form input{
            width: 100%;
            margin-bottom: 16px;
            margin-top: 16px;
            border-style: solid;
            border-color: #ff6200;
            border-width: 2px;
            border-radius: 4px;
            padding: 4px;
            height: 48px;
        }

        #new-task-window div form button{
            background-color: #ff6200;
            height: 48px;
            padding:8px;
            width:45%;
            margin: 16px;
            outline: 0 none;
            border-color: #ff6200;
            border-radius: 8px;
            color: white;
        }


    </style>
    <?php
}
