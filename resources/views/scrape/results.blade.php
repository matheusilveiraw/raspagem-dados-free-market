<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Pesquisa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-header {
            background-color: #dc3545; 
            color: white;
        }
        .btn-primary {
            background-color: #dc3545; 
            border-color: #dc3545;
        }
        .btn-primary:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .image-container {
            height: 400px;
        }
        .image-container img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="card mb-4">
            <div class="card-header">
                <h4>Busca de Produtos</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('raspagem.produtos') }}" method="POST" class="d-flex">
                    @csrf
                    <div class="mb-3 me-2 flex-grow-1">
                        <input type="text" class="form-control h-100" id="query" name="query" placeholder="Busque um produto no mercado livre" value="{{ old('query') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary h-100">Buscar</button>
                </form>
            </div>
        </div>

        @if(count($produtos) > 0)
        <h2 class="text-center my-4">Resultados para: {{ request('query') }}</h2>
        <div class="row">
            @foreach($produtos as $produto)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="image-container">
                        <img src="{{ $produto->img }}" class="img-fluid" alt="{{ $produto->nome }}">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ Str::limit($produto->nome, 75) }}</h5>
                        <p class="card-text mb-4">
                            Preço: R$ {{ number_format($produto->preco, 2, ',', '.') }}
                        </p>
                        <a href="{{ $produto->url }}" target="_blank" class="btn btn-primary mt-auto">Ver produto</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-center">Nenhum produto encontrado.</p>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
