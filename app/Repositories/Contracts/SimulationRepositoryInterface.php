<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface SimulationRepositoryInterface
{

    public function getFileInstitutions() : void;

    public function getFileInsurances() : void;

    public function getFilesRates() : void;

    public function removeKeyCollection(Collection $collections, string $key) :Collection;

    public function getInstitutions() : array;

    public function getInsurances() : array;

    public function getRates(array $parameters) : Collection;

    public function getInstitutionsWithoutKeys() : Collection;

    public function getInsurancesWithoutKeys() : Collection;

    public function getGroupInstitutionsRates() : Collection;

    public function filterInstitutions(Collection $collection, array $parameters) : Collection;

    public function filterInsurances(Collection $collections, array $parameters) : Collection;

    public function filterInstallments(Collection $collections, array $parameters) : Collection;
}
