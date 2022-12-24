<?php

declare(strict_types=1);

namespace App\Domain\Security;

use Symfony\Component\Security\Core\Security;

interface SecurityInterface
{
    public function security(Security $security): string;
}
