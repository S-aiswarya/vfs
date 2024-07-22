<?php
namespace App\SpiderMail;

use Illuminate\Support\Facades\Facade;

class SpiderMailFacade extends Facade
{
     protected static function getFacadeAccessor()
     {
          return 'spider-mailer';
     }
}