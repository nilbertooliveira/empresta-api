<?php

namespace Tests\Unit;

use Tests\TestCase;

class SimulationTest extends TestCase
{
    /**
     * @var array
     */
    private array $parameters = [
        'valor_emprestimo' => 1000,
        'instituicoes' => [
            'BMG',
            'OLE',
            'PAN'
        ],
        'convenios' => [
            'INSS',
            'FEDERAL',
            'SIAPE'
        ],
        'parcelas' => 72
    ];

    public function testGetInstitutions()
    {
        $response = $this->getJson('/api/institutions');
        $response->assertStatus(200);
    }

    public function testGetSimulations()
    {
        $response = $this->postJson('/api/simulations', $this->parameters);
        $response->assertStatus(200);
        $response->assertJsonStructure(['simulacoes'=>['BMG']]);
    }

    public function testGetInsurances()
    {
        $response = $this->getJson('/api/insurances');
        $response->assertStatus(200);
    }

}
