<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostUser extends Model
{
    use HasFactory;

    protected $table = 'post_user'; // Nome da tabela de associação

    protected $fillable = [
        'user_id', // Nome da coluna que referencia o ID do usuário
        'post_id', // Nome da coluna que referencia o ID da postagem
    ];
}
