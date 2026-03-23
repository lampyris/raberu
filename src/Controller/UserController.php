<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/top', name: 'api_users_top', methods: ['GET'])]
    public function topUsers(UserRepository $userRepository): JsonResponse
    {
        $topUsers = $userRepository->findTopUsersByOrdersSum();
        return $this->json($topUsers);
    }

    #[Route('/api/user', name: 'api_user_get', methods: ['GET'])]
    public function getUserById(Request $request, Connection $connection): JsonResponse
    {
        $id = $request->query->get('id');

        if (!$id || !is_numeric($id)) {
            return $this->json(['error' => 'Invalid user ID'], 400);
        }

        $sql = "SELECT id, name FROM users WHERE id = :id";
        $result = $connection->executeQuery($sql, ['id' => $id]);
        $user = $result->fetchAssociative();

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json($user);
    }
}
