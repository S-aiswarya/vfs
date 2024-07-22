<?php

namespace App\Models;

use App\Models\BaseModel as Model;
use App\Traits\ValidationTrait;

class AdminLink extends Model
{
    
    use ValidationTrait {
        ValidationTrait::validate as private parent_validate;
    }

    public function __construct(array $attributes = array()) {
        
        parent::__construct($attributes);
        $this->__validationConstruct();
    }

    protected $table = 'admin_links';


    protected $fillable = array('permissions_id', 'name', 'parent_id', 'icon', 'display_order');

    protected $dates = ['created_at','updated_at'];

    protected function setRules() {

        $this->val_rules = array(
            'name' => 'required|max:250',
            'permissions_id' => 'required'
        );
    }

    protected function setAttributes() {
        $this->val_attributes = array(
        );
    }

    public function validate($data = null, $ignoreId = 'NULL') {
        return $this->parent_validate($data);
    }

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission', 'permissions_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\AdminLink', 'parent_id');
    }

    public function children() 
    {
        return $this->hasMany('App\Models\AdminLink','parent_id','id')->orderBy('display_order') ;
    }

}