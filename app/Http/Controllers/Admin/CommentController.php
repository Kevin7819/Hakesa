<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $pending = Comment::with('user')->pending()->latest()->paginate(10, ['*'], 'pending_page');
        $approved = Comment::with('user')->approved()->latest()->paginate(10, ['*'], 'approved_page');
        $rejected = Comment::with('user')->rejected()->latest()->paginate(10, ['*'], 'rejected_page');

        return view('admin.comments.index', compact('pending', 'approved', 'rejected'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'aprobado']);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comentario aprobado exitosamente.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->update(['status' => 'rechazado']);

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comentario rechazado.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()->route('admin.comments.index')
            ->with('success', 'Comentario eliminado.');
    }
}
