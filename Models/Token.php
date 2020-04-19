<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Token extends Eloquent {
    protected $guarded = [];

    public static function generate() {
        require_once(__DIR__ . "/../Helpers/GenerateToken.php");

        $value = generate_token();

        Token::create([
            "value" => $value
        ]);

        return $value;
    }

    public static function if_valid_then_consume($value) {
        $token = Token::where("value", $value)->first();

        if ($token && !$token->is_consumed) {
            $token->is_consumed = true;

            $token->save();
            
            return true;
        }

        return false;
    }
}

?>
