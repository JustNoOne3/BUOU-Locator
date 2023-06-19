<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sub_storage extends Model
{
    use HasFactory;

    protected $fillable = ['storage_id','substorage_name', 'substorage_description'];

    public function storage()
    {
        return $this->belongsTo(storage::class);
    }

    public function file()
    {
        return $this->hasMany(file::class);
    }
}
