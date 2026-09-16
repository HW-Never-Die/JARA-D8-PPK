<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'name', 'description'])]
class TaskList extends Model
{
    use HasFactory;

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_list_members')->withTimestamps();
    }

    public function progress(): array
    {
        $total = $this->tasks()->count();
        $done = $this->tasks()->where('status', 'done')->count();

        return [
            'total' => $total,
            'done' => $done,
            'pending' => $total - $done,
            'percentage' => $total > 0 ? round(($done / $total) * 100) : 0,
        ];
    }
}
