<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Effettua il login dell'utente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Valida la richiesta
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Se la validazione fallisce
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Trova l'utente con l'email
        $user = User::where('email', $request->email)->first();

        // Verifica se l'utente esiste e se la password è corretta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenziali non valide'], 401);
        }

        // Genera un token di accesso per l'utente
        $token = $user->createToken('LaravelSanctum')->plainTextToken;

        // Ritorna il token
        return response()->json([
            'message' => 'Autenticazione riuscita',
            'token' => $token,
        ]);
    }

    /**
     * Effettua il logout dell'utente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // Revoca tutti i token dell'utente
        $request->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logout effettuato con successo']);
    }
}
