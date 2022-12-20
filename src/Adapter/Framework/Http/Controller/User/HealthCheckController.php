<?php

declare(strict_types=1);

namespace App\Adapter\Framework\Http\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    /**
     * @Route("/health-check", name="user_health_check")
     */
    public function __invoke(): Response
    {
        return $this->json(['message' => 'Module User up and running!']);
    }
}
