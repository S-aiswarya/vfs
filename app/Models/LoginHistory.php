<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Traits\ValidationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
	use ValidationTrait {
        ValidationTrait::validate as private parent_validate;
    }
    
    public function __construct() {
        
        parent::__construct();
        $this->__validationConstruct();
    }

    protected $table = 'login_history';
    
    protected $guarded = ['id'];

   

    protected function setRules() {

        $this->val_rules = array();
    }

    protected function setAttributes() {
        $this->val_attributes = array();
    }

    public function validate($data = null, $ignoreId = 'NULL') {
        return $this->parent_validate($data);
    }

 
   public function user()
   {
    return $this->belongsTo(User::class,'user_id','id');
   }

}