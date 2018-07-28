<?php

namespace Lari\Payments;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Lari\Payments\Events\PaymentRegistered;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PaymentGateway extends Model
{
    protected $guarded = [];

    /**
     * @param Builder $query
     */
    public function scopeActive(Builder $query)
    {
        $query->whereIsActive(true);
    }

    /**
     * @param Builder $query
     */
    public function scopeOrdered(Builder $query)
    {
        $query->orderBy('order');
    }

    /**
     * @param string $key
     * @return PaymentGateway|null
     */
    public static function key(string $key)
    {
        return static::where('key', $key)->first();
    }

    /**
     * @param PaymentTransaction|array $transaction
     * @return PaymentTransaction|Model
     */
    public function recordTransaction($transaction)
    {
        $transaction = $this->createTransaction($transaction);

        PaymentRegistered::dispatch($transaction);

        return $transaction;
    }

    /**
     * Transaciotns
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'gateway_id');
    }

    /**
     * @param $transaction
     * @return false|Model
     */
    protected function createTransaction($transaction)
    {
        if ($transaction instanceof PaymentTransaction) {
            return $this->transactions()->save($transaction);
        }

        return $this->transactions()->create($transaction);
    }
}
