<?php
namespace Freshteapot\Api\Controllers;

use Freshteapot\Api\HttpApi;
use Freshteapot\Http\Response;

class Error extends HttpApi
{
	function get ()
	{
		return new Response( "400", "Bad Url");
	}
}
