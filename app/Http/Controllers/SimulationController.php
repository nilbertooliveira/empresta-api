<?php

namespace App\Http\Controllers;

use App\services\SimulationService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SimulationController extends Controller
{

    /**
     * @var SimulationService
     */
    private SimulationService $service;

    public function __construct(SimulationService $service)
    {
        $this->service = $service;
    }

    public function getInstitutions()
    {
        try {
            return response()->json($this->service->getInstitutions());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
    }

    public function getInsurances()
    {
        try {
            return response()->json($this->service->getInsurances());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
    }

    public function getSimulations(Request $request)
    {
        try {
            return response()->json($this->service->getSimulations($request));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY]);
        }
    }
}
