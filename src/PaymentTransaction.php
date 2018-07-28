<?php

namespace Lari\Payments;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PaymentTransaction extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data'    => 'collection',
        'is_test' => 'boolean',
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }

    /**
     * Gateway
     * Define a relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    /**
     * @param bool $test
     * @return bool
     */
    public function isTest(bool $test = false)
    {
        return $this->update(['is_test' => $test]);
    }

    /**
     * @return bool
     */
    public function isReal(): bool
    {
        return !$this->is_test;
    }

    /**
     * @param $data
     * @return PaymentTransaction
     */
    public function setDataAttribute($data)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $this->attributes['data'] = $data;

        return $this;
    }

}
