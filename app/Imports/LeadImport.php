<?php

namespace App\Imports;

use App\Services\LeadService;
use DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $data = [];
        if(!empty($row['name']) && !empty($row['email'])){
            $data['name'] = $row['name'];
            $data['email'] = $row['email'];

            $data['phone_number'] = !empty($row['phone_number'])?$row['phone_number']:null;
            $data['alternate_phone_number'] = !empty($row['alternate_phone_number'])?$row['alternate_phone_number']:null;
            $data['whatsapp_number'] = !empty($row['whatsapp_number'])?$row['whatsapp_number']:null;
            
            $data['city'] = !empty($row['city'])?$row['city']:null;
            $data['preferred_destinations'] = !empty($row['preferred_destinations'])?$row['preferred_destinations']:null;
            $data['preferred_packages'] = !empty($row['preferred_packages'])?$row['preferred_packages']:null;
            $data['source_id'] = !empty($row['lead_source'])?$this->getLeadSourceId($row['lead_source']):null;
            $data['agency_id'] = !empty($row['referral_agency'])?$this->getAgencyId($row['referral_agency']):null;
            $data['note'] = !empty($row['note'])?$row['note']:null;
            $data['referrance_from'] = !empty($row['referance_from'])?$row['referance_from']:null;

            $data['passport'] = !empty($row['passport_number'])?$row['passport_number']:null;
            $data['passport_exp_date'] = !empty($row['passport_exp_date'])?$this->getDate($row['passport_exp_date']):null;

            $lead_service = new LeadService();
            $lead_service->store($data);
        }
    }

    protected function getDate($date)
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
    }

    protected function getLeadSourceId($name){
        $result = DB::table('lead_sources')->where('status', 1)->where('name', $name)->first();
        return $result?->id;
    }

    protected function getAgencyId($name){
        $result = DB::table('agencies')->where('status', 1)->where('name', $name)->first();
        return $result?->id;
    }

}
