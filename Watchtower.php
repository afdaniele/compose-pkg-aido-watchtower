<?php
# @Author: Andrea F. Daniele <afdaniele>
# @Date:   Wednesday, July 18th 2018
# @Email:  afdaniele@ttic.edu
# @Last modified by:   afdaniele


namespace system\packages\duckietown_watchtower;

use \system\classes\Core;
use \system\classes\Utils;
use \system\classes\Database;
use \system\classes\Configuration;

/**
*   Module for managing watchtowers in Duckietown
*/
class Watchtower{

	private static $initialized = false;

	// disable the constructor
	private function __construct() {}

	/** Initializes the module.
     *
     *	@retval array
	 *		a status array of the form
	 *	<pre><code class="php">[
	 *		"success" => boolean, 	// whether the function succeded
	 *		"data" => mixed 		// error message or NULL
	 *	]</code></pre>
	 *		where, the `success` field indicates whether the function succeded.
	 *		The `data` field contains errors when `success` is `FALSE`.
     */
	public static function init(){
		if( !self::$initialized ){
			// do stuff
			//
			self::$initialized = true;
			return ['success' => true, 'data' => null];
		}else{
			return ['success' => true, 'data' => "Module already initialized!"];
		}
	}//init

	/** Returns whether the module is initialized.
     *
     *	@retval boolean
	 *		whether the module is initialized.
     */
	public static function isInitialized(){
		return self::$initialized;
	}//isInitialized

    /** Safely terminates the module.
     *
     *	@retval array
	 *		a status array of the form
	 *	<pre><code class="php">[
	 *		"success" => boolean, 	// whether the function succeded
	 *		"data" => mixed 		// error message or NULL
	 *	]</code></pre>
	 *		where, the `success` field indicates whether the function succeded.
	 *		The `data` field contains errors when `success` is `FALSE`.
     */
	public static function close(){
		// do stuff
		return [ 'success' => true, 'data' => null ];
	}//close



	// =======================================================================================================
	// Public functions

	// YOUR PUBLIC METHODS HERE



	// =======================================================================================================
	// Private functions

	// YOUR PRIVATE METHODS HERE

}//Watchtower
?>
