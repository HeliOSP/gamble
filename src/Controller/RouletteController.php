<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\Round;
use App\Repository\RoundRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RouletteController extends AbstractController
{
    /**
     * @Route("/roulette/spin", name="roulette_spin", methods={"POST"})
     */
    public function spin(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $roundId = $data['roundId'] ?? 0;
        /** @var RoundRepository $roundRepository */
        $roundRepository = $this->getDoctrine()->getRepository(Round::class);
        /** @var Round $round */
        if ($roundId) {
            $round = $roundRepository->find($roundId);
        } else {
            $round = null;
        }

        $response = [];
        if ($round) {
            $cell = $roundRepository->spinRound($round);

            if ($cell !== Round::ROUND_INVALID_CELL) {
                $this->getDoctrine()->getRepository(Log::class)->addRoundLog($round, $cell);
                $response = [
                    'cell' => $cell,
                    'finished' => $round->getFinished() == Round::ROUND_FINISHED
                ];
            } else {
                $response = [
                    'error' => 'Round finished'
                ];
            }
        } else {
            $response = [
                'error' => 'Incorrect Round'
            ];
        }

        return $this->json($response);
    }
}