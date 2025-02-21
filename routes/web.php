<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScraperController;

//rota que manda o termo via get para a url e lá captura os dados do site
Route::post('/scrape', [ScraperController::class, 'buscaProdutosMercadoLivre'])->name('raspagem.produtos');

//rota que bota os dados do banco na tela 
Route::get('/scrape/results', [ScraperController::class, 'buscaProdutosNoBD'])->name('raspagem.resultados');