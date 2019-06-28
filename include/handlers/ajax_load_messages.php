<?php

    include_once("../../config/config.php");
    include_once("../classes/User.php");
    include_once("../classes/Message.php");

    $limit = 7;
    $message = new Message($con, $_REQUEST['user']);
    echo $message->getConvosDropdown($_REQUEST, $limit)
?>