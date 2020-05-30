<?php

namespace App\Entity;

use App\Repository\RoundRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoundRepository::class)
 */
class Round
{
    const ROUND_FINISHED = 1;
    const ROUND_NOT_FINISHED = 0;
    const ROUND_INVALID_CELL = -1;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $startState;

    /**
     * @ORM\Column(type="text")
     */
    private $currentState;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished = 0;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="round")
     */
    private $logs;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="rounds")
     */
    private $users;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartState(): ?string
    {
        return $this->startState;
    }

    public function setStartState(string $startState): self
    {
        $this->startState = $startState;

        return $this;
    }

    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    public function getCurrentStateArray(): ?array
    {
        return json_decode($this->getCurrentState(), true);
    }

    public function setCurrentState(string $currentState): self
    {
        $this->currentState = $currentState;

        return $this;
    }

    public function getFinished(): ?bool
    {
        return $this->finished;
    }

    public function setFinished(bool $finished): self
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * @return Collection|Log[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setRound($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->contains($log)) {
            $this->logs->removeElement($log);
            // set the owning side to null (unless already changed)
            if ($log->getRound() === $this) {
                $log->setRound(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
}
