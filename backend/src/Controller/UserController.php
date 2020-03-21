<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /** @var ObjectManager */
    protected $entityManager;

    public function __construct(Registry $doctrine)
    {
        $this->entityManager = $doctrine->getManager();
    }

    /**
     * @Route("/api/user", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        $allUser = $this->entityManager->getRepository(User::class)->findAll();
        $userList = [];

        /** @var User $user */
        foreach ($allUser as $user) {
            $userList[] = $user->toArray();
        }

        return new JsonResponse(
            $userList,
            200
        );
    }

    /**
     * @Route("/api/user/{id}", methods={"GET"})
     *
     * @param string $id
     * @return JsonResponse
     */
    public function getOne(string $id): JsonResponse
    {
        $userId = (int) $id;

        if (!$userId) {
            return new JsonResponse(
                [
                    'message' => 'id not valid',
                ],
                400
            );
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(
                [
                    'message' => 'user not found',
                ],
                404
            );
        }

        return new JsonResponse(
            $user->toArray(),
            200
        );
    }

    /**
     * @Route("/api/user/{id}", methods={"PUT"})
     *
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function put(string $id, Request $request): JsonResponse
    {
        $userId = (int) $id;

        if (!$userId) {
            return new JsonResponse(
                [
                    'message' => 'id not valid',
                ],
                400
            );
        }

        $requestData = json_decode($request->getContent());

        if (!$requestData) {
            return new JsonResponse(
                [
                    'message' => 'invalid data',
                ],
                422
            );
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(
                [
                    'message' => 'user not found',
                ],
                404
            );
        }

        $user->setCompany($requestData->company);
        $user->setFirstname($requestData->firstname);
        $user->setLastname($requestData->lastname);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'message' => 'user updated',
                'id' => $user->getId(),
            ],
            200
        );
    }

    /**
     * @Route("/api/user", methods={"POST"})
     *
     * @example
     * {
     *      "company": "Meine Firma",
     *      "firstname": "Martina",
     *      "lastname": "Musterfrau"
     * }
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function post(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent());

        if (!$requestData) {
            return new JsonResponse(
                [
                    'message' => 'invalid data',
                ],
                422
            );
        }

        $user = new User();
        $user->setCompany($requestData->company);
        $user->setFirstname($requestData->firstname);
        $user->setLastname($requestData->lastname);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(
            [
                'message' => 'user created',
                'id' => $user->getId(),
            ],
            200
        );
    }

    /**
     * @Route("/api/user/{id}", methods={"DELETE"})
     *
     * @return JsonResponse
     */
    public function delete(string $id): JsonResponse
    {
        $userId = (int) $id;

        if (!$userId) {
            return new JsonResponse(
                [
                    'message' => 'id not valid',
                ],
                400
            );
        }

        /** @var User $user */
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(
                [
                    'message' => 'user not found',
                ],
                404
            );
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse('user deleted');
    }
}
