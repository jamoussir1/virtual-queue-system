<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\User;
use App\Models\Ticket;
use App\Models\ServiceWindow;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    // ──────────────────────────────────────────
    // DASHBOARD
    // ──────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_users'    => User::count(),
            'total_queues'   => Queue::count(),
            'waiting_now'    => Ticket::where('status', 'waiting')->count(),
            'served_today'   => Ticket::where('status', 'served')->whereDate('served_at', today())->count(),
        ];
        $queues  = Queue::withCount(['tickets as waiting' => fn($q) => $q->where('status','waiting')])->get();
        $recent  = Ticket::with('customer','queue')->orderBy('created_at','desc')->take(10)->get();
        return view('admin.dashboard', compact('stats', 'queues', 'recent'));
    }

    // ──────────────────────────────────────────
    // QUEUES CRUD
    // ──────────────────────────────────────────
    public function queues()
    {
        $queues = Queue::with('creator')->withCount('tickets')->paginate(15);
        return view('admin.queues.index', compact('queues'));
    }

    public function createQueue()  { return view('admin.queues.create'); }

    public function storeQueue(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'status'       => 'required|in:open,closed,paused',
            'max_capacity' => 'required|integer|min:1|max:500',
        ]);
        Queue::create([...$request->only('name','status','max_capacity'), 'created_by' => auth()->id()]);
        return redirect()->route('admin.queues')->with('success', 'Queue created.');
    }

    public function editQueue(Queue $queue) { return view('admin.queues.edit', compact('queue')); }

    public function updateQueue(Request $request, Queue $queue)
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'status'       => 'required|in:open,closed,paused',
            'max_capacity' => 'required|integer|min:1|max:500',
        ]);
        $queue->update($request->only('name','status','max_capacity'));
        return redirect()->route('admin.queues')->with('success', 'Queue updated.');
    }

    public function destroyQueue(Queue $queue)
    {
        $queue->delete();
        return redirect()->route('admin.queues')->with('success', 'Queue deleted.');
    }

    // ──────────────────────────────────────────
    // SERVICE WINDOWS CRUD
    // ──────────────────────────────────────────
    public function windows()
    {
        $windows = ServiceWindow::with('agent','queue')->paginate(15);
        return view('admin.windows.index', compact('windows'));
    }

    public function createWindow()
    {
        $queues = Queue::all();
        $agents = User::where('role','agent')->get();
        return view('admin.windows.create', compact('queues','agents'));
    }

    public function storeWindow(Request $request)
    {
        $request->validate([
            'label'     => 'required|string|max:50',
            'queue_id'  => 'required|exists:queues,id',
            'agent_id'  => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);
        ServiceWindow::create($request->only('label','queue_id','agent_id') + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.windows')->with('success', 'Window created.');
    }

    public function editWindow(ServiceWindow $window)
    {
        $queues = Queue::all();
        $agents = User::where('role','agent')->get();
        return view('admin.windows.edit', compact('window','queues','agents'));
    }

    public function updateWindow(Request $request, ServiceWindow $window)
    {
        $request->validate([
            'label'     => 'required|string|max:50',
            'queue_id'  => 'required|exists:queues,id',
            'agent_id'  => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);
        $window->update($request->only('label','queue_id','agent_id') + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.windows')->with('success', 'Window updated.');
    }

    public function destroyWindow(ServiceWindow $window)
    {
        $window->delete();
        return redirect()->route('admin.windows')->with('success', 'Window deleted.');
    }

    // ──────────────────────────────────────────
    // USER ACCOUNTS CRUD
    // ──────────────────────────────────────────
    public function users()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser() { return view('admin.users.create'); }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'role'     => 'required|in:customer,agent,admin',
            'password' => ['required', Rules\Password::defaults()],
        ]);
        User::create([...$request->only('name','email','role'), 'password' => Hash::make($request->password)]);
        return redirect()->route('admin.users')->with('success', 'User created.');
    }

    public function editUser(User $user) { return view('admin.users.edit', compact('user')); }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role'  => 'required|in:customer,agent,admin',
        ]);
        $user->update($request->only('name','email','role'));
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) return back()->with('error', 'Cannot delete yourself.');
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted.');
    }

    // ──────────────────────────────────────────
    // STATISTICS
    // ──────────────────────────────────────────
    public function statistics()
    {
        $queues     = Queue::all();
        $dailyStats = Ticket::selectRaw('DATE(created_at) as day, COUNT(*) as total, SUM(status="served") as served')
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->take(14)
            ->get();
        $byQueue = Queue::withCount([
            'tickets as total_tickets',
            'tickets as served_tickets' => fn($q) => $q->where('status','served'),
            'tickets as waiting_tickets' => fn($q) => $q->where('status','waiting'),
        ])->get();
        return view('admin.statistics', compact('queues','dailyStats','byQueue'));
    }
}
