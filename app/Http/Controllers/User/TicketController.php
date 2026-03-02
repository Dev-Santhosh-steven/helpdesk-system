<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Department;
use App\Models\TicketComment;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = TicketCategory::all();
        $departments = Department::all();
        
        return view('tickets.create', compact('categories','departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required',
            'priority' => 'required',
            'department_id'=>'required|exists:departments,id',
            'ticket_category_id' => 'required|exists:ticket_categories,id'
        ]);

        Ticket::create([
            'ticket_category_id' => $request->ticket_category_id,
            'department_id' => $request->department_id,
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'description' => $request->description,
            'ticket_number' => 'TCK-' . strtoupper(Str::random(8)),
            'status' =>'open',
        
        ]);

        return redirect()->route('tickets.index')->with('success', 'Ticket Created Successfully');
    }

    public function categoryIndex()
    {
        $categories = TicketCategory::latest()->get();
        return view('tickets.categories', compact('categories'));
    }

    public function categoryCreate()
    {
        return view('tickets.create_category');
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'category_description' => 'nullable|string',
        ]);

        TicketCategory::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);

        return redirect()->route('tickets.create_category')->with('success', 'Category Created Successfully');
    }

    public function show($id)
    {
        $ticket = Ticket::with([
            'department',
            'category',
            'comments.user'
        ])->findOrFail($id);

        return view('tickets.show', compact('ticket'));
    }

    public function departmentCreate()
    {
        return view('tickets.create_department');
    }

    public function departmentStore(Request $request)
    {
        $request->validate([
            'name'=> 'required',
            'description'=> 'required|nullable',

            ]);

        $department = Department::create([
            'name'=> $request->name,
            'description'=> $request->description,
            'is_active'=> 1, 
            ]);
                return redirect()->route('tickets.departments')->with('success', 'Department created successfully');
    }

    public function status(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,reopened,close'
        ]);

        $ticket = Ticket::findOrFail($id);


        if ($request->status === 'resolved') {

            if (!in_array(strtolower($user->role), ['admin','staff'])) {
                abort(403);
            }

            if (!in_array($ticket->status, ['in_progress','reopened'])) {
                return back()->with('error', 'Ticket cannot be resolved.');
            }
        }


        if ($request->status === 'close') {

            if ($user->role !== 'user' || $ticket->user_id !== $user->id) {
                abort(403);
            }

            if ($ticket->status !== 'resolved') {
                return back()->with('error', 'Ticket must be resolved before closing.');
            }

            if (empty($ticket->closure_requested_at)) {
                return back()->with('error', 'Closure request not yet sent by support.');
            }

            $ticket->closed_by = $user->id;
            $ticket->closed_at = now();
        }


        if ($request->status === 'reopened') {

            
            if ($ticket->status !== 'close') {
                return back()->with('error', 'Only closed tickets can be reopened.');
            }

            if ($ticket->reopen_count >= 1) {
                return back()->with('error', 'This ticket has already been reopened once.');
            }

    
            if ($user->role === 'user' && $ticket->user_id !== $user->id) {
                abort(403);
            }

            $ticket->reopen_count += 1;

            // Reset closure fields
            $ticket->closure_requested_at = null;
            $ticket->closed_by = null;
            $ticket->closed_at = null;

            // Add system comment
            $ticket->comments()->create([
                'user_id' => $user->id,
                'comment' => 'Ticket reopened by ' . ucfirst($user->role) . '.'
            ]);
        }


        $ticket->status = $request->status;
        $ticket->save();

        return back()->with('success', 'Ticket status updated successfully.');
    }


    public function sendClosureRequest($id)
{
    $ticket = Ticket::findOrFail($id);
    $user = auth()->user();

    if (!in_array(strtolower($user->role), ['admin','staff'])) {
        abort(403);
    }

    if ($ticket->status !== 'resolved') {
        return back()->with('error', 'Ticket must be resolved before sending closure request.');
    }

    if ($ticket->closure_requested_at) {
        return back()->with('error', 'Closure request already sent.');
    }

    $ticket->closure_requested_at = now();
    $ticket->save();

    $ticket->comments()->create([
        'user_id' => $user->id,
        'comment' => 'This issue has been resolved. Please confirm and close the ticket if everything is working correctly.'
    ]);

    return back()->with('success', 'Closure request sent to user.');
}


    public function reopen($id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = auth()->user();
        $role = strtolower($user->role);

        if ($role === 'user') {

            if ($ticket->user_id != $user->id) {
                abort(403);
            }

            if ($ticket->status !== 'resolved') {
                return back()->with('error', 'You can only reopen resolved tickets.');
            }
        }

        if (in_array($role, ['admin','staff'])) {

            if (!in_array($ticket->status, ['resolved','close'])) {
                return back()->with('error', 'This ticket cannot be reopened.');
            }
        }

        $ticket->status = 'reopened';

        $ticket->closure_requested_at = null;
        $ticket->closed_by = null;
        $ticket->closed_at = null;

        $ticket->save();

        $ticket->comments()->create([
            'user_id' => $user->id,
            'comment' => 'Ticket reopened by ' . ucfirst($role) . '.'
        ]);

        return back()->with('success', 'Ticket reopened successfully.');
    }

    public function timeline($id)
    {
        $ticket = Ticket::with([
            'department',
            'category',
            'comments.user'
        ])->findOrFail($id);

        return view('tickets.timeline', compact('ticket'));
    }


}
