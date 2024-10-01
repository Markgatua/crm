<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStageInformation extends Model
{
    use HasFactory;
    
    protected $casts = [
         "meta" => "json",
     ];

    protected $fillable = [
        'account_id','project_stage_id','due_date','stage_information'
    ];

    public function projectStage()
    {
        return $this->belongsTo('App\Models\ProjectStage', 'project_stage_id');
    }
}
