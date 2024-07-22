<?php

namespace App\Models;

use App\Models\BaseModel as Model;

class LeadWebhookData extends Model
{
    protected $table = 'lead_webhook_data';

    protected $dates = ['created_at'];

}