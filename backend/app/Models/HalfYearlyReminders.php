<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Notifications\Notifiable;
use App\Traits\Uuids;

class HalfYearlyReminders extends Model
{
    use HasFactory;
    use Notifiable, Uuids;
    protected $primaryKey = "id";
    protected  $table = 'halfYearlyReminders';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'reminderId', 'day', 'month','quarter'
    ];

    public function reminders()
    {
        return $this->belongsTo(Reminders::class, 'reminderId', 'id');
    }

    // @codeCoverageIgnoreStart
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }// @codeCoverageIgnoreEnd
}
