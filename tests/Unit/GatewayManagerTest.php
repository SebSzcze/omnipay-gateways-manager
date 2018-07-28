<?php

namespace Tests\Unit;

use Illuminate\Contracts\Cache\Repository;
use Lari\Payments\Services\GatewayManager;
use Mockery;
use Tests\TestCase;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class GatewayManagerTest extends TestCase
{
    /**
     * @var GatewayManager
     */
    private $service;

    protected function setUp()
    {
        parent::setUp(); 
        
        $this->service = Mockery::mock(GatewayManager::class, [app(Repository::class)])->makePartial();
    }
    public function tearDown()
    {
        Mockery::close();
    }

    /**
    * @test
    */
    public function it_gets_list_of_available_gateways()
    {
        $this->markTestIncomplete('To do');
        $this->service->shouldReceive('getGatewayNamespaces')
                      ->andReturn(collect([
                          'PayU' => '\Omnipay\PayU\Gateway'
                      ]));
        $gateways = $this->service->getAvailableGateways();
    }


}
