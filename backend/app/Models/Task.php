<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'status', 'priority', 'order', 'due_date', 'user_id'
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function scopeStatus(Builder $query, $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopePriority(Builder $query, $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
