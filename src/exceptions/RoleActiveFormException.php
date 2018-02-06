<?php

namespace misterspelik\widgets\exceptions;

use yii\base\UserException;

class RoleActiveFormException extends UserException
{
    /**
     * Constructor.
     * @param int $status HTTP status code, such as 404, 500, etc.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Role ActiveForm misconfiguration';
    }
}
