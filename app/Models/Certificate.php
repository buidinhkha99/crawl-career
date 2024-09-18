<?php

namespace App\Models;

use App\Enums\CertificateConstant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'released_at',
        'type',
        'card_id',
        'card_info'
    ];

    protected $casts = [
        'card_info' => 'json',
        'released_at' => 'date'
    ];

    protected $appends = ['certificate_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(UserGroup::class, 'group_id');
    }

    public function getCertificateIDAttribute()
    {
        if ($this->type == CertificateConstant::OCCUPATIONAL_SAFETY) {
            return $this->card_id . '/' . $this->released_at->year . '/TATLĐ';
        }

        if ($this->type == CertificateConstant::ELECTRICAL_SAFETY) {
            return $this->card_id . '/LĐV/TATĐ';
        }

        return $this->card_id;
    }

    public function getJobAttribute()
    {
        $job = null;
        if (!empty($this->user->position)) {
            $job = $this->user->position;
        }

        if (!empty($this->user->department) && $job) {
            $job = $job . ' - ' . $this->user->department;
        } elseif (!empty($this->user->department) && empty($job)) {
            $job = $this->user->department;
        }

        return $job;
    }

    public function getCompleteFromAttribute()
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);

        return !empty($cardInfo['complete_from']) ? Carbon::parse($cardInfo['complete_from']) : null;
    }

    public function setCompleteFromAttribute($value)
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);
        $cardInfo['complete_from'] = $value;
        $this->attributes['card_info'] = json_encode($cardInfo);
    }

    public function getCompleteToAttribute()
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);

        return !empty($cardInfo['complete_to']) ? Carbon::parse($cardInfo['complete_to']) : null;
    }

    public function setCompleteToAtAttribute($value)
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);
        $cardInfo['complete_to'] = $value ? Carbon::parse($cardInfo['complete_to'])->format('Y-m-d') : null;
        $this->attributes['card_info'] = json_encode($cardInfo);
    }

    public function getEffectiveToAttribute()
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);

        return !empty($cardInfo['effective_to']) ? Carbon::parse($cardInfo['effective_to']) : null;
    }

    public function setEffectiveToAttribute($value)
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);
        $cardInfo['effective_to'] = $value ? Carbon::parse($cardInfo['effective_to'])->format('Y-m-d') : null;
        $this->attributes['card_info'] = json_encode($cardInfo);
    }
}
