<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->conversations()->with('users', 'messages.user')->latest()->get();
        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation); // Policy kontrolü
        $conversation->load('users', 'messages.user');
        return view('messages.show', compact('conversation'));
    }

    public function create()
    {
        // Yeni mesaj formunda alıcı seçimi için kullanıcı listesi
        $users = User::where('id', '!=', Auth::id())->get();
        return view('messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {
            $conversation = Conversation::create(['subject' => $validated['subject']]);

            // Konuşmaya göndereni ve alıcıları ekle
            $participants = array_merge($validated['recipients'], [Auth::id()]);
            $conversation->users()->attach($participants);

            // İlk mesajı oluştur
            Message::create([
                'conversation_id' => $conversation->id,
                'user_id' => Auth::id(),
                'body' => $validated['body'],
            ]);
        });

        return redirect()->route('messages.index')->with('success', 'Mesaj gönderildi.');
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $this->authorize('reply', $conversation); // Policy kontrolü
        $validated = $request->validate(['body' => 'required|string']);

        Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        return redirect()->route('messages.show', $conversation)->with('success', 'Cevabınız gönderildi.');
    }
}
