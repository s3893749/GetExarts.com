<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 11/06/2022
 */

if(!$authed){
    echo "<div id=redirect>login</div>";
}else{

?>

<main>
    <section>
        <h1>My Account</h1>
        <p style="text-align: center"><strong>Name: </strong><?php echo $user->getFirstName()." ".$user->getLastName()?></p>
        <p style="text-align: center"><strong>Email: </strong><?php echo $user->getEmail();?></p>
        <br>
        <p style="text-align: center">User details cannot be edited at this time</p>
    </section>
</main>
    <?php
}