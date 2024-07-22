<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class LeadCampaign extends Model
{
    protected $table = 'lead_campaigns';

    protected $dates = ['created_at', 'updated_at'];

}