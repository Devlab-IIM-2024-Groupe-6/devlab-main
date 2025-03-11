<?php

namespace App\Twig;

use Twig\TwigFunction;
use App\Repository\DepotRepository;
use App\Repository\DepositRepository;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;

class DepotExtension extends AbstractExtension
{
    private DepositRepository $depotRepository;

    public function __construct(DepositRepository $depotRepository)
    {
        $this->depotRepository = $depotRepository;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('last_depot_id', [$this, 'getLastDepotId']),
        ];
    }

    public function getLastDepotId(): ?int
    {
        $lastDepot = $this->depotRepository->findOneBy([], ['id' => 'DESC']);
        // dump($lastDepot);
        return $lastDepot ? $lastDepot->getId() : null;
    }
}
