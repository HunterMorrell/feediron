<?php

class Feediron_Helper
{
  public static function getCleanupConfig( $config )
  {
    $cconfig = false;

    if (isset($config['cleanup']))
    {
      $cconfig = $config['cleanup'];
      if (!is_array($cconfig))
      {
        $cconfig = array($cconfig);
      }
    }
    return $cconfig;
  }

  public static function check_array( $data )
  {

    if(!is_array( $data )){
      $array = array( $data );
    }else{
      $array = $data;
    }

    return $array;

  }

  // reformat a string with given options
  public static function reformat($string, $options)
  {
    Feediron_Logger::get()->log(Feediron_Logger::LOG_VERBOSE, "Reformat ", $string);
    foreach($options as $option)
    {
      Feediron_Logger::get()->log_object(Feediron_Logger::LOG_VERBOSE, "Reformat step with option ", $option);
      switch($option['type'])
      {
        case 'replace':
        $string = str_replace($option['search'], $option['replace'], $string);
        break;

        case 'regex':
        $string = preg_replace($option['pattern'], $option['replace'], $string);
        break;
      }
      Feediron_Logger::get()->log(Feediron_Logger::LOG_VERBOSE, "Step result ", $string);
    }
    Feediron_Logger::get()->log(Feediron_Logger::LOG_VERBOSE, "Result ", $string);
    return $string;
  }

  public static function getDOM( $html, $charset, $debug ){
    $doc = new DOMDocument();
    if ($charset) {
			Feediron_Logger::get()->log(Feediron_Logger::LOG_VERBOSE, "Applying Character set: ".$charset);
      $html = '<?xml encoding="' . $charset . '">' . $html;
    }
    libxml_use_internal_errors(true);
    $doc->loadHTML($html);
    if(!$doc)
    {
      Feediron_Logger::get()->log(Feediron_Logger::LOG_TTRSS, "The content is not a valid xml format");
      if( $debug )
      {
        foreach (libxml_get_errors() as $value)
        {
          Feediron_Logger::get()->log(Feediron_Logger::LOG_TTRSS, $value);
        }
      }
      return new DOMDocument();
    }
    return $doc;
  }

  // reformat an url with a given config
  public static function reformatUrl($url, $config)
  {
    $link = trim($url);
    if($this->array_check($config, 'reformat'))
    {
      $link = reformat($link, $config['reformat']);
      Feediron_Logger::get()->log(Feediron_Logger::LOG_TTRSS, "Reformated url: ".$link);
    }
    return $link;
  }
}
