<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Notification;
use App\Models\ServiceWindow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function dashboard()
    {
        $agent   = Auth::user();
        $windows = ServiceWindow::where('agent_id', $agent->id)->with('queue')->get();
        $activeWindow = $windows->where('is_active', true)->first();
        $waiting = $activeWindow
            ? Ticket::where('queue_id', $activeWindow->queue_id)->where('status', 'waiting')->orderBy('position')->with('customer')->get()
            : collect();
        $recentlyCalled = $activeWindow
            ? Ticket::where('window_id', $activeWindow->id)->where('status', 'called')->with('customer')->orderBy('called_at', 'desc')->take(5)->get()
            : collect();
        return view('agent.dashboard', compact('windows', 'activeWindow', 'waiting', 'recentlyCalled'));
    }

    public function callNext(Request $request)
    {
        $request->validate(['window_id' => 'required|exists:service_windows,id']);
        $window = ServiceWindow::findOrFail($request->window_id);

        if ($window->agent_id !== Auth::id()) abort(403);

        // Mark any currently called ticket as absent if not served
        Ticket::where('window_id', $window->id)->where('status', 'called')->update(['status' => 'absent']);

        $next = Ticket::where('queue_id', $window->queue_id)
            ->where('status', 'waiting')
            ->orderBy('position')
            ->first();

        if (!$next) {
            return back()->with('info', 'No more customers waiting.');
        }

        $next->update([
            'status'    => 'called',
            'window_id' => $window->id,
            'called_at' => now(),
        ]);

        Notification::create([
            'type'      => 'in-app',
            'message'   => "It's your turn! Please proceed to {$window->label}.",
            'user_id'   => $next->customer_id,
            'ticket_id' => $next->id,
            'sent_at'   => now(),
        ]);

        return back()->with('success', "Called ticket #{$next->position} — {$next->customer->name}");
    }

    public function markServed(Request $request)
    {
        $request->validate(['ticket_id' => 'required|exists:tickets,id']);
        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket->update(['status' => 'served', 'served_at' => now()]);

        Notification::create([
            'type'      => 'in-app',
            'message'   => "Your ticket has been marked as served. Thank you!",
            'user_id'   => $ticket->customer_id,
            'ticket_id' => $ticket->id,
            'sent_at'   => now(),
        ]);

        return back()->with('success', 'Customer marked as served.');
    }

    public function markAbsent(Request $request)
    {
        $request->validate(['ticket_id' => 'required|exists:tickets,id']);
        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket->update(['status' => 'absent']);
        return back()->with('warning', 'Customer marked as absent.');
    }
}
