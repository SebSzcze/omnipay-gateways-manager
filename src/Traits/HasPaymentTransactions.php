<?php

namespace Lari\Payments\Traits;

use Lari\Payments\PaymentTransaction;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
trait HasPaymentTransactions
{
    /**
     * Transactions
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'order_id')->latest();
    }

    /**
     * @return int
     */
    public function getPaidAttribute(): int
    {
        return $this->transactions->filter->isReal()->sum('amount');
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->amount <= $this->paid;
    }

    /**
     * @return bool
     */
    public function isUnderpaid()
    {
        return $this->paid > 0 && $this->paid < $this->amount;
    }

    /**
     * @return int
     */
    public function getUnderpaymentAttribute(): int
    {
        return $this->amount - $this->paid;
    }

    /**
     * @return bool
     */
    public function isOverpaid()
    {
        return $this->amount < $this->paid;
    }

    /**
     * @return mixed
     */
    public function getOverpaymentAttribute()
    {
        return $this->paid - $this->amount;
    }
}
