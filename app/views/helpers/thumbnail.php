<?php  
class ThumbnailHelper extends AppHelper {  
    function render($image,$params){  
    error_log("IMG=$image");
#return $image;
        //Set defaults  
        $path='';  
	$relcachepath = 'img'.DS.'thumbs'.DS; # DEFAULT
	$cachepath= WWW_ROOT.$relcachepath;
        $width=150;  
        $height=225;  
        $quality=75;  
	$errorFilename = '';
	$thumbPath = '';
        //Extract Parameters  
        if(isset($params['path'])){  
            $path = $params['path'].DS;  
        }  
	if (isset($params['cachepath'])){
	    $relcachepath = $params['cachepath'].DS;
	    $cachepath = WWW_ROOT.$relcachepath;
	}
	$errorFilename = '';
	if (isset($params['errorfile']))
	{
		$errorFilename = $params['errorfile'];
	}
	if (isset($params['thumbpath']))
	{
		$thumbPath = $params['thumbpath'];
	}

        if(isset($params['width'])){  
            $width = $params['width'];  
        }  
        if(isset($params['height'])){  
            $height = $params['height'];  
        }  
        if(isset($params['quality'])){  
            $quality = $params['quality'];  
        }  
        //import phpThumb class  
        app::import('Vendor','phpthumb',array('file'=>'phpThumb'.DS.'phpthumb.class.php'));  
        $thumbNail = new phpthumb;  
        $thumbNail->src = WWW_ROOT.DS.$path.$image;  
        $thumbNail->w = $width;  
        $thumbNail->h = $height;  
        $thumbNail->q = $quality;  
        $thumbNail->config_imagemagick_path = '/usr/bin/convert';  
        $thumbNail->config_prefer_imagemagick = true;  
        $thumbNail->config_output_format = 'jpg';  
        $thumbNail->config_error_die_on_error = true;  
        $thumbNail->config_document_root = '';  
        $thumbNail->config_temp_directory = APP . 'tmp';  
        $thumbNail->config_cache_directory = $cachepath;
        $thumbNail->config_cache_disable_warning = true;  
        $cacheFilename = $image;  
        $thumbNail->cache_filename = $thumbNail->config_cache_directory.$cacheFilename;  
	error_log("CF=".$thumbNail->cache_filename);
	error_log("CF2=".$cacheFilename);

	if (!is_file($thumbNail->src))
	{
		return $errorFilename;
	}


        if(!is_file($thumbNail->cache_filename)){  
	error_log("NOTFI");
            if($thumbNail->GenerateThumbnail()) {  
		error_log("RENTOFI");
                $thumbNail->RenderToFile($thumbNail->cache_filename);  
            } else { # Return error image instead.
	      error_log("COULDNT GEN");
	      return $errorFilename;
	    }
        }  
	error_log("RET");
        if(is_file($thumbNail->cache_filename)){  
	error_log("CFX=$cacheFilename");
            return $relcachepath . $cacheFilename;  
        } else {
	      error_log("COULDNT GEN");
	      return $errorFilename;
	}

    }  
}  
?> 
