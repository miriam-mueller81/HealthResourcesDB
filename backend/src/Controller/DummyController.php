<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DummyController
{
    /**
     * @Route("/dummy/index")
     * @return JsonResponse
     */
    public function index()
    {
        $data = [
            'success' => true
        ];

        return new JsonResponse($data);
    }
}
