<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Repository\WishlistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="adminHome")
     */
    public function index(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminDashboard', [
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/index.html.twig', [
            'infos' => json_decode($response->getContent(), true)
        ]);
    }

    /**
     * @Route("/annonces", name="adminAnnonces")
     */
    public function annonces(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminAllAnnonces', [
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/annonces.html.twig', [
            'annonces' => json_decode($response->getContent(), true)
        ]);
    }

    /**
     * @Route("/annonce/{id}", name="adminAnnonceDetail")
     */
    public function annonceDetail($id = 0): Response
    {
        if($id == 0) return $this->redirectToRoute('adminAnnonceDetail', array('id' => 1));

        $response = $this->forward('App\Controller\ApiController::singleAnnonce', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $annonce = json_decode($response->getContent(), true);
        if(isset($annonce['code']) && $annonce['code'] == 404) return $this->redirectToRoute('adminHome');

        return $this->render('admin/annonce_detail.html.twig', [
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/utilisateurs", name="adminUtilisateurs")
     */
    public function utilisateurs(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminAllUsers', [
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/utilisateurs.html.twig', [
            'users' => json_decode($response->getContent(), true)
        ]);
    }

    /**
     * @Route("/utilisateur/{id}", name="adminUtilisateurDetail")
     */
    public function utilisateurDetail($id = 0, TransactionRepository $tr, WishlistRepository $wr): Response
    {
        if($id == 0) return $this->redirectToRoute('adminUtilisateurDetail', array('id' => 1));

        $response = $this->forward('App\Controller\ApiController::singleUser', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $user = json_decode($response->getContent(), true);
        if(isset($user['code']) && $user['code'] == 404) return $this->redirectToRoute('adminHome');

        return $this->render('admin/utilisateur_detail.html.twig', [
            'user' => $user,
            'nbProductsSold' => $tr->nbProductsSold($id),
            'nbProductsTracked' => $wr->nbProductsTracked($id)
        ]);
    }
    
    /**
     * @Route("/transactions", name="adminTransactions")
     */
    public function transactions(): Response
    {
        $response = $this->forward('App\Controller\ApiController::adminTransactions', [
            'token' => $_ENV['API_TOKEN'],
        ]);

        return $this->render('admin/transactions.html.twig', [
            'transactions' => json_decode($response->getContent(), true)
        ]);
    }
    /**
     * @Route("/transaction/{id}", name="adminTransactionDetail")
     */
    public function transactionDetail($id = 0): Response
    {
        if($id == 0) return $this->redirectToRoute('adminTransactionDetail', array('id' => 1));
        $response = $this->forward('App\Controller\ApiController::adminSingleTransaction', [
            'token' => $_ENV['API_TOKEN'],
            'id' => $id
        ]);

        $transaction = json_decode($response->getContent(), true);
        if(isset($transaction['code']) && $transaction['code'] == 404) return $this->redirectToRoute('adminHome');

        return $this->render('admin/transaction_detail.html.twig', [
            'transaction' => $transaction
        ]);
    }
}
