<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lari\Payments\PaymentGateway;
use Tests\TestCase;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase;
    
    /**
    * @test 
    */
    public function it_filters_only_active_gateways()
    {
        factory(PaymentGateway::class)->create();
        factory(PaymentGateway::class)->create([
            'name' => 'Aktywny',
            'is_active' => true
        ]);
        
        $active = PaymentGateway::active()->get();
        
        $this->assertCount(1, $active);
        $this->assertEquals('Aktywny', $active[0]->name);
    }
    
    /**
    * @test 
    */
    public function it_orders_gateways()
    {
        factory(PaymentGateway::class)->create([
            'name' => 'jeden'
        ]);
        factory(PaymentGateway::class)->create([
            'name' => 'dwa',
            'order' => 1 
        ]);
        factory(PaymentGateway::class)->create([
            'name' => 'trzy',
            'order' => 2
        ]);
        $ordered = PaymentGateway::ordered()->get();

        $this->assertCount(3, $ordered);

        $this->assertEquals('jeden', $ordered[0]->name);
        $this->assertEquals('dwa', $ordered[1]->name);
        $this->assertEquals('trzy', $ordered[2]->name);
    }

    /**
    * @test
    */
    public function it_gets_gateway_by_code()
    {
        $key = str_random(20);
        factory(PaymentGateway::class)->create([
            'key' => $key,
        ]);
        $gateway = PaymentGateway::key($key);
        
        $this->assertEquals($key, $gateway->key);
    }

    /**
    * @test 
    */
    public function it_records_transactions()
    {
        $gateway = factory(PaymentGateway::class)->create([
            'name' => 'PayU',
            'key' => 'payu-1',
        ]);
        $this->assertCount(0, $gateway->transactions);

        $gateway->recordTransaction([
            'order_id' => 1,
            'provider_id' => 'provider-id',
            'amount' => 10000,
            'data' => [
                'transaction' => 'id'
            ] 
        ]);

        $this->assertCount(1, $gateway->fresh()->transactions);
    }
}
