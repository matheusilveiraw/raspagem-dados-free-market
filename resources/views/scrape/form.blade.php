<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisar Produtos</title>
</head>
<body>
    <h1>Pesquisar Produtos</h1>

    <form action="{{ route('raspagem.produtos') }}" method="get">
        <label for="query">Digite o termo de pesquisa: </label>
        <input type="text" id="query" name="query" placeholder="Digite o termo" required>
        <button type="submit">Buscar</button>
    </form>
</body>
</html>
