<?php

// app/Services/ScraperService.php

namespace App\Services;

use App\Models\Product;
use Goutte\Client;

class ScraperService
{
    public function scrapeProducts($query)
    {
        $client = new Client();
        $url = "https://www.mercadolivre.com.br/search?q=" . urlencode($query);
        
        // Fazendo o scraping
        $crawler = $client->request('GET', $url);

        // Coleta dos produtos
        $products = [];
        
        $crawler->filter('.ui-search-result')->each(function ($node) use (&$products) {
            $name = $node->filter('.ui-search-item__title')->text();
            $image = $node->filter('.ui-search-result-image__element')->attr('src');
            $price = $node->filter('.price-tag-fraction')->text();
            $brand = $node->filter('.ui-search-item__brand')->text();
            $url = $node->filter('.ui-search-link')->attr('href');

            // Salvando no banco de dados
            $product = Product::create([
                'name' => $name,
                'image' => $image,
                'brand' => $brand,
                'price' => (float) $price,
                'priceCurrency' => 'BRL',
                'url' => $url,
            ]);

            // Adicionando ao array para exibir
            $products[] = $product;
        });

        return $products;
    }
}