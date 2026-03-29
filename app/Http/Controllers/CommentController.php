<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'content' => ['required', 'string', 'min:2', 'max:1000'],
        ], [
            'content.required' => 'El comentario es obligatorio.',
            'content.min' => 'El comentario debe tener al menos 2 caracteres.',
            'content.max' => 'El comentario no puede superar 1000 caracteres.',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'status' => 'pendiente',
        ]);

        return redirect()->route('welcome')
            ->with('success', '¡Gracias por tu comentario! Tu comentario está pendiente de aprobación.');
    }
}
