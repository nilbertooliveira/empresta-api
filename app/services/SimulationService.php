<?php

namespace App\services;

use App\Helpers\Helper;
use App\Repositories\Contracts\SimulationRepositoryInterface;
use App\Validators\SimulationValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class SimulationService
{

    /**
     * @var SimulationRepositoryInterface
     */
    private SimulationRepositoryInterface $repository;

    public function __construct(SimulationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getInstitutions()
    {
        return $this->repository->getInstitutions();
    }

    public function getInsurances()
    {
        return $this->repository->getInsurances();
    }

    /**
     * @param array $rates
     * @param array $parameters
     * @return array|Collection[]|string[]
     */
    public function getSimulations(Request $request): array
    {
        $result = Helper::validatePayload($request);

        if (!$result['success']) {
            return $result;
        }

        $tempParameters = $this->upperCaseParameters($request->all());

        $result = $this->validateParameters($tempParameters);

        if (isset($result['errors'])) {
            return [
                'errors' => $result['errors'],
            ];
        }

        $rates = $this->repository->getRates($tempParameters);

        $simulation = $this->calculateSimulations($rates, $tempParameters);

        return [
            'simulacoes' => $simulation,
        ];
    }

    /**
     * @param Collection $rates
     * @param array $parameters
     * @return Collection
     */
    public function calculateSimulations(Collection $rates, array $parameters): Collection
    {
        $loanAmount = (float)$parameters['valor_emprestimo'];

        foreach ($rates as $key => $institutions) {
            foreach ($institutions as $key2 => $institution) {
                $valueAmount = ($loanAmount * $institution->taxaJuros) / $institution->parcelas;
                $rates[$key][$key2]->valor_parcela = round($valueAmount, 2);
            }
        }
        return $rates;
    }

    /**
     * @param array $parameters
     * @return array|string[]|void
     */
    public function validateParameters(array $parameters)
    {
        $validator = Validator::make($parameters, SimulationValidator::RULE);

        if ($validator->fails()) {
            return [
                'errors' => $validator->errors(),
            ];
        }

        $institutionsCollections = collect($parameters['instituicoes']);
        $institutions = $this->repository->getInstitutionsWithoutKeys();
        $diff = $institutionsCollections->diff($institutions)->values();

        if (count($diff) > 0) {
            return [
                'errors' => "Insurances not found: " . $diff,
            ];
        }

        $insurancesCollections = collect($parameters['convenios']);
        $insurances = $this->repository->getInsurancesWithoutKeys();
        $diff = $insurancesCollections->diff($insurances)->values();

        if (count($diff) > 0) {
            return [
                'errors' => "Insurances not found: " . $diff,
            ];
        }
    }


    /**
     * @param array $parameters
     * @return array
     */
    public function upperCaseParameters(array $parameters): array
    {
        if (isset($parameters['instituicoes'])) {
            $temp = array_flip($parameters['instituicoes']);
            $temp = array_change_key_case($temp, CASE_UPPER);
            $temp = array_flip($temp);
            $parameters['instituicoes'] = $temp;
        }
        if (isset($parameters['convenios'])) {
            $temp = array_flip($parameters['convenios']);
            $temp = array_change_key_case($temp, CASE_UPPER);
            $temp = array_flip($temp);
            $parameters['convenios'] = $temp;
        }
        return $parameters;
    }


}
