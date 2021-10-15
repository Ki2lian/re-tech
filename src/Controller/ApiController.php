<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\TagRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/users/{token}/{skip}/{fetch}", name="api-users-all-params")
     * @Route("/api/users/{token}/{skip}", name="api-users-skip")
     * @Route("/api/users/{token}", name="api-users-no-param")
     */
    public function allUsers(UserRepository $users, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($users->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-user']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/user/{id}/{token}", name="api-user")
     */
    public function singleUser(UserRepository $user, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $user->find($id);
            return $data === null ? $this->json(["code" => 200, "message" => "User not found"]) : $this->json($data, 200 , [], ['groups' => "data-user"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/api/annonces/{token}/{skip}/{fetch}", name="api-annonces-all-params")
     * @Route("/api/annonces/{token}/{skip}", name="api-annonces-skip")
     * @Route("/api/annonces/{token}", name="api-annonces-no-param")
     */
    public function allAnnonces(AnnonceRepository $annonces, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($annonces->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-annonce']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/annonce/{id}/{token}", name="api-annonce")
     */
    public function singleAnnonce(AnnonceRepository $annonce, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $annonce->find($id);
            return $data === null ? $this->json(["code" => 200, "message" => "Annonce not found"]) : $this->json($data, 200 , [], ['groups' => "data-annonce"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/tags/{token}", name="api-tags")
     */
    public function allTags(TagRepository $tags, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) return $this->json($tags->findAll(), 200, [], ['groups' => 'data-tags']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/tag/{id}/{token}/{skip}/{fetch}", name="api-tag-all-params")
     * @Route("/api/tag/{id}/{token}/{skip}", name="api-tag-skip")
     * @Route("/api/tag/{id}/{token}", name="api-tag-no-param")
     */
    public function singleTag(TagRepository $tag, $id, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) {
            $data = $tag->find($id);
            return $data === null ? $this->json(["code" => 200, "message" => "Tag not found"]) : $this->json($data, 200 , [], ['groups' => "data-tag"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/tickets/{token}/{skip}/{fetch}", name="api-tickets-all-params")
     * @Route("/api/tickets/{token}/{skip}", name="api-tickets-skip")
     * @Route("/api/tickets/{token}", name="api-tickets-no-param")
     */
    public function allTickets(TicketRepository $tickets, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($tickets->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-tickets']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/tag/{id}/{token}", name="api-ticket")
     */
    public function singleTicket(TicketRepository $ticket, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $ticket->find($id);
            return $data === null ? $this->json(["code" => 200, "message" => "Ticket not found"]) : $this->json($data, 200 , [], ['groups' => "data-tickets"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/api/annonce-tag/{token}/{listId}", name="api-annonce-tag")
     */
    public function annoncesByTag(AnnonceRepository $annonces, string $listId, string $token): Response
    {
        $listId = explode(",", $listId);
        foreach ($listId as $id => $value) {
            if(intval($value) == 0) unset($listId[$id]);
        }
        $listId = array_values($listId);
        $tab = $annonces->annonceByTag($listId);
        $tab['listId'] = $listId;
        return $this->json($tab);
    }


    /**
     * @Route("/api/wishlist/{id}/{token}", name="api-wishlist")
     */
    public function wishlist(WishlistRepository $wishlist, $id, string $token): Response
    {
        try {
            if ($token === $_ENV['API_TOKEN']) {
                $data = $wishlist->findBy(array('id_compte' => $id));
                return $data === null ? $this->json(["code" => 200, "message" => "Wishlist not found"]) : $this->json($data, 200 , [], ['groups' => "data-wishlist"]);
            }
            return $this->json(["code" => 403, "message" => "Access Denied"],403); 
            
        } catch (\Throwable $th) {
            return $this->json(["code" => 403, "message" => "Access Denied"],403); 
        }
    }

}
