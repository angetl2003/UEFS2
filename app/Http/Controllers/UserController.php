<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log; 


class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        try {
            // Valide os dados do usuário
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
            ]);

            // Crie um novo usuário
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            // Redirecionar para tela de usuários
            return redirect()->route('users.show', $user->id)->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            // Capturar e exibir exceções
            Log::error('Erro ao criar usuário: ' . $e->getMessage());
            return redirect()->route('users.create')->with('user', 'Houve um erro ao criar o usuário. Por favor, verifique os dados.');
            
        }
    }


    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }
  
   
  
    public function update(Request $request, User $user)
    {
        try {
            // Valide os dados do usuário
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id, // Ignorar o email atual na verificação única
                'password' => 'nullable|string|min:8', // Senha é opcional, pode ser nula
            ]);

            // Atualize os campos do usuário com os dados validados
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            // Verifique se a senha foi fornecida e a atualize (caso não esteja vazia)
            if ($validatedData['password']) {
                $user->password = bcrypt($validatedData['password']);
            }

            // Salve as alterações no banco de dados
            $user->save();

            // Redirecione para a tela de visualização do usuário com mensagem de sucesso
            return redirect()->route('users.show', $user->id)->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            // Capturar e exibir exceções
            Log::error('Erro ao atualizar usuário: ' . $e->getMessage());
            return redirect()->route('users.edit', $user->id)->with('user', 'Houve um erro ao atualizar o usuário. Por favor, verifique os dados.');
        }
    }


    public function destroy(User $user)
    {
        try {
            // Exclua o usuário do banco de dados
            $user->delete();
    
            // Redirecione para a lista de usuários com mensagem de sucesso
            return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
        } catch (\Exception $e) {
            // Capturar e exibir exceções
            Log::error('Erro ao excluir usuário: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Houve um erro ao excluir o usuário. Por favor, tente novamente.');
        }
    }

  
    
}