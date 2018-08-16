<?php

/*
 * This is given to password_hash as the cost parameter for bcrypt.
 *
 * 13 equates to about half a second on the CSIS machine, according to Example
 * 4 here: https://secure.php.net/manual/en/function.password-hash.php
 *
 * It seems to scale pretty quickly too; half a second on my crusty Pentium III
 * box at home is only 11. I doubt we'd ever want anything below 10 on a
 * low-traffic site.
 */
$BCRYPT_COST = 13;

// The name of the table used to store login information.
$USERS_TABLE = "prog03_users";

// The maximum size for uploaded files, in octets
$MAX_FILE_SIZE = 20000;

?>
