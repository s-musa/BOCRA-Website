<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleDependency extends Model
{
    protected $table = 'module_dependencies';
    protected $guarded = ['id'];
}
