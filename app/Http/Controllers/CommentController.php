<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
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

        $message = 'Tu comentario está pendiente de aprobación.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'comment' => [
                    'content' => e($request->input('content')),
                    'user_name' => e(Auth::user()->name),
                    'created_at' => 'Ahora',
                ],
            ]);
        }

        return redirect()->route('welcome')
            ->with('success', $message);
    }
}
