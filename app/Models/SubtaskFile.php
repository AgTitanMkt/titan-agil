<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtaskFile extends Model
{
    protected $fillable = [
        'subtask_id',
        'file_type',
        'file_url',
        'uploaded_by',
    ];

    const FILE_TYPE = [
        'DOCUMENT' => 'document',
        'IMAGE' => 'image',
        'OTHER' => 'other',
        'URL' => 'url',
    ];

    public function subtask()
    {
        return $this->belongsTo(SubTask::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
