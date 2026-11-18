<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'space_id',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $appends = ['duration_hours', 'status_label'];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    // Reservas pending/confirmed que se cruzan con el rango dado
    public function scopeOverlapping(Builder $query, $start, $end): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed'])
            ->where('start_time', '<', $end)
            ->where('end_time', '>', $start);
    }

    // Accessor: duración en horas
    public function getDurationHoursAttribute(): float
    {
        return $this->start_time->diffInMinutes($this->end_time) / 60;
    }

    // Accessor: estado legible en español
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
            default => $this->status,
        };
    }
}
