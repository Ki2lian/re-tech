<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\TagRepository;
use App\Repository\TicketRepository;
use App\Repository\TransactionRepository;
use App\Repository\UserRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/annonces-paid/{token}/{skip}/{fetch}", name="api-annonces-paid-all-params")
     * @Route("/annonces-paid/{token}/{skip}", name="api-annonces-paid-skip")
     * @Route("/annonces-paid/{token}", name="api-annonces-paid-no-param")
     */
    public function allAnnoncesPaid(AnnonceRepository $annonces, string $token, int $skip = 0, int $fetch = 16): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        if ($token === $_ENV['API_TOKEN']) return $this->json($annonces->findBy(array('actif' => 1, 'annonce_payante' => 1), array('id' => 'DESC'), $fetch, $skip), 200, [], ['groups' => 'data-annonce']);
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
     * @Route("/tag/{nom}/{token}", name="api-tag-no-param")
     */
    public function singleTag(TagRepository $tag, $nom, string $token, int $skip = 0, int $fetch = 10): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $tag->findBy(array('nom' => $nom));
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
     * @Route("/annonce-tag/{token}/{listNom}/{skip}/{fetch}", name="api-annonce-tag-all-params")
     * @Route("/annonce-tag/{token}/{listNom}/{skip}", name="api-annonce-tag-skip")
     * @Route("/annonce-tag/{token}/{listNom}", name="api-annonce-tag")
     */
    public function annoncesByTag(AnnonceRepository $annonces, string $listNom, $skip = 0, $fetch = 10): Response
    {
        if ($skip < 0 || $fetch <= 0) return $this->json(["code" => 400, "message" => "Bad request"], 400);
        /*$listId = explode(",", $listId);
        foreach ($listId as $id => $value) {
            if(intval($value) == 0) unset($listId[$id]);
        }
        $listId = array_values($listId);
        // $tab['listId'] = $listId;*/
        $listNom = explode(",", $listNom);
        $tab = $annonces->annonceByTag($listNom, $skip, $fetch);
        return $this->json($tab, 200, [], ['groups' => 'data-annonce']);
        // return $this->json($tab);
    }


    /**
     * @Route("/wishlist/{id}/{token}", name="api-wishlist")
     */
    public function wishlist(WishlistRepository $wishlist, $id, string $token): Response
    {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $wishlist->findBy(array('id_compte' => $id), array('id' => 'DESC'));
            return $data === null ? $this->json(["code" => 404, "message" => "Wishlist not found"]) : $this->json($data, 200 , [], ['groups' => "data-wishlist"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }


    public function getArrayMonthValue($array){
        $months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $temp = [];
        foreach ($array as $value) $temp[$value['MONTH']] = $value['COUNT'];
        for ($i=1; $i <= 12; $i++) if(!isset($temp[$i])) $temp[$i] = 0; 
        ksort($temp);
        for ($i=0; $i < 12; $i++) { 
            $temp[$months[$i]] = $temp[strval($i+1)];
            unset($temp[strval($i+1)]);
        }
        return $temp;
    }

    /**
     * @Route("/admin/dashboard/{token}",  name="api-admin-dashboard")
     */
    public function adminDashboard(TransactionRepository $transaction, UserRepository $user, AnnonceRepository $annonce, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) {
            $productsPostedByMonth = $this->getArrayMonthValue($annonce->countAllProductsPostedByMonth());
            $productsSoldByMonth = $this->getArrayMonthValue($transaction->countAllProductsSoldByMonth());

            
            return $this->json([
                "code" => 200,
                "nbAllUsers" => $user->countAllUsers(),
                "nbAllSellers" => $annonce->countAllSellers(),
                "nbAllAnnonces" => $annonce->countAllAnnonces(),
                "nbAllAnnoncesPaid" => $annonce->countAllAnnoncesPaid(),
                "productsPostedByMonth" => $productsPostedByMonth,
                "productsSoldByMonth" => $productsSoldByMonth,
                "transactions" => json_decode($this->json($transaction->findBy(array(), array('id' => 'DESC'), 7, 0), 200, [], ['groups' => 'data-transaction'])->getContent(), true),
            ]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/admin/transactions/{token}",  name="api-admin-transactions")
     */
    public function adminTransactions(TransactionRepository $transaction, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) return $this->json($transaction->findAll(), 200, [], ['groups' => 'data-transaction']);
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/admin/transaction/{id}/{token}",  name="api-admin-transaction")
     */
    public function adminSingleTransaction(TransactionRepository $transaction, $id, string $token = null): Response {
        if ($token === $_ENV['API_TOKEN']) {
            $data = $transaction->find($id);
            return $data === null ? $this->json(["code" => 404, "message" => "Transaction not found"]) : $this->json($data, 200 , [], ['groups' => "data-transaction"]);
        }
        return $this->json(["code" => 403, "message" => "Access Denied"],403); 
    }

    /**
     * @Route("/search/",  name="api-searchbar")
     */
    public function searchBar(Request $request, AnnonceRepository $ar): Response {
        $q = $request->get('q');
        $annonces = json_decode($this->json($ar->search($q), 200 , [], ['groups' => "data-annonce-search"])->getContent(), true);
        $isAjax = $request->isXMLHttpRequest();
        if (!$isAjax) return new Response('', 404);

        return $this->json(["code" => 200, "annonces" => $annonces],200);
    }
}
