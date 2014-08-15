<?php

namespace RKA\Validator;

use Zend\Validator\EmailAddress as BaseEmailAddress;

class EmailAddress extends BaseEmailAddress
{
    protected $messageTemplates = array(
        self::INVALID            => "Invalid type given. String expected",
        self::INVALID_FORMAT     => "Invalid email address",
        self::INVALID_HOSTNAME   => "Invalid email address",
        self::INVALID_MX_RECORD  => "Invalid email address",
        self::INVALID_SEGMENT    => "Invalid email address",
        self::DOT_ATOM           => "Invalid email address",
        self::QUOTED_STRING      => "Invalid email address",
        self::INVALID_LOCAL_PART => "Invalid email address",
        self::LENGTH_EXCEEDED    => "Email address is too long",
    );
}
