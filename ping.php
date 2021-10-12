<?php

$address = $_POST["url"];
$command = "ping -c 1 ".$address;

$result = shell_exec($command);

echo $result;

echo "<br>";

if (strpos($result, "Destination Host Unreachable")){
    echo $address." is DOWN";
}


?>
