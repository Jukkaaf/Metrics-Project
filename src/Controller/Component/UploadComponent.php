<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Network\Exception\InternalErrorException;

class UploadComponent extends Component{
    public $max_file_ammount = 1;
    
    // the function that handels the uploaded weeklyreports
    public function send($data){
        // check that have a file, and only one of them
        if(!empty($data) && count($data) == $this->max_file_ammount){
            $filename = $data[0]['name'];
            $temp = explode('.', $filename);
            // check that the file is a .txt
            if(strcmp($temp[1], "txt") == 0){                
                //print_r($temp[0]);
                // check that the file was uploaded with no errors, and that it was indeed uploaded
                if ($data[0]['error'] == UPLOAD_ERR_OK && is_uploaded_file($data[0]['tmp_name'])){
                    return file_get_contents($data[0]['tmp_name']);
                }
            }
            else{
                throw new InternalErrorException("Wrong file type {$this->temp[1]}", 1);
            }
        }
        else{
            throw new InternalErrorException("Maximum file ammount is {$this->max_file_ammount}", 1);
        }
    }
}

