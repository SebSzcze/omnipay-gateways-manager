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
        return $this->transactions->sum('amount');
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->amount <= $this->paid;
    }

    /**
     * @return int
     */
    public function getUnderpaymentAttribute(): int
    {
        return $this->amount - $this->paid;
    }
}
