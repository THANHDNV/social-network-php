<?php

    include_once("../../config.php");
    include_once("../classes/User.php");
    
    $query = $_POST['query'];
    $userLoggedIn = $_POST['userLoggedIn'];

    $names = explode(" ", $query);

    if(strpos($query, "_") !== false) {
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE 'query%' AND user_closed='no' LIMIT 8");
    } else if(count($name) == 2) {
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
    } else {
        $usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
    }

    if ($query != '') {
        while($row = mysqli_fetch_array($usersReturned)) {
            $user = new User($con, $userLoggedIn);

            if ($row['username'] != $userLoggedIn) {
                $mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
            } else {
                $mutual_friends = "";
            }
        }
    }

?>