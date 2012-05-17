<?php
namespace Freshteapot\Http;

class Response
{
	public $code;
	public $message;

	CONST ACCESS_GRANTED = 'allow';
	CONST ACCESS_DENIED='access.denied';

	CONST SAVE_FAIL='insert.failed';
	CONST SAVE_BADDATA = 'missing.data';

	CONST DELETE_SUCCESS = 'delete.success';
	CONST DELETE_NOTHING = 'delete.nothing';
	CONST DELETE_FAIL = 'delete.failed';
	CONST NOTHING_TODO = 'No change';
	CONST MISSING_DATA = 'Data expected but missing';

	CONST ERROR_LOG = 'log.it';
	CONST SUCCESS = 'ok';


	function __construct( $code, $message )
	{
		$this->code = $code;
		$this->message = $message;
	}
	
	function toJSON ()
	{
		return json_encode( $this );
	}
}