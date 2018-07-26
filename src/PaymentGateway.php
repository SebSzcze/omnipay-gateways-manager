<?php

namespace Lari\Payments;

use Illuminate\Database\Eloquent\Model;
/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PaymentGateway extends Model
{
    protected $guarded = [];

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

}
