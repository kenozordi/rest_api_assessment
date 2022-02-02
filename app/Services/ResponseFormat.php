<?php

namespace App\Services;

trait ResponseFormat
{
	protected static function formatResponse($status, $message = null, $data = null)
	{
		$response = [
			"status" => $status,
			"message" => $message
		];
		if ($data) $response["data"] = $data;
		return $response;
	}

	/**
	 * This method formats the response to be sent to the client.
	 * 
	 * @param $status	The status of the request; "OK", "FAIL"
	 * @param $message	A Message to give more description about the response
	 * @param $data	The data to be sent with the response
	 * @return \Illuminate\Http\Response
	 */
	public static function returnResponse($status, $message = null, $data = null)
	{
		return response()->json(self::formatResponse($status, $message, $data), 200);
	}

}
