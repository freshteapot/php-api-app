<?php
namespace Freshteapot\Api;

use Freshteapot\Http\Response;

class Server
{
	private
		$allowedMethods = array( 'get', 'post', 'put', 'delete', 'patch' );
		
	public
		$response;

	/**
	 * How to pass the headers, so that they can be used.

	 * @param unknown_type $method
	 * @param unknown_type $uri
	 * @param unknown_type $headers
	 */
	function __construct ( $method, $uri, $headers )
	{
		try {
			$this->run( $method, $uri );
		} catch( \InvalidArgumentException $e ) {
			echo "Method not allowed";
		}
	}
	
	private function run ( $method, $uri )
	{
		$this->checkMethod( $method );

		$path = explode( "/", $uri );
		$className = __NAMESPACE__ . "\Controllers\\" . ucfirst( strtolower( $path["1"] ) );
		try {
			$api = new $className();
		} catch ( \Exception $e ) {
			echo "Class doesnt exist";
			return;
		}

		try {
			//Break this into parts
			$this->response = $api->$method( $uri );
		} catch ( \Exception $e ) {
			$a = new Server("get", "/error/1" );
			$this->response = $a->response;
		}
	}
	
	private function checkMethod ( $method )
	{
		if ( in_array( $method, $this->allowedMethods ) ) {
			return true;
		}
		throw new \InvalidArgumentException( "Bad Method" );
	}
	
	public function __toString ()
	{
		if( $this->response instanceof Response  )
		{
			return $this->response->toJSON();
		}
	}
}
