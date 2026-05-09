<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_no', 'user_id', 'category_id', 'subject', 'description', 'status', 'priority'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($ticket) {
            $ticket->ticket_no = static::generateTicketNumber();
        });
    }

    public static function generateTicketNumber()
    {
        $prefix = 'TCK-' . date('Ymd') . '-';
        $lastTicket = static::where('ticket_no', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        $number = $lastTicket ? (int) Str::after($lastTicket->ticket_no, $prefix) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class)->orderBy('created_at', 'desc');
    }

    public function updateStatus($newStatus, $userId, $note = null, $attachment = null)
    {
        if ($this->status === $newStatus) return false;
        $oldStatus = $this->status;
        $this->status = $newStatus;
        $this->save();

        TicketLog::create([
            'ticket_id' => $this->id,
            'user_id' => $userId,
            'action' => 'status_updated',
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note' => $note,
            'attachment' => $attachment,
        ]);

        return true;
    }

    public function addNote($note, $userId, $attachment = null)
    {
        TicketLog::create([
            'ticket_id' => $this->id,
            'user_id' => $userId,
            'action' => 'note_added',
            'note' => $note,
            'attachment' => $attachment,
        ]);
        return true;
    }
}