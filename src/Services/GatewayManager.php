<?php

namespace Lari\Payments\Services;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Collection;
use Lari\Payments\PaymentGateway;
use Omnipay\Common\Helper;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class GatewayManager
{
    const CACHE_KEY = 'lari.payments.available_gateways';
    const TTL = 10800;
    /**
     * @var Repository
     */
    private $cache;

    /**
     * @param Repository $cache
     */
    public function __construct(Repository $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getGatewayNamespaces()
    {
        $path = base_path('vendor/composer/autoload_psr4.php');

        if (!file_exists($path)) {
            return collect();
        }

        return $file = collect(include $path);
    }

    /**
     * @return Collection
     */
    public function getAvailableGateways()
    {
        return $this->cache->remember(static::CACHE_KEY, static::TTL, function (){
            return $this->getGatewayNamespaces()->filter(function ($item, $key) {
                return strpos($key, "Omnipay") !== false && $key !== "Omnipay\Common\\";
            })->mapWithKeys(function ($item, $namespace) {
                $key = str_replace(['Omnipay\\', '\\'], ['', ''], $namespace);

                return [
                    $key => Helper::getGatewayClassName($key),
                ];
            });
        });
    }

    public function getUnregisteredGateways()
    {
        $gateways = PaymentGateway::all()->pluck('key');

        return $this->getAvailableGateways()->filter(function ($item, $key) use($gateways){
            return !$gateways->contains($key);
        });
    }
}
