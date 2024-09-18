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
        'group_id',
        'type',
        'card_id',
        'card_info'
    ];

    protected $casts = [
        'card_info' => 'json',
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
            return $this->card_id . '/' . $this->created_at->year . '/TATLĐ';
        }

        if ($this->type == CertificateConstant::ELECTRICAL_SAFETY) {
            return $this->card_id . '/LĐV/TATĐ';
        }

        return $this->card_id;
    }

    public function getJobAttribute()
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);
        $job = null;
        if (!empty($cardInfo['position'])) {
            $job = $cardInfo['position'];
        }

        if (!empty($cardInfo['department']) && $job) {
            $job = $job . ' - ' . $cardInfo['department'];
        } elseif (!empty($cardInfo['department']) && empty($job)) {
            $job = $cardInfo['department'];
        }

        return $job;
    }

    public function getDobAttribute()
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);

        return !empty($cardInfo['dob']) ? Carbon::parse($cardInfo['dob']) : null;
    }

    public function setDobAttribute($value)
    {
        $cardInfo = json_decode($this->attributes['card_info'], true);
        $cardInfo['dob'] = $value;
        $this->attributes['card_info'] = json_encode($cardInfo);
    }
}
