<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;





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
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'tax_id' => 'required|string|unique:users,tax_id|max:14',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required' => 'O nome é obrigatório.',
            'tax_id.required' => 'O CPF/CNPJ é obrigatório.',
            'tax_id.unique' => 'O CPF/CNPJ já está em uso.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'O e-mail já está em uso.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }
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
            'current_password' => 'required|string',
            'name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:14',
            'email' => 'required|string|email|unique:users,email,' . $id . ',id',
        ]);

        $user = User::findOrFail($id);
                if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Senha atual incorreta.'],
            ]);
        }

        $user->update($request->only(['name', 'tax_id', 'email']));

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
