<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Board;
use App\Models\BoardMinute;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class BoardMinuteController extends Controller
{
    public function index(Board $board)
    {
        $minutes = $board->minutes()->orderBy('date', 'desc')->paginate(15);
        return view('admin.board_minutes.index', compact('board', 'minutes'));
    }

    public function create(Board $board)
    {
        return view('admin.board_minutes.create', compact('board'));
    }

    public function store(Request $request, Board $board)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'signed_pdf' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        $minute = new BoardMinute([
            'date' => $request->date,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('signed_pdf')) {
            $minute->signed_pdf_path = $request->file('signed_pdf')->store('actas', 'public');
        }

        $board->minutes()->save($minute);

        return redirect()->route('admin.boards.minutes.index', $board)->with('success', 'Acta creada correctamente.');
    }

    public function edit(Board $board, BoardMinute $minute)
    {
        return view('admin.board_minutes.edit', compact('board', 'minute'));
    }

    public function update(Request $request, Board $board, BoardMinute $minute)
    {
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'signed_pdf' => 'nullable|file|mimes:pdf|max:10240'
        ]);

        $minute->fill([
            'date' => $request->date,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('signed_pdf')) {
            if ($minute->signed_pdf_path) {
                Storage::disk('public')->delete($minute->signed_pdf_path);
            }
            $minute->signed_pdf_path = $request->file('signed_pdf')->store('actas', 'public');
        }

        $minute->save();

        return redirect()->route('admin.boards.minutes.index', $board)->with('success', 'Acta actualizada correctamente.');
    }

    public function destroy(Board $board, BoardMinute $minute)
    {
        if ($minute->signed_pdf_path) {
            Storage::disk('public')->delete($minute->signed_pdf_path);
        }
        $minute->delete();

        return redirect()->route('admin.boards.minutes.index', $board)->with('success', 'Acta eliminada.');
    }

    public function downloadPdf(Board $board, BoardMinute $minute)
    {
        return view('admin.board_minutes.pdf', compact('board', 'minute'));
    }
}
