<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable =[
        'ticket_number',
        'user_id',
        'department_id',
        'ticket_category_id',
        'subject',
        'description',
        'priority',
    ];

    protected $casts = [
    'closed_at' => 'datetime',
    'closure_requested_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
];

public function customer()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'agent_id');
    }

    public function category()
    {
        return $this->belongsTo(TicketCategory::class,'ticket_category_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class)->latest();
    }

    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }
    
}
