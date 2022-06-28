<?php

namespace App\Repositories;

use App\Repositories\Contracts\SimulationRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class SimulationRepository implements SimulationRepositoryInterface
{

    /**
     * @var Collection
     */
    private Collection $institutions;

    /**
     * @var Collection
     */
    private Collection $insurances;

    /**
     * @var Collection
     */
    private Collection $rates;

    /**
     * @var string
     */
    private string $pathJsonInstitutions;

    /**
     * @var string
     */
    private string $pathJsonInsurances;

    /**
     * @var string
     */
    private string $pathJsonRates;


    public function __construct()
    {
        $this->pathJsonInstitutions = env('PATHJSONINSTITUTIONS');
        $this->pathJsonInsurances = env('PATHJSONINSURANCES');
        $this->pathJsonRates = env('PATHJSONRATES');

        $this->getFileInstitutions();
        $this->getFileInsurances();
        $this->getFilesRates();

        /**
         * Make function uppercase
         */
        Collection::macro('toUpper', function () {
            return $this->map(function ($value) {
                return Str::upper($value);
            });
        });
    }

    /**
     * @return void
     */
    public function getFileInstitutions(): void
    {
        $temp = Storage::disk('local')->get($this->pathJsonInstitutions);
        $temp = json_decode($temp);
        $this->institutions = collect($temp);
    }

    /**
     * @return void
     */
    public function getFileInsurances(): void
    {
        $temp = Storage::disk('local')->get($this->pathJsonInsurances);
        $temp = json_decode($temp);
        $this->insurances = collect($temp);
    }

    /**
     * @return void
     */
    public function getFilesRates(): void
    {
        $temp = Storage::disk('local')->get($this->pathJsonRates);
        $temp = json_decode($temp);
        $this->rates = collect($temp);
    }

    /**
     * Remove one level array
     * @param Collection $collections
     * @param string $key
     * @return Collection
     */
    public function removeKeyCollection(Collection $collections, string $key): Collection
    {
        foreach ($collections as $i => $collection) {
            foreach ($collection as $j => $values) {
                if (isset($values->$key)) {
                    unset($values->$key);
                    $collections[$i][$j] = $values;
                }
            }
        }
        return $collections;
    }

    /**
     * @return array
     */
    public function getInstitutions(): array
    {
        return $this->institutions->sortBy('valor')->values()->all();
    }

    /**
     * @return array
     */
    public function getInsurances(): array
    {
        return $this->insurances->sortBy('valor')->values()->all();
    }

    public function getRates(array $parameters): Collection
    {
        $groupRates = $this->getGroupInstitutionsRates();
        $groupRates = $this->removeKeyCollection($groupRates, 'instituicao');
        $groupRates = $this->filterInstitutions($groupRates, $parameters);
        $groupRates = $this->filterInsurances($groupRates, $parameters);
        $groupRates = $this->filterInstallments($groupRates, $parameters);

        /**
         * Remove empty keys
         */
        return $groupRates->reject(function ($value) {
            return count($value) == 0;
        });
    }

    /**
     * @return Collection
     */
    public function getInstitutionsWithoutKeys(): Collection
    {
        return $this->institutions->groupBy('valor')->keys()->toUpper();
    }

    /**
     * @return Collection
     */
    public function getInsurancesWithoutKeys(): Collection
    {
        return $this->insurances->groupBy('valor')->keys()->toUpper();
    }

    /**
     * @return Collection
     */
    public function getGroupInstitutionsRates(): Collection
    {
        return $this->rates->groupBy('instituicao');
    }

    /**
     * @param Collection $collection
     * @param array $parameters
     * @return Collection
     */
    public function filterInstitutions(Collection $collection, array $parameters): Collection
    {
        if (!isset($parameters['instituicoes'])) {
            return $collection;
        }
        $param = array_flip($parameters['instituicoes']);

        $intersect = $collection->intersectByKeys($param);
        $intersect->values()->all();

        return $intersect;
    }

    /**
     * @param Collection $collections
     * @param array $parameters
     * @return Collection
     */
    public function filterInsurances(Collection $collections, array $parameters): Collection
    {
        if (!isset($parameters['convenios'])) {
            return $collections;
        }
        foreach ($collections as $i => $collection) {
            foreach ($collection as $j => $values) {
                if (!in_array($values->convenio, $parameters['convenios'])) {
                    unset($collections[$i][$j]);
                }
            }
            $collections[$i] = $collection->values();
        }
        return $collections;
    }

    /**
     * @param Collection $collections
     * @param array $parameters
     * @return Collection
     */
    public function filterInstallments(Collection $collections, array $parameters): Collection
    {
        if (!isset($parameters['parcelas'])) {
            return $collections;
        }
        foreach ($collections as $i => $collection) {
            foreach ($collection as $j => $values) {
                if ($values->parcelas != $parameters['parcelas']) {
                    unset($collections[$i][$j]);
                }
            }
            $collections[$i] = $collection->values();
        }
        return $collections;
    }
}
