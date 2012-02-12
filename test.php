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
 * We'd send requests by calling `request` method.
 *
 *  First Argument: 	(required) Method name to execute,
 * 					For list of available methods, refer to: http://ge.tt/developers
 *  Second Argument:	(optional) Holds any parameters to be sent with the request.
 * 					Passing parameters convert the request type to POST.
 */

/*
 * Get list of files you own.
 *
 * You could do $gett->getShare('mytitle'); to get only a specific share
 */
$my_shares = $gett->getShare();
var_dump($my_shares);

/*
 * See here what you can do with the request method https://open.ge.tt/1/doc/rest
 */
//$new_share = $gett->request('shares/create', array('title' => 'New Share!'));
$new_share = $gett->newShare('New Share!'); // both methods are the same
var_dump($new_share);
