<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Lari\Payments\Events\PaymentRegistered;
use Lari\Payments\PaymentGateway;
use Lari\Payments\PaymentTransaction;
use Webpatser\Uuid\Uuid;
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
     * @var Order
     */
    private $order;

    /**
     * @var PaymentGateway
     */
    private $gateway;

    protected function setUp()
    {
        parent::setUp();

        $this->order = Order::create([
            'amount' => 15000,
        ]);
        $this->gateway = factory(PaymentGateway::class)->create([
            'name' => 'PayU',
            'key'  => 'payu',
        ]);
    }

    /**
     * @test
     */
    public function it_fires_an_event_after_transaction_is_recorded()
    {
        Event::fake();

        $transaction = $this->makeTransaction(['id' => Uuid::generate()->string ]);
        $transaction = $this->gateway->recordTransaction($transaction);

        Event::assertDispatched(PaymentRegistered::class, function ($event) use($transaction){
            return $event->transaction->id == $transaction->id;
        });
    }

    /**
     * @param array $attributes
     * @return PaymentTransaction
     */
    protected function makeTransaction(array $attributes = [])
    {
        return factory(PaymentTransaction::class)
            ->make(array_merge([
                'order_id' => $this->order->id,
                'amount'   => $this->order->amount,
            ], $attributes));
    }
}
