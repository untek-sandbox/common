<?php

namespace Untek\User\Authentication\Domain\Enums;

class AuthEventEnum
{

    const BEFORE_AUTH = 'before_auth';
    const AFTER_AUTH_SUCCESS = 'after_auth_success';
    const AFTER_AUTH_ERROR = 'after_auth_error';
    
//    const BEFORE_SET_IDENTITY = 'before_set_identity';
//    const AFTER_SET_IDENTITY = 'after_set_identity';

    const BEFORE_GET_IDENTITY = 'before_get_identity';
    const AFTER_GET_IDENTITY = 'after_get_identity';

    const BEFORE_IS_GUEST = 'before_is_guest';
    const AFTER_IS_GUEST = 'after_is_guest';

//    const BEFORE_LOGIN = 'before_login';
//    const AFTER_LOGIN = 'after_login';
    
    const BEFORE_LOGOUT = 'before_logout';
    const AFTER_LOGOUT = 'after_logout';
}
