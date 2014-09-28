<?php

/****************************************
Example of how to use this uploader class...
You can uncomment the following lines (minus the require) to use these as your defaults.

// list of valid extensions, ex. array("jpeg", "xml", "bmp")
$allowedExtensions = array();
// max file size in bytes
$sizeLimit = 10 * 1024 * 1024;

require('valums-file-uploader/server/php.php');
$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

// Call handleUpload() with the name of the folder, relative to PHP's getcwd()
$result = $uploader->handleUpload('uploads/');

// to pass data through iframe you will need to encode all html tags
echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

/******************************************/



/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
	var $base = '';
	var $array_item = '';
	
	function __construct($base='qqfile', $array_item = null) {
		$this->base = $base;
		$this->array_item = $array_item;
	}
	 
    function save($path) {
		if ($this->array_item != null) {
			if (count($this->array_item)==1) {
				if(!move_uploaded_file($_FILES[$this->base]['tmp_name'][$this->array_item[0]], $path)){
					return false;
				}
			} else {
				if(!move_uploaded_file($_FILES[$this->base]['tmp_name'][$this->array_item[0]][$this->array_item[1]], $path)){
					return false;
				}
			}
		} else {
			if(!move_uploaded_file($_FILES[$this->base]['tmp_name'], $path)){
				return false;
			}
		}
        return true;
    }
    function getName() {
		if ($this->array_item != null) {
			if (count($this->array_item)==1) {
				return $_FILES[$this->base]['name'][$this->array_item[0]];
			} else {
				return $_FILES[$this->base]['name'][$this->array_item[0]][$this->array_item[1]];
			}
		} else {
			return $_FILES[$this->base]['name'];
		}
    }
    function getSize() {
        if ($this->array_item != null) {
			if (count($this->array_item)==1) {
				return $_FILES[$this->base]['size'][$this->array_item[0]];
			} else {
				return $_FILES[$this->base]['size'][$this->array_item[0]][$this->array_item[1]];
			}
		} else {
			return $_FILES[$this->base]['size'];
		}
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $base='qqfile', $array_item=null, $sizeLimit = 10485760){    
		if (!$allowedExtensions) {
			$allowedExtensions = array('bmp','csv','doc','gif','ico','jpg','jpeg','odg','odp','ods','odt','pdf','png','ppt','swf','txt','xcf','xls');
		}
		    
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
        
		
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES[$base])) {
            $this->file = new qqUploadedFileForm($base, $array_item);
        } else {
            $this->file = false; 
        }
    }
    
	public function getName(){
		if ($this->file)
			return $this->file->getName();
	}
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
		
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
		
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];
        //$filename = md5(uniqid());
        $ext = @$pathinfo['extension'];		// hide notices if extension is empty

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return $filename.'.'.$ext;
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}
