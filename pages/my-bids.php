<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 12/06/2022
 */

if(!$authed){
    echo "<div id=redirect>login</div>";
}else{

    ?>

    <main>
        <section>
            <h1>My Bids</h1>

            <table>
                <thead>
                <th>Task Name</th>
                <th>Bid Hash</th>
                <th>Author</th>
                <th>Outcome</th>
                </thead>
                <tbody>
                <?php
                $user_id = $user->getId();
                $bids = Database::query("SELECT * FROM bids WHERE user_id='$user_id'")->fetch_all(MYSQLI_ASSOC);
                foreach ($bids as $bid){
                    $task_id = $bid["task_id"];
                    $task = Database::query("SELECT * FROM tasks WHERE id='$task_id'")->fetch_all(MYSQLI_ASSOC);

                    echo "<tr>";
                    echo "<td>".$task[0]["name"]."</td>";
                    echo "<td>".$bid["hash"]."</td>";
                    echo "<td>".$task[0]["author"]."</td>";
                    echo "<td>Feature not yet implemented</td>";


                    echo "</tr>";

                }
                ?>
                </tbody>
            </table>

        </section>
    </main>

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
    </style>

    <?php
}