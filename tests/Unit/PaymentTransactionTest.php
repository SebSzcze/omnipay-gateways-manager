<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Lari\Payments\PaymentGateway;
use Lari\Payments\PaymentTransaction;
use Lari\Payments\Traits\HasPaymentTransactions;
use Tests\Order;
use Tests\TestCase;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PaymentTransactionTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test 
    */
    public function it_can_be_marked_as_test()
    {
        $gateway = $this->createGateway();
        $transaction = factory(PaymentTransaction::class)->make([
            'order_id' => 1,
            'provider_id' => 'my-id'
        ]);

        /** @var PaymentTransaction $transaction */
        $transaction = $gateway->recordTransaction($transaction)->fresh();
        
        $this->assertFalse($transaction->is_test);

        $transaction->isTest(true);
        $this->assertTrue($transaction->is_test);
    }
    
    /**
    * @test 
    */
    public function it_belongs_to_order()
    {
        $order = $this->createOrder();
        $gateway = $this->createGateway();
        $transaction = factory(PaymentTransaction::class)->make([
            'order_id' => $order->id,
            'amount' => $order->amount 
        ]);
        
        $gateway->recordTransaction($transaction);
        
        $this->assertCount(1, $order->transactions);
    }

    public function createOrder(array $data = [])
    {
        return Order::create([
            'amount' =>  15000
        ] + $data);
    }

    /**
     * @param array $data
     * @return PaymentGateway
     */
    protected function createGateway(array $data = [])
    {
        return factory(PaymentGateway::class)->create([
            'name' => 'PayU',
            'key'  => 'payu',
        ] + $data);
    }

}
