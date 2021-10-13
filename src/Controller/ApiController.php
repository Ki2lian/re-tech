<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/users/{token}", name="api-users")
     */
    public function allUsers(UserRepository $users, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            return $this->json($users->findAll());
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/user/{id}/{token}", name="api-user")
     */
    public function singleUser(UserRepository $user, int $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $user->find($id);
            if ($data === null) {
                return $this->json(["code" => 200, "message" => "User not found"]);
            }
            return $this->json($data);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }
}
