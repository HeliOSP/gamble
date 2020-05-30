<?php

namespace App\Controller;

use App\Entity\Round;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MiscController extends AbstractController
{
    /**
     * @Route("/misc/active_players", name="misc_active_players")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function activePlayers()
    {
        $activePlayers = $this->getDoctrine()->getRepository(User::class)->getActiveUsers();

        return $this->json([
            'activePlayers' => $activePlayers
        ]);
    }
    /**
     * @Route("/misc/round_players", name="misc_round_players")
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function roundPlayers()
    {
        $roundPlayers = $this->getDoctrine()->getRepository(Round::class)->getRoundPlayers();

        return $this->json([
            'roundPlayers' => $roundPlayers
        ]);
    }
}