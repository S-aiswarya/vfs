<?php 
namespace App\Traits;

use Illuminate\Http\JsonResponse, Redirect, Request, Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationTrait
{
    protected $val_rules;
    protected $val_attributes;
    protected $val_errors;
    
    public function __validationConstruct() {

        $this->setRules();
        $this->setAttributes();
        $this->setMessages();
    }
    
    abstract protected function setRules();
    
    public function setAttributes() {
        $this->val_attributes = [];
    }
    
    public function setMessages() {
        $this->val_errors = [];
    }
    
    public function addRule($key = '', $rule = '') {
        if (isset($this->val_rules[$key])) 
            $this->val_rules[$key] .= '|' . $rule;
    }
    
    public function addRules($newRules = [], $newNiceNames = [], $newErrorMessages = []) {
        if( is_array($newRules) )
            $this->val_rules = array_merge($this->val_rules, $newRules);
        if( is_array($newNiceNames) )
            $this->val_attributes = array_merge($this->val_attributes, $newNiceNames);
        if( is_array($newErrorMessages) )
            $this->val_errors = array_merge($this->val_errors, $newErrorMessages);
    }
    
    public function removeRule($keys = array()) {
        if (!is_array($keys)) $keys = [$keys];
        foreach ($keys as $key) {
            if (isset($this->val_rules[$key])) unset($this->val_rules[$key]);
        }
    }
    
    public function cutRules($unwantedRules = array()) {
        foreach ($unwantedRules as $key => $rules) {
            if (isset($this->val_rules[$key])) {
                $curRulesType = 'string';
                if(is_array($this->val_rules[$key])){
                    $curRulesArray = $this->val_rules[$key];
                    $curRulesType = 'array';
                } else {
                    $curRulesArray = explode('|', $this->val_rules[$key]);
                }
                $unwantedRulesArray = explode('|', $rules);
                $this->val_rules[$key] = array_diff($curRulesArray, $unwantedRulesArray);
                if($curRulesType == 'string')
                    $this->val_rules[$key] = implode('|', $this->val_rules[$key]);
            } 
        }
    }
    
    public function stripRules($keys = array()) {
        if (!is_array($keys)) $keys = [$keys];
        $newRules = array();
        foreach ($keys as $key) {
            if (isset($this->val_rules[$key])) $newRules[$key] = $this->val_rules[$key];
        }
        $this->val_rules = $newRules;
    }
    
    public function addAttributes($key = '', $nice_name = '') {
        if ( is_array($key) ) {
            foreach ($key as $index => $value) {
                if( isset( $this->val_attributes[$index] ) )
                    $this->val_attributes[$index] = $value;
            }
        } else {                
            if( isset( $this->val_attributes[$key] ) )
                $this->val_attributes[$key] = $nice_name;
        }
    }
    
    public function validate($data=null, $throw_rror = true) {
        $data = $data ? $data : request()->all();

        // make a new validator object
        $validator = Validator::make($data, $this->val_rules, $this->val_errors, $this->val_attributes);
        
        // check for failure
        if ($validator->fails()) {
            // set errors and return false
            $this->errors = $validator->errors();
            if($throw_rror) {
                if (Request::ajax())
                    $response = new JsonResponse(['errors' => $this->errors]);
                else
                    $response = Redirect::back()->withInput()->withErrors($this->errors);

                throw new HttpResponseException($response);
            } else
                return false;
        }
        
        // validation pass
        return true;
    }
    
    public function errors() {
        return $this->errors;
    }
}
