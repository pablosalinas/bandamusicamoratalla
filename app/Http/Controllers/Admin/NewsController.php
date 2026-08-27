<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = \App\Models\NewsActivity::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . time();
        $validated['is_published'] = $request->has('is_published');

        \App\Models\NewsActivity::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Noticia creada exitosamente.');
    }

    public function edit(\App\Models\NewsActivity $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, \App\Models\NewsActivity $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_date' => 'nullable|date',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . time();
        $validated['is_published'] = $request->has('is_published');

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Noticia actualizada exitosamente.');
    }

    public function destroy(\App\Models\NewsActivity $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Noticia eliminada.');
    }
