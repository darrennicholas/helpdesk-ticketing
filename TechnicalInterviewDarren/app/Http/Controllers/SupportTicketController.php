<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'category']);

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('priority')) $query->where('priority', $request->priority);

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();
        $statuses = ['Open', 'On Progress', 'Resolved', 'Closed'];
        $priorities = ['Low', 'Medium', 'High'];

        return view('support.tickets.index', compact('tickets', 'categories', 'statuses', 'priorities', 'request'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('user', 'category', 'logs.user');
        $nextStatus = $this->getNextStatus($ticket->status);
        return view('support.tickets.show', compact('ticket', 'nextStatus'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Open,On Progress,Resolved,Closed',
            'note' => 'nullable|string',
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket-logs', 'public');
        }

        $oldStatus = $ticket->status;
        $success = $ticket->updateStatus($request->status, Auth::id(), $request->note, $attachmentPath);
        if (!$success) return back()->with('error', 'Status already ' . $request->status);

        return redirect()->route('support.tickets.show', $ticket)->with('success', "Status changed from {$oldStatus} to {$request->status}");
    }

    public function addNote(Request $request, Ticket $ticket)
    {
        $request->validate([
            'note' => 'required|string',
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ticket-logs', 'public');
        }

        $ticket->addNote($request->note, Auth::id(), $attachmentPath);
        return redirect()->route('support.tickets.show', $ticket)->with('success', 'Note added');
    }

    private function getNextStatus($current)
    {
        $flow = ['Open' => 'On Progress', 'On Progress' => 'Resolved', 'Resolved' => 'Closed', 'Closed' => null];
        return $flow[$current] ?? null;
    }
}