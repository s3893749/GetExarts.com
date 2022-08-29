<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 16/06/2022
 */

$id = $_GET["id"] ?? null;

if($id === null){
    echo "<p>invalid task id provided</p>";
    die;
}

$task = Database::query("SELECT * FROM tasks WHERE id='$id'")->fetch_all(MYSQLI_ASSOC);
if(!isset($task[0])){
    echo "<p>invalid task id provided</p>";
    die;
}

//calculate the time remaining on the task
$today = time();
$event = $task[0]["end_date"];
$id = $task[0]["id"];
$countDownHours = round(($event - $today) / 3600);

//set our task variables
$name = $task[0]["name"];
$description = $task[0]["description"];
$author = $task[0]["author"];
$id = $task[0]["id"];
$image = $task[0]["image"];


//get a count of all the bids that have been submitted
$bids = count(Database::query("SELECT * FROM bids WHERE task_id='$id'")->fetch_all(MYSQLI_ASSOC));

//get our user if one is logged in

$current_user_id = null;
$has_bid = false;

if($authed){

    //set the current users id
    $current_user_id = $user->getId();

    //check if the user has bid
    if(count(Database::query("SELECT * FROM bids WHERE task_id='$id' AND user_id='$current_user_id'")->fetch_all(MYSQLI_ASSOC)) > 0){
        $has_bid = true;
    }

}


echo "<h2>$name</h2>";
echo "<img src='$image' alt='Task Image' style='width: 100%; margin-bottom: 32px'>";
echo "<p><strong>Description: </strong></p><br>";
echo "<p>$description</p><br>";
echo "<p><strong>Duration: </strong></p><br>";
if($countDownHours > 0){
    echo "<p>$countDownHours hours remaining</p><br>";
}else{
    echo "<p style='color: red;'>bidding closed</p><br>";
}
echo "<p><strong>Author: </strong></p><br>";
if($current_user_id == $task[0]["user_id"]){
    echo "<p>$author (You)</p>";
}else{
    echo "<p>$author</p>";
}
echo "<div class='divider' style='margin-top: 32px'></div>";
echo "<h3>Bidding</h3>";

echo "<p style='margin-bottom: 16px'>".$bids." bids submited by users</p>";

if(!$authed) {
    echo "<p style='color:red; text-align: center; margin-top: 32px;'>Error: please log in to place bid</p>";
    die;
}

if($countDownHours < 0){
    echo "<p>Bidding has been closed for this task, if you have placed bid please confirm your bid on via the 'my bids' page.</p>";
    echo "<br style='margin-bottom: 8px'>";
    echo "<form><button disabled>Bidding ended</button></form>";
    echo "<br>";
    die;
}

    if ($countDownHours > 0) {

        if ($current_user_id == $task[0]["user_id"]) {
            echo "<p>you are the creator of this task, task creators cannot submit bids for their own tasks, please bid on other users";
            echo "<br style='margin-bottom: 8px'>";
            echo "<form><button disabled>Cannot submit bid on own task</button></form>";
            echo "<br>";

            die;
        }

        if ($has_bid) {
            echo "<p style='color: red'>you have already bid on this task, bids can only be submitted once per task";
            echo "<br style='margin-bottom: 8px'>";
            echo "<form><button disabled>Bid already submitted</button></form>";
            echo "<br>";

            die;
        }


        echo '<form action="javascript:Application.instance.placeBid()">';
        echo "<label>
            Bid Amount
            <input type='number' step='0.1' id='bid-amount-input' placeholder='Enter your bid amount'>
            <input type='text' value='" . $id . "' disabled style='display: none' id='bid-task-id'>
         </label>";
        echo "<button>Bid</button>";
        echo "</form>";
        echo "<br>";

    }


