<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ControlEvidence extends Model
{
    protected $table = 'control_evidence';

    protected $fillable = [
        'control_id',
        'file_name',
        'file_path',
        'original_name',
        'file_type',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function control()
    {
        return $this->belongsTo(Control::class);
    }
}
