<?php
namespace Jmrz\PokemonApi\Block\Pokemon;

use Jmrz\PokemonApi\Helper\PokemonApiHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;

class PokemonDetails extends \Magento\Framework\View\Element\Template
{
    /** @var PokemonApiHelper */
    protected $pokemonApiHelper;

    /** @var ProductRepositoryInterface */
    protected $productRepository;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        PokemonApiHelper $pokemonApiHelper,
        ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->pokemonApiHelper = $pokemonApiHelper;
        $this->productRepository = $productRepository;

        parent::__construct($context, $data);
    }

    public function getPokemonName($product_id)
    {
        $product = $this->productRepository->getById($product_id);
        $name = $product->getCustomAttribute('pokemon_name')->getValue();
        return $name ? $this->pokemonApiHelper->getPokemonDetails($name) : null;
    }
}
