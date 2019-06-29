<?php

    include_once("../../config/config.php");
    include_once("../classes/User.php");
    include_once("../classes/Notification.php");

    $limit = 7;
    $notification = new Notification($con, $_REQUEST['userLoggedIn']);
    echo $notification->getNotication($_REQUEST, $limit)

?>