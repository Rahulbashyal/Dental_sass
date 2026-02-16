<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagingFile extends Model
{
    protected $fillable = [
        'imaging_study_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'notes'
    ];

    public function study()
    {
        return $this->belongsTo(ImagingStudy::class, 'imaging_study_id');
    }

    public function getFileSizeHuman(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }
}
