<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('employee.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('employee.tickets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High',
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket-logs', 'public');
        }

        TicketLog::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'note' => 'Ticket created',
            'attachment' => $attachmentPath,
        ]);

        return redirect()->route('employee.tickets.show', $ticket)->with('success', 'Ticket #' . $ticket->ticket_no . ' created.');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) abort(403);
        $ticket->load('logs.user', 'category');
        return view('employee.tickets.show', compact('ticket'));
    }
}