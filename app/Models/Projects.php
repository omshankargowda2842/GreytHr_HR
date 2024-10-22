<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    use HasFactory;
    protected $table = "Company_projects";
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_id',
        'project_name',
        'client_name',
        'start_date',
        'end_date',
        'project_status',
    ];
}