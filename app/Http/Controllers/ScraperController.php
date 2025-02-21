<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use App\Models\Product;

class ScraperController extends Controller {
public function buscaProdutosMercadoLivre(Request $requisicao)
{
    $consulta = $requisicao->input('query');

    if (empty($consulta)) {
        return redirect('/scrape/form')->with('error', 'Por favor, insira um termo de pesquisa');
    }

    Product::truncate();  // truncate limpa o banco de dados

    $cliente = new Client();
    $url = 'https://lista.mercadolivre.com.br/' . urlencode($consulta);
    $deslocamento = 1;
    $produtosAdicionados = 0;
    $temMaisResultados = true;

    while ($temMaisResultados) {
        if ($deslocamento !== 1) {
            $url .= '_Desde_' . $deslocamento;
        } //reescreve assim pois é o padrão do mercado livre


        $raspador = $cliente->request('GET', $url); //retorna o html do mercado livre

        $jsonLd = $raspador->filter('script[type="application/ld+json"]')->each(function ($node) {
            return json_decode($node->text(), true);
        }); 
        //pega o que tá dentro de script na página do mercado livre do tipo que tá ali e retorna um array

        $produtosAdicionadosNaPagina = 0;

        foreach ($jsonLd as $ldItem) {
            if (isset($ldItem['@graph']) && is_array($ldItem['@graph'])) {
                foreach ($ldItem['@graph'] as $item) {
                    if (isset($item['@type']) && $item['@type'] == 'Product') {
                        $produto = [
                            'nome' => $item['name'] ?? null,
                            'preco' => $item['offers']['price'] ?? null,
                            'marca' => $item['brand']['name'] ?? null,
                            'url' => $item['offers']['url'] ?? null,
                            'img' => $item['image'] ?? null,
                        ];

                        Product::create([
                            'nome' => $produto['nome'],
                            'preco' => $produto['preco'],
                            'marca' => $produto['marca'],
                            'url' => $produto['url'],
                            'img' => $produto['img']
                        ]);

                        $produtosAdicionadosNaPagina++;
                        $produtosAdicionados++;

                        if ($produtosAdicionados >= 6) { //aqui é o valor que interrompe o loop
                            $temMaisResultados = false;
                            break 2;
                        }
                    }
                }
            }
        }

        $deslocamento += 47; //são 48 resultados por página no mercado livre por isso esse valor

        if ($produtosAdicionadosNaPagina === 0) {
            $temMaisResultados = false;
        }
    }

    return redirect()->route('raspagem.resultados', ['query' => $consulta]);
}
    
    public function buscaProdutosNoBD(Request $requisicao)
    {
        $produtos = Product::all();
        return view('scrape.results', ['produtos' => $produtos, 'query' => $requisicao->query('query')]);
    }
}