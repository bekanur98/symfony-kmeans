<?php

namespace App\Entity;

use App\Repository\KclustersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KclustersRepository::class)
 */
class Kclusters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $firstDim;

    /**
     * @ORM\Column(type="integer")
     */
    private $secondDim;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstDim()
    {
        return $this->firstDim;
    }

    public function setFirstDim($firstDim): void
    {
        $this->firstDim = $firstDim;
    }

    public function getSecondDim()
    {
        return $this->secondDim;
    }

    public function setSecondDim($secondDim): void
    {
        $this->secondDim = $secondDim;
    }

}
