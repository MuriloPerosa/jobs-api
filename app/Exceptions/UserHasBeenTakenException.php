<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserHasBeenTakenException extends Exception
{
    /**
     * HTTP answer to exception
     * @return array
     */
    public function render()
    {
        return response()->json([
            'error'   => class_basename($this),
            'message' => 'User has been taken.'
        ], Response::HTTP_BAD_REQUEST);
    }
}
