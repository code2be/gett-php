<?php
/**
 * Example file showing how we can use the library.
 * 
 * Any questions? https://github.com/AhmedHosny/gett-php/issues
 */
require_once('gett.class.php');

/*
 * APIKey is required
 * Authenticate using your own e-mail and password (Must be registered on ge.tt).
 * 
 * @see http://ge.tt/developers
 */
$gett = new gett('APIKey', 'e-mail', 'password');

/*
 * Get list of files you own.
 *
 * You could do $gett->get_share('mytitle'); to get only a specific share
 */
$my_shares = $gett->get_share();
var_dump($my_shares);

/*
 * Here you can create new share titled "New Share !"
 */
$new_share = $gett->new_share('New Share!'); // both methods are the same
var_dump($new_share);

?>