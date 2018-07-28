<?php

namespace Tests;


use Illuminate\Database\Eloquent\Model;
use Lari\Payments\Traits\HasPaymentTransactions;

/**
 * Fake class for tests
 *
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Order extends Model
{
    use HasPaymentTransactions;
    protected $guarded = [];

    /**
     * @param $data
     * @return Order
     */
    public static function create(array $data = [])
    {
        return new static(
            ['id' => rand(0, 10000000)] + $data
        );
    }
}
