<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class storage extends Model
{
    use HasFactory;

    protected $fillable = ['storage_name', 'storage_location', 'storage_description'];

    public function sub_storage()
    {
        return $this->hasMany(sub_storage::class);
    }
    public function file()
    {
        return $this->hasMany(file::class);
    }
}
