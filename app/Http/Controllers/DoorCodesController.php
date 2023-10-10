<?php

namespace App\Http\Controllers;

use App\Models\DoorCodes;
use Illuminate\Http\Request;
use Nette\Utils\Random;

class DoorCodesController extends Controller
{
    public static function createDoorCodeFromName($name,int $length = 6){
        $code = Random::generate($length,'0-9');

        $doorcode = new DoorCodes();

        $doorcode->name = $name;

        // Check restrictions

        $doorcode->code = $code;

        $doorcode->save();

        dd($doorcode);
    }

    private function checkRestrictions($code){
        // Code cannot be a palindrome
        if($code === strrev($code)){
            return false;
        }


        $characters = [];
        foreach ($code as $character) {
            $character = $character->toString();
            if(!isset($characters[$character])){
                $characters[$character] = 1;
                continue;
            }
            $characters[$character]++;
        }

        // Code must have 3 unique characters
        if(count($characters) < 3){
            return false;
        }

        // Character cannot be repeated more than 3 times
        rsort($characters);
        $firstkey = array_key_first($characters);
        if($characters[$firstkey] > 3){
            return false;
        }

        // Character cannot be in a sequence of more than 3
    }
}
