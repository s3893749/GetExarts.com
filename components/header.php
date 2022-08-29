<?php
/**
 * Copyright Jack Harris
 * Peninsula Interactive - A1_Q3
 * Last Updated - 9/06/2022
 */
?>
<header>
    <nav>
        <ol>
            <li id="logo">
                <a href="javascript:;" onclick="javascript:Application.instance.setPage('home')">GetExarts.com</a>
            </li>
            <li>
                <a href="javascript:;" onclick="javascript:Application.instance.setPage('tasks')">Tasks</a>
            </li>
            <?php if($authed){?>
                <li>
                    <a href="javascript:;" onclick="javascript:Application.instance.setPage('my-bids')">My Bids</a>
                </li>
                <li>
                    <a href="javascript:;" onclick="javascript:Application.instance.setPage('my-tasks')">My Tasks</a>
                </li>
            <?php }?>
        </ol>
        <ol style="flex-direction: row-reverse; margin-right: 32px">
            <?php if($authed){?>
                <li>
                    <a href="javascript:;" onclick="javascript:Application.instance.logout()">Log out</a>
                </li>
                <li>
                    <a href="javascript:;" onclick="javascript:Application.instance.setPage('account')">My Account</a>
                </li>
            <?php }else{?>
                <li>
                    <a href="javascript:;" onclick="javascript:Application.instance.setPage('login')">Login / Register</a>
                </li>
            <?php }?>
        </ol>
    </nav>
</header>
