<?php
/**
 * Example file showing how we can use the library.
 * 
 * Any questions ?, e-mail me at: admin@spanlayer.com
 */
 
require_once('gett.php');

$gett = new gett();

/*
 * Authenticate using your own e-mail and password ( Must be registered with ge.tt ).
 */ 
$gett->auth('e-mail', 'password');

/*
 * We'd send requests by calling `request` method.
 *
 *  First Argument: 	(required) Method name to execute,
 * 					For list of available methods, refer to: http://ge.tt/developers
 * Second Argument: (optional) Holds any parameters to be sent with the request.
 * 					Passing parameters convert the request type to POST.
 */

/*
 * Get list of shares you own.
 */
$my_shares = $gett->request('shares');
var_dump($my_shares);

/*
 * Creating new share titled 'New Share'.
 * Method `request` here would send POST Request as there're some parameters passed in the second argument.
 */
$new_share = $gett->request('shares/create', Array('title'=>'New Share !'));
var_dump($new_share);

?>
