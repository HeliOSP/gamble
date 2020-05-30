<?php

namespace App\Controller;

use App\Entity\Round;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RoundController extends AbstractController
{
    /**
     * @Route("/round/{roundId}", name="round", requirements={"roundId"="\d+"}, methods={"GET"})
     * 
     * @param string $roundId
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws NotFoundHttpException
     */
    public function get(string $roundId)
    {
        $round = $this->getDoctrine()->getRepository(Round::class)->find($roundId);
        if (!$round) {
            throw new NotFoundHttpException('Round not found');
        }

        return $this->render('round/get.html.twig', ['round' => $round]);
    }

    /**
     * @Route("round/new", name="round_new")
     * 
     * @return RedirectResponse
     */
    public function newRound()
    {
        /** @var Round $round */
        $round = $this->getDoctrine()->getRepository(Round::class)->createRound();
        $this->getDoctrine()->getRepository(User::class)->addUsersToRound($round);

        return $this->redirectToRoute('round', [ 'roundId' => $round->getId() ]);
    }
}