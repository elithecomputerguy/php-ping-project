# php-ping-project

This is a simple project to show that you can send a variable value to a PHP script and then print out the results.

ping.html
you submit a domain name or IP ddress and this is sent to the ping.php script

ping.php
turns the url/ip address into a variable, and then uses the shell_exec() function to ping that variable value

the response is then printed on the screen, and using strpos() it evaulates whetehr "Destination Host Unreachable", and if so prints $address." is down"
