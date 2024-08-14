<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class CheckinExport implements FromView
{
    public $collection, $headings, $title;

    public function __construct($collection, $headings, $title)
    {
        $this->collection = $collection;
        $this->headings = $headings;
        $this->title = $title;
   }
    
   public function view(): View
   {
       return view('admin.exports.Viewfile', [
           'collections' => $this->collection, 'headings'=>$this->headings, 'excelheadings'=>$this->title
       ]);
   }

}
