<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user    = Auth::user();
        $queues  = Queue::where('status', 'open')->withCount(['tickets as waiting_count' => fn($q) => $q->where('status', 'waiting')])->get();
        $myTickets = Ticket::where('customer_id', $user->id)
            ->whereIn('status', ['waiting', 'called'])
            ->with('queue')
            ->orderBy('created_at', 'desc')
            ->get();
        $unread = $user->unreadNotifications()->count();
        return view('customer.dashboard', compact('queues', 'myTickets', 'unread'));
    }

    public function joinQueue(Request $request)
    {
        $request->validate(['queue_id' => 'required|exists:queues,id']);

        $queue = Queue::findOrFail($request->queue_id);

        if ($queue->status !== 'open') {
            return back()->with('error', 'This queue is not open.');
        }

        $existing = Ticket::where('customer_id', Auth::id())
            ->where('queue_id', $queue->id)
            ->whereIn('status', ['waiting', 'called'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active ticket for this queue.');
        }

        $waiting = $queue->tickets()->where('status', 'waiting')->count();
        if ($waiting >= $queue->max_capacity) {
            return back()->with('error', 'Queue is full. Please try again later.');
        }

        $ticket = Ticket::create([
            'qr_code'     => Ticket::generateQrCode(),
            'position'    => $queue->nextPosition(),
            'status'      => 'waiting',
            'customer_id' => Auth::id(),
            'queue_id'    => $queue->id,
        ]);

        Notification::create([
            'type'      => 'in-app',
            'message'   => "You joined queue \"{$queue->name}\". Your position: #{$ticket->position}. Est. wait: {$ticket->estimatedWait()} min.",
            'user_id'   => Auth::id(),
            'ticket_id' => $ticket->id,
            'sent_at'   => now(),
        ]);

        return redirect()->route('customer.ticket', $ticket->id)->with('success', 'You joined the queue successfully!');
    }

    public function showTicket(Ticket $ticket)
    {
        if ($ticket->customer_id !== Auth::id()) abort(403);
        $ticket->load('queue', 'window.agent');
        return view('customer.ticket', compact('ticket'));
    }

    public function cancelTicket(Ticket $ticket)
    {
        if ($ticket->customer_id !== Auth::id()) abort(403);
        if (!in_array($ticket->status, ['waiting', 'called'])) {
            return back()->with('error', 'Ticket cannot be cancelled.');
        }
        $ticket->update(['status' => 'cancelled']);
        return redirect()->route('customer.dashboard')->with('success', 'Ticket cancelled.');
    }

    public function notifications()
    {
        $notifs = Notification::where('user_id', Auth::id())->orderBy('sent_at', 'desc')->paginate(15);
        Notification::where('user_id', Auth::id())->where('is_read', false)->update(['is_read' => true]);
        return view('customer.notifications', compact('notifs'));
    }

    public function history()
    {
        $tickets = Ticket::where('customer_id', Auth::id())
            ->with('queue')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('customer.history', compact('tickets'));
    }
}
