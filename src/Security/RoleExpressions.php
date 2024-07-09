<?php

namespace App\Security;

final class RoleExpressions
{
    public const ADMIN_OR_EMPLOYE = 'is_granted("ROLE_ADMIN") or is_granted("ROLE_EMPLOYE")';
    public const ADMIN_OR_VETO = 'is_granted("ROLE_ADMIN") or is_granted("ROLE_VETO")';
    public const ALL = 'is_granted("ROLE_ADMIN") or is_granted("ROLE_EMPLOYE") or is_granted("ROLE_VETO")';
}
