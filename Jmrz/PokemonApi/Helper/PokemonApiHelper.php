<?php

namespace Jmrz\PokemonApi\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\CacheInterface;

class PokemonApiHelper extends AbstractHelper
{

    private $curl;
    private $cache;

    public function __construct(Context $context, Curl $curl, CacheInterface $cache)
    {
        $this->curl = $curl;
        $this->cache = $cache;
        parent::__construct($context);
    }
    private function getApiUrlFromConfig()
    {
        return $this->scopeConfig->getValue('pokeapi/general/base_url', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getPokemonDetails($pokemonName)
    {
        $cacheKey = 'pokemon_data_' . strtolower($pokemonName);
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            return json_decode($cachedData, true);
        }

        $url = $this->getApiUrlFromConfig() . '/pokemon/' . strtolower($pokemonName);
        $this->curl->get($url);

        if ($this->curl->getStatus() === 200) {
            $pokemonDetails = json_decode($this->curl->getBody(), true);
            $this->cache->save(json_encode($pokemonDetails), $cacheKey, []);
            return $pokemonDetails;
        }

        return null;
    }


    public function clearPokemonCache($pokemonName)
    {
        $cacheKey = 'pokemon_data_' . strtolower($pokemonName);
        $this->cache->remove($cacheKey);
    }
}
