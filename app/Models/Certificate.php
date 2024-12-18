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
        'card_info',
        'image_font',
        'image_font_url',
        'image_back',
        'image_back_url',
        'place_printed',
        'complete_from_printed',
        'complete_to_printed',
        'director_name_printed',
        'signature_photo_printed',
        'effective_to_printed',
        'work_unit_printed'
    ];

    protected $casts = [
        'card_info' => 'json',
        'released_at' => 'date',
        'complete_from_printed' => 'date',
        'complete_to_printed' => 'date',
        'effective_to_printed' => 'date',
    ];

    protected $appends = ['certificate_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCertificateIDAttribute()
    {
        if ($this->type == CertificateConstant::OCCUPATIONAL_SAFETY) {
            return $this->card_id . '/' . $this->released_at?->year . '/TATLĐ';
        }

        if ($this->type == CertificateConstant::ELECTRICAL_SAFETY) {
            return $this->card_id . '/LĐV/TATĐ';
        }
        if ($this->type == CertificateConstant::PAPER_SAFETY) {
            // add 0 with number < 10
            return str_pad($this->card_id, 2, '0', STR_PAD_LEFT) . '/GCN-ATLĐ';
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

    // Helper method to decode card_info
    public function getCardInfoAttribute()
    {
        return json_decode($this->attributes['card_info'] ?? null, true) ?? [];
    }

    // Helper method to set card_info
    public function setCardInfo($key, $value)
    {
        $cardInfo = $this->getCardInfoAttribute();
        $cardInfo[$key] = $value;
        $this->attributes['card_info'] = json_encode($cardInfo);
    }

    // Helper method to parse dates
    public function getDateAttribute($key)
    {
        $cardInfo = $this->getCardInfoAttribute();
        return !empty($cardInfo[$key]) ? Carbon::parse($cardInfo[$key]) : null;
    }

    // Helper method to format dates for setting
    public function setDateAttribute($key, $value)
    {
        $this->setCardInfo($key, $value ? Carbon::parse($value)->format('Y-m-d') : null);
    }

    public function getCompleteFromAttribute()
    {
        return $this->getDateAttribute('complete_from');
    }

    public function setCompleteFromAttribute($value)
    {
        $this->setDateAttribute('complete_from', $value);
    }

    public function getCompleteToAttribute()
    {
        return $this->getDateAttribute('complete_to');
    }

    public function setCompleteToAttribute($value)
    {
        $this->setDateAttribute('complete_to', $value);
    }

    public function getEffectiveToAttribute()
    {
        return $this->getDateAttribute('effective_to');
    }

    public function setEffectiveToAttribute($value)
    {
        $this->setDateAttribute('effective_to', $value);
    }

    public function getDobAttribute()
    {
        return $this->getDateAttribute('dob');
    }

    public function setDobAttribute($value)
    {
        $this->setDateAttribute('dob', $value);
    }

    public function getEffectiveFromAttribute()
    {
        return $this->getDateAttribute('effective_from');
    }

    public function setEffectiveFromAttribute($value)
    {
        $this->setDateAttribute('effective_from', $value);
    }

    public function getLevelAttribute()
    {
        return $this->getCardInfoAttribute()['level'] ?? null;
    }

    public function setLevelAttribute($value)
    {
        $this->setCardInfo('level', $value);
    }

    public function getGenderAttribute()
    {
        return $this->getCardInfoAttribute()['gender'] ?? null;
    }

    public function setGenderAttribute($value)
    {
        $this->setCardInfo('gender', $value);
    }

    public function getNationalityAttribute()
    {
        return $this->getCardInfoAttribute()['nationality'] ?? null;
    }

    public function setNationalityAttribute($value)
    {
        $this->setCardInfo('nationality', $value);
    }

    public function getCccdAttribute()
    {
        return $this->getCardInfoAttribute()['cccd'] ?? null;
    }

    public function setCccdAttribute($value)
    {
        $this->setCardInfo('cccd', $value);
    }

    public function getGroupAttribute()
    {
        return $this->getCardInfoAttribute()['group'] ?? null;
    }

    public function setGroupAttribute($value)
    {
        $this->setCardInfo('group', $value);
    }

    public function getResultAttribute()
    {
        return $this->getCardInfoAttribute()['result'] ?? null;
    }

    public function setResultAttribute($value)
    {
        $this->setCardInfo('result', $value);
    }

    public function getDescriptionElectricCertificateAttribute()
    {
        $user = $this->user;

        return $user->department . ". $user->factory_name - $user->position";
    }

    public function getTrainingContentPaperCertificateAttribute()
    {
        $objectGroup = ObjectGroup::where('name', $this->card_info['group'] ?? null)->first();
        if ($objectGroup) {
            return $objectGroup->description;
        }

        // get description in same name group user group
        return UserGroup::where('name', $this->card_info['group'] ?? null)->first()?->description;
    }
}
