<?php

namespace App\Http\Triats;

trait GeneralTrait 
{

    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    public function returnError($errorNum ="001", $msg)
    {
        return response()->json([
            'status' => false ,
            'errnum' =>  $errorNum,
            'msg' => $msg  
        ]);
    }

    public function returnSuccessMessage($msg ="" , $errorNum = "000")
    {
        return response()->json([
            'status' => true ,
            'errnum' =>  $errorNum,
            'msg' => $msg  
        ]);
    }

    public function returnData($key, $value, $msg ="")
    {
        return response()->json([
            'status' => true ,
            'errnum' =>  "000",
            'msg' => $msg ,
            $key => $value  
        ]);
    }

    public function returnValidationError($code = "E001", $validator)
    {
        return $this->returnError($code, $validator->errors()->first());
    }


    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    public function getErrorCode($input)
    {
        if ($input == "name")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else if ($input == "mobile")
            return 'E003';

        else
            return "";
    }

    public function savaPhoto($Photo , $Folder){
        // save photo in folder
        $file_extension =$Photo ->getClientOriginalExtension();
        $file_name = time() .'.'.$file_extension ;
        $path =$Folder;
        $Photo->move($path,$file_name);

        return $file_name;
    }

}