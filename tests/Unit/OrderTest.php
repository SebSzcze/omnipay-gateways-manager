<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Lari\Payments\PaymentGateway;
use Lari\Payments\PaymentTransaction;
use Tests\Order;
use Tests\TestCase;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class OrderTest extends TestCase
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
    public function it_can_be_paid()
    {
        $transaction = $this->makeTransaction();

        $this->gateway->recordTransaction($transaction);

        $this->assertTrue($this->order->isPaid());
        $this->assertEquals($this->order->amount, $this->order->paid);
    }

    /**
     * @test
     */
    public function it_can_be_overpaid()
    {
        $transaction = $this->makeTransaction([
            'amount' => $this->order->amount * 2,
        ]);

        $this->gateway->recordTransaction($transaction);

        $this->assertTrue($this->order->isPaid());
        $this->assertEquals($this->order->amount * 2, $this->order->paid);

        $this->assertTrue($this->order->isOverpaid());
        $this->assertEquals($this->order->amount, $this->order->overpayment);
    }

    /**
     * @test
     */
    public function it_can_be_underpaid()
    {
        $transaction = $this->makeTransaction([
            'amount' => (int)($this->order->amount * 0.5),
        ]);

        $this->gateway->recordTransaction($transaction);

        $this->assertFalse($this->order->isPaid());
        $this->assertEquals($this->order->amount * 0.5, $this->order->paid);

        $this->assertTrue($this->order->isUnderpaid());
        $this->assertEquals($this->order->amount * 0.5, $this->order->underpayment);
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
