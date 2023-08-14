<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostUser;
use Illuminate\Support\Facades\Log; 
use App\Models\Post;
use App\Models\User;

class PostUserController extends Controller
{
   

    public function showPosts($userId)
    {
        $user = User::findOrFail($userId);
        $posts = $user->posts;

         // Obter os IDs da tabela post_user associados a este usuário
        $postUserIds = PostUser::where('user_id', $userId)->pluck('id');

        return view('postUser.ListaPosts', compact('user', 'posts', 'postUserIds'));
    }

    public function associate(User $user)
    {
        // Obtém todos os posts que NÃO estão associados ao usuário
        $posts = Post::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return view('postUser.associate', ['user' => $user, 'posts' => $posts]);
    }

    public function storeAssociate(User $user, Request $request)
    {
        $data = $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        // Salvar a associação na tabela intermediária (assumindo que você está usando relacionamento many-to-many)
        $user->posts()->attach($data['post_id']);

        return redirect()->route('postUser.listaPosts', $user->id)
                        ->with('success', 'Associação salva com sucesso!');
    }

    public function destroy(PostUser $postUser)
    {
        try {
            $userId = $postUser;
            // Exclua a associação do banco de dados
            $postUser->delete();

            // Redirecione para a lista de posts do usuário com mensagem de sucesso
            return redirect()->route('postUser.listaPosts', ['id' => $userId])
                ->with('success', 'Associação excluída com sucesso!');
        } catch (\Exception $e) {
            // Capturar e exibir exceções
            Log::error('Erro ao excluir associação para o usuário com ID: ' . $userId . ' - ' . $e->getMessage());
            return redirect()->route('postUser.listaPosts', ['id' => $userId])
                ->with('error', 'Houve um erro ao excluir a associação. Por favor, tente novamente.');
        }
    }

    public function destroyAssociation($userId, $postId)
    {
        try {
            // Encontre o registro na tabela post_user para o usuário e o post especificados
            $postUser = PostUser::where('user_id', $userId)->where('post_id', $postId)->firstOrFail();
            
            // Exclua a associação do banco de dados
            $postUser->delete();

            return redirect()->route('postUser.listaPosts', $userId)
                ->with('success', 'Associação removida com sucesso!');
        } catch (\Exception $e) {
            // Capturar e exibir exceções
            Log::error('Erro ao remover associação: ' . $e->getMessage());
            return redirect()->route('postUser.listaPosts', $userId)
                ->with('error', 'Houve um erro ao remover a associação. Por favor, tente novamente.');
        }
    }

}
