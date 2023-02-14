<?php

namespace App\Service;


use App\Repository\EgaFemaleContestantRepository;
use App\Repository\EgaMaleContestantRepository;
use App\Repository\MesseFemaleContestantRepository;
use App\Repository\MesseMaleContestantRepository;

class LimitService
{

    public function __construct(
        private readonly int                             $messeLimit,
        private readonly int                             $egaLimit,
        private readonly EgaFemaleContestantRepository   $egaFemaleContestantRepository,
        private readonly EgaMaleContestantRepository     $egaMaleContestantRepository,
        private readonly MesseFemaleContestantRepository $messeFemaleContestantRepository,
        private readonly MesseMaleContestantRepository   $messeMaleContestantRepository,
    )
    {
    }

    public function messeCup(): int
    {
        return $this->messeLimit
            - $this->messeFemaleContestantRepository->count([])
            - $this->messeMaleContestantRepository->count([]);
    }

    public function egaPokal(): int
    {
        return $this->egaLimit
            - $this->egaFemaleContestantRepository->count([])
            - $this->egaMaleContestantRepository->count([]);
    }
}
