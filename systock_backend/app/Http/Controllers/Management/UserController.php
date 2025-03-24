<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Http\Controllers\Management\Hash;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class UserController extends Controller
{

    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    public function show($id){

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:14',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'tax_id.required' => 'O CPF/CNPJ é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'tax_id' => $request->tax_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'Usuário criado com sucesso!', 'user' => $user], 201);
    }

    public function update(Request $request, $id)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'tax_id' => 'required|string|max:14',
        'email' => 'required|string|email|unique:users,email,' . $id . ',id',
        'password' => 'nullable|string|min:6',
    ], [
        'name.required' => 'O nome é obrigatório.',
        'tax_id.required' => 'O CPF/CNPJ é obrigatório.',
        'email.required' => 'O e-mail é obrigatório.',
        'password.required' => 'A senha é obrigatória.',
    ]);

    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuário não encontrado.'], 404);
    }

    $user->name = $request->name;
    $user->tax_id = $request->tax_id;
    $user->email = $request->email;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json(['message' => 'Usuário atualizado com sucesso!', 'user' => $user], 200);
}


public function destroy($id)
{
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuário não encontrado.'], 404);
    }

    $user->delete();

    return response()->json(['message' => 'Usuário deletado com sucesso!'], 200);
}



}
