<?php

namespace App\Controller;

use App\Entity\Round;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        $currentRounds = $this->getDoctrine()->getRepository(Round::class)->getActiveRounds();

        return $this->render('index/index.html.twig', [
            'currentRounds' => $currentRounds
        ]);
    }
}