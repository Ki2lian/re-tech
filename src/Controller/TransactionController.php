<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/transaction")
 * Transaction fait aussi office de réservation avec le type (bool, 0 pour transac payante pub et 1 pour transac particulier/particulier)
 */
class TransactionController extends AbstractController
{
    /**
     * @Route("/", name="transaction_index", methods={"GET"})
     */
    public function index(TransactionRepository $transactionRepository): Response
    {
        return $this->render('transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new{id}", name="transaction_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Annonce $annonce, $id, EntityManagerInterface $entityManager): Response
    {
        $date = new DateTime();
        $transaction = new Transaction();
        $user = $this->getUser();
        $transaction->setIdCompte($user)
                    ->setIdAnnonce($annonce)
                    ->setDateCreation($date)
                    ->setMoyenPaiement('none');
                    
        $entityManager->persist($transaction);
        $entityManager->flush();

        return $this->redirectToRoute('accueil');
        
    }

    /**
     * @Route("/{id}", name="transaction_show", methods={"GET"})
     */
    public function show(Transaction $transaction): Response
    {
        return $this->render('transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="transaction_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Transaction $transaction): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="transaction_delete", methods={"POST"})
     */
    public function delete(Request $request, Transaction $transaction): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
