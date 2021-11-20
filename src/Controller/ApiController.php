<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\TagRepository;
use App\Repository\TicketRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/users/{token}/{skip}/{fetch}", name="api-users-all-params")
     * @Route("/users/{token}/{skip}", name="api-users-skip")
     * @Route("/users/{token}", name="api-users-no-param")
     */
    public function allUsers(UserRepository $users, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($users->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-user']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/admin/users/{token}", name="api-admin-users-no-param")
     */
    public function adminAllUsers(UserRepository $user, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) return $this->json($user->findBy(array('actif' => 1), array('id' => 'DESC')), 200, [], ['groups' => 'data-user']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/user/{id}/{token}", name="api-user")
     */
    public function singleUser(UserRepository $user, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $user->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "User not found"]) : $this->json($data, 200 , [], ['groups' => "data-user"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/annonces/{token}/{skip}/{fetch}", name="api-annonces-all-params")
     * @Route("/annonces/{token}/{skip}", name="api-annonces-skip")
     * @Route("/annonces/{token}", name="api-annonces-no-param")
     */
    public function allAnnonces(AnnonceRepository $annonces, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($annonces->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-annonce']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/admin/annonces/{token}", name="api-admin-annonces-no-param")
     */
    public function adminAllAnnonces(AnnonceRepository $annonces, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) return $this->json($annonces->findBy(array('actif' => 1), array('id' => 'DESC')), 200, [], ['groups' => 'data-annonce']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/annonce/{id}/{token}", name="api-annonce")
     */
    public function singleAnnonce(AnnonceRepository $annonce, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $annonce->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "Annonce not found"]) : $this->json($data, 200 , [], ['groups' => "data-annonce"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/tags/{token}", name="api-tags")
     */
    public function allTags(TagRepository $tags, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) return $this->json($tags->findAll(), 200, [], ['groups' => 'data-tags']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/tag/{id}/{token}/{skip}/{fetch}", name="api-tag-all-params")
     * @Route("/tag/{id}/{token}/{skip}", name="api-tag-skip")
     * @Route("/tag/{id}/{token}", name="api-tag-no-param")
     */
    public function singleTag(TagRepository $tag, $id, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) {
            $data = $tag->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "Tag not found"]) : $this->json($data, 200 , [], ['groups' => "data-tag"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/tickets/{token}/{skip}/{fetch}", name="api-tickets-all-params")
     * @Route("/tickets/{token}/{skip}", name="api-tickets-skip")
     * @Route("/tickets/{token}", name="api-tickets-no-param")
     */
    public function allTickets(TicketRepository $tickets, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($tickets->findBy(array('actif' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-tickets']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/ticket/{id}/{token}", name="api-ticket")
     */
    public function singleTicket(TicketRepository $ticket, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $ticket->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "Ticket not found"]) : $this->json($data, 200 , [], ['groups' => "data-tickets"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403);
    }

    /**
     * @Route("/annonce-tag/{token}/{listId}/{skip}/{fetch}", name="api-annonce-tag-all-params")
     * @Route("/annonce-tag/{token}/{listId}/{skip}", name="api-annonce-tag-skip")
     * @Route("/annonce-tag/{token}/{listId}", name="api-annonce-tag")
     */
    public function annoncesByTag(AnnonceRepository $annonces, string $listId, $skip = 0, $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        $listId = explode(",", $listId);
        foreach ($listId as $id => $value) {
            if(intval($value) == 0) unset($listId[$id]);
        }
        $listId = array_values($listId);
        $tab = $annonces->annonceByTag($listId, $skip, $fetch);
        $tab['listId'] = $listId;
        return $this->json($tab);
    }


    /**
     * @Route("/wishlist/{id}/{token}", name="api-wishlist")
     */
    public function wishlist(WishlistRepository $wishlist, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $wishlist->findBy(array('id_compte' => $id));
            return $data === null ? $this->json(["code" => 404, "message" => "Wishlist not found"]) : $this->json($data, 200 , [], ['groups' => "data-wishlist"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/admin/dashboard/{token}", "api-admin-dashboard")
     */
    public function adminDashboard(TransactionRepository $transaction, UserRepository $user, AnnonceRepository $annonce, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) {
            return $this->json([
                "code" => 200,
                "nbAllUsers" => $user->countAllUsers(),
                "nbAllSellers" => $annonce->countAllSellers(),
                "nbAllAnnonces" => $annonce->countAllAnnonces(),
                "nbAllAnnoncesPaid" => $annonce->countAllAnnoncesPaid(),
                "transactions" => json_decode($this->json($transaction->findBy(array(), array('id' => 'DESC'), 7, 0), 200, [], ['groups' => 'data-transaction'])->getContent(), true),
            ]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/admin/transactions/{token}", "api-admin-transactions")
     */
    public function adminTransactions(TransactionRepository $transaction, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) return $this->json($transaction->findAll(), 200, [], ['groups' => 'data-transaction']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/admin/transaction/{id}/{token}", "api-admin-transaction")
     */
    public function adminSingleTransaction(TransactionRepository $transaction, $id, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $transaction->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "Transaction not found"]) : $this->json($data, 200 , [], ['groups' => "data-transaction"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }
}
