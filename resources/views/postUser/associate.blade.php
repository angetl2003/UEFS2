<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Postagens do usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="antialiased">
<div class="container">
        <h2 class="text-center mb-4">Associar post a {{ $user->name }}</h2>
    <form method="POST" action="{{ route('postUser.storeAssociate', $user->id) }}" >
        @csrf
     
        <label for="post_id">Selecione a postagem:</label>
        <select name="post_id" id="post_id">
            @foreach ($posts as $post)
                <option value="{{ $post->id }}">{{ $post->title }}</option>
            @endforeach
        </select>

        <br><br>

        <button type="submit">Associar</button>
    </form>
    <a href="{{ route('posts.create') }}" class="btn btn-success mb-3">Nova Postagem</a>
   
</div>
</body>
</html>
