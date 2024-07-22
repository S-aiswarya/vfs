<?php

namespace App\Enum;

enum StageType: string{
    case Normal = "Normal";
    case LeadCreate = "Lead Created";
    case LeadConfirmed = "Lead Confirmed";
    case ApplicationCreated = "Application Created";
    case LeadClosed = "Lead Closed";
    case ApplicationClosed = "Application Closed";
}
