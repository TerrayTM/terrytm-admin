<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class QueryPermission extends Eloquent {
    protected $guarded = [];
    public $timestamps = false;

    public function generate_link() {
        $rules = json_decode($this->rules, true);
        $result = "https://api.terrytm.com/wain.php?route=query&model=" . $this->model;

        foreach ($rules as $key => $value) {
            if ($rules[$key] === "*") {
                $result .= "&" . $key . "=???";
            } else {
                $result .= "&" . $key . "=" . $value;
            }
        }

        return $result;
    }

    public function is_allowed($parameters) {
        $rules = json_decode($this->rules, true);

        if (count(array_diff_key($parameters, $rules)) !== 0 || count(array_diff_key($rules, $parameters)) !== 0) {
            return false;
        }

        foreach ($parameters as $key => $value) {
            if ($rules[$key] !== "*" && $rules[$key] !== $value) {
                return false;
            }

            if (str_contains($value, "?")) {
                return false;
            }
        }

        return true;
    }
}

?>
