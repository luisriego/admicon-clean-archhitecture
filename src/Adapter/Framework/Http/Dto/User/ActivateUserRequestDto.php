<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Dto\User;

use App\Adapter\Framework\Http\Dto\RequestDto;
use Symfony\Component\HttpFoundation\Request;

class ActivateUserRequestDto implements RequestDto
{
    public readonly ?string $id;
    public readonly ?string $token;

    public function __construct(Request $request)
    {
        $this->id = $request->request->get('id');
        $this->token = $request->request->get('token');
    }
}
