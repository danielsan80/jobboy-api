<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\Security;

class RequiredRoleProvider
{
    private $role;

    public function __construct(string $role = Roles::ROLE_JOBBOY)
    {
        $this->role = $role;
    }

    public function get(): string
    {
        return $this->role;
    }
}
