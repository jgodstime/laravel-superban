<?php
namespace LaravelSuperBan\SuperBan\Exceptions;


use Exception;

class SuperbanInvalidArgumentException extends Exception
{
    public function __construct($message = 'Invalid cache driver for superban.', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
