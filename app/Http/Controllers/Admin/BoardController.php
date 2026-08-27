<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = \App\Models\Board::orderBy('start_date', 'desc')->paginate(10);
        return view('admin.boards.index', compact('boards'));
    }

    public function create()
    {
        return view('admin.boards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        // If setting this board as active, maybe we want to deactivate others? 
        // For now, just create it.
        if ($validated['is_active']) {
            \App\Models\Board::where('is_active', true)->update(['is_active' => false]);
        }

        \App\Models\Board::create($validated);

        return redirect()->route('admin.boards.index')->with('success', 'Legislatura creada correctamente.');
    }

    public function show(\App\Models\Board $board)
    {
        $board->load('members.user');
        $users = \App\Models\User::where('is_active', true)->orderBy('name')->get(); // For adding new members

        return view('admin.boards.show', compact('board', 'users'));
    }

    public function edit(\App\Models\Board $board)
    {
        return view('admin.boards.edit', compact('board'));
    }

    public function update(Request $request, \App\Models\Board $board)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_active'] && !$board->is_active) {
            \App\Models\Board::where('id', '!=', $board->id)->update(['is_active' => false]);
        }

        $board->update($validated);

        return redirect()->route('admin.boards.index')->with('success', 'Legislatura actualizada correctamente.');
    }

    public function destroy(\App\Models\Board $board)
    {
        $board->delete();
        return redirect()->route('admin.boards.index')->with('success', 'Legislatura eliminada.');
    }

    public function addMember(Request $request, \App\Models\Board $board)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|string|max:255',
        ]);

        $board->members()->create($validated);

        return redirect()->route('admin.boards.show', $board)->with('success', 'Miembro añadido a la junta.');
    }

    public function removeMember(\App\Models\Board $board, \App\Models\BoardMember $member)
    {
        if ($member->board_id === $board->id) {
            $member->delete();
            return redirect()->route('admin.boards.show', $board)->with('success', 'Miembro removido de la junta.');
        }

        return redirect()->route('admin.boards.show', $board)->with('error', 'Error al remover el miembro.');
    }
}
