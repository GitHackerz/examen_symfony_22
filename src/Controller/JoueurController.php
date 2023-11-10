<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\JoueurRepository;
use App\Repository\VoteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoueurController extends AbstractController
{
    #[Route('/joueur', name: 'app_joueur')]
    public function index(Request $req, JoueurRepository $joueurRepository, VoteRepository $voteRepository, ManagerRegistry $doctrine): Response
    {
        $joueurs = $joueurRepository->findAll();

        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $vote->setDate(new \DateTime());

            $entityManager->persist($vote);
            $entityManager->flush();

            $sumVote = $voteRepository->getSommeVoteByJoueur($vote->getJoueur());
            $joueur = $vote->getJoueur();
            $joueur->setMoyenneVote($sumVote/count($joueur->getVote()));
            $entityManager->persist($joueur);
            $entityManager->flush();

            return $this->redirectToRoute('app_joueur');
        }

        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurs,
            'f' => $form->createView()
        ]);
    }

    #[Route('/joueur/vote/{id}', name: 'app_joueur_vote')]
    public function getVoteByJoueur(VoteRepository $voteRepository, int $id): Response
    {
        $votes = $voteRepository->findByJoueur($id);
        return $this->render('joueur/vote.html.twig', [
            'votes' => $votes,
        ]);
    }


}
