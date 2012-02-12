<?php
set_time_limit(0);
error_reporting(E_ALL);

/**
 * ge.tt PHP Library
 * Easily interact with ge.tt API via PHP.
 * Website:		http://www.spanlayer.com
 *
 * @version		1.0.0
 * @author		Ahmed Hosny <admin@spanlayer.com>, Jacob Groß / kurtextrem <kurtextrem@gmail.com>
 * 
 * @license		This library is Free for everyone to use or modify,
 * 				Keeping the original credits,
 * 				Appending notes and credits for any modifications.
 */
class gett {

	/**
	 * Your ge.tt API Key
	 *
	 * @var		string
	 */
	private $APIKey;

	/**
	 * ge.tt Access Token
	 *
	 * @var		mixed
	 */
	private $accessToken;

	/**
	 * Loads the "Request" Lib
	 *
	 * @param	string		$APIKey
	 */
	public function __construct($APIKey, $email, $password) {
		session_start();
		require_once 'libraries/Requests.php';
		Requests::register_autoloader();

		$this->APIKey = $APIKey;
		$this->auth($email, $password);
	}

	/**
	 * REST API Connection to ge.tt
	 *
	 * @param	string		$method
	 * @param	array		$postData
	 * @return	mixed
	 * @throws	Exception
	 */
	public function request($method, $postData = array()) {
		$post_data_str = json_encode($postData);
		$this->accessToken = $this->accessToken ? $this->accessToken : (isset($_SESSION['gett_accessToken']) ? $_SESSION['gett_accessToken'] : false);

		$accessToken = $this->accessToken ? '?accesstoken=' . $this->accessToken : '';
		try {
			if (is_array($postData) && count($postData) > 0) {
				$request = Requests::post('https://open.ge.tt/1/' . $method . $accessToken, array(), $post_data_str);
			} else {
				$request = Requests::get('https://open.ge.tt/1/' . $method . $accessToken);
			}
		} catch (Exception $e) {
			$this->handleException($e);
		}

		$response = json_decode($request->body);

		try {
			if (isset($response->error) || !$request->success)
				throw new Exception('<i>Error:</i> <strong>' . $response->error . '</strong>', E_USER_ERROR);
			if (is_null($response))
				throw new Exception('<strong>Unknown method <i>(' . $method . ')</i> or <span style="color: white; background-color: lightblue">GE.TT</span> API is down!</strong>', E_USER_ERROR);
		} catch (Exception $e) {
			$this->handleException($e);
		}

		return $response;
	}

	/**
	 * Creates a new share
	 *
	 * @param	string		$title
	 * @return	mixed
	 */
	public function newShare($title) {
		return $this->request('shares/create', array('title' => $title));
	}

	/**
	 * Returns data from a share
	 *
	 * @param	string		$title
	 * @return	mixed
	 */
	public function getShare($title = '') {
		if (!empty($title))
			$title = '/' . $title;

		return $this->request('shares' . $title);
	}

	/**
	 * Renames a share
	 *
	 * @param	string		$title
	 * @return	mixed
	 */
	public function renameShare($oldTitle, $newTitle) {
		return $this->request('shares/' . $oldTitle . '/update', array('title' => $newTitle));
	}

	/**
	 * Deletes a share
	 *
	 * @param	string		$title
	 * @return	mixed
	 */
	public function deleteShare($title) {
		return $this->request('shares/' . $title . '/destroy'); // alternative would be https://open.ge.tt/1/doc/rest#shares/{sharename}/update
	}

	/**
	 * Auth process
	 *
	 * @param	string		$email
	 * @param	string		$password
	 * @return	bool
	 * @throws	Exception
	 */
	private function auth($email, $password) {
		try {
			if (isset($_SESSION['gett_accessToken']) || !empty($this->accessToken))
				return true;
			// throw new Exception($email . ' is already authed.'); // There's no problem if user is already authed
		} catch (Exception $e) {
			$this->handleException($e);
		}


		$user = $this->request('users/login', array(
		    'apikey' => $this->APIKey,
		    'email' => $email,
		    'password' => $password
			));

		$_SESSION['gett_accessToken'] = $user->accesstoken;
		$this->accessToken = $user->accesstoken;
		
		return true;
	}

	/**
	 * Handles caught exceptions
	 *
	 * @param	Exception	$e
	 */
	private function handleException($e) {
		echo '<div class="gettError"><span style="color: white; background-color: lightblue">GE.TT</span> ' . $e->getMessage() . '<br><code>in <b>' . $e->getFile() . '</b> on line <b>' . $e->getLine() . '</b></code></div>';
	}

}
