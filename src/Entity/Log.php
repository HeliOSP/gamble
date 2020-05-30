<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Round::class, inversedBy="logs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $round;

    /**
     * @ORM\Column(type="integer")
     */
    private $cell;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRound(): ?Round
    {
        return $this->round;
    }

    public function setRound(?Round $round): self
    {
        $this->round = $round;

        return $this;
    }

    public function getCell(): ?int
    {
        return $this->cell;
    }

    public function setCell(int $cell): self
    {
        $this->cell = $cell;

        return $this;
    }
}
