<?php
/**
 * LoaderTool object.
 * This object is used to propose files to download.
 *
 * Usage :
 *    To download a file, simply send it to user with :
 *
 *    public function executeDownload()
 *    {
 *      return LoaderTool::downloadContent($local_file_path, $downloaded_filename);
 *    }
 *
 *    By default, file is downloaded as attachement, and MIME type is auto detected.
 *    Options are available to force MIME type or inline display.
 *
 *
 *
 * Configuration :
 *    If you are using Lighttpd, support for X-Sendfile is available.
 *    To enable it in the application, add this to your app.yml configuration file :
 *
 *    all:
 *      loadertool:
 *        enable_xsendfile: true
 *
 * To enable X-Sendfile in the Lighttpd configuration, add this line to your fast-cgi configuration :
 *
 *    fastcgi.server =
 *    (
 *      ".php" =>
 *        ((
 *          ...
 *          "allow-x-send-file" => "enable",
 *          ...
 *        ))
 *    )
 *
 * @package    easyCreaDoc
 * @subpackage lib.tools
 * @author     Pierre-Yves Landuré <py.landure@dorigo.fr>
 * @version    SVN: $Id$
 */
class LoaderTool
{

  /** the code for Lighttpd server softare */
  const LIGHTTPD = 'lighttpd';

  /** the code for apache server softare */
  const APACHE = 'apache';

  /** the code for unknown server softare */
  const OTHER = 'other';



  /**
   * Detect the server software (lighttpd, apache, or other).
   * 
   * @static
   * @access public
   * @return string The server software name.
   */
  public static function detectServerSoftware()
  {

    if(preg_match('/^lighttpd/', $_SERVER['SERVER_SOFTWARE']))
    {
      return self::LIGHTTPD;
    }

    if(preg_match('/^Apache/', $_SERVER['SERVER_SOFTWARE']))
    {
      return self::APACHE;
    }

    return self::OTHER;
  } // detectServerSoftware()



  /**
   * Detect a content or file MIME type.
   * 
   * @param string $content A file contents or name.
   * @static
   * @access public
   * @return string The detected MIME type.
   */
  public static function detectMimeType($content)
  {
    $mime_type = null;

    if(function_exists('finfo_file')) { // Test if finfo extention is present.
      $finfo = finfo_open(FILEINFO_MIME);

      if(is_file($content)) // Test if content is a file name.
      {
        $mime_type = finfo_file($finfo, $content);
      }
      else // Test if content is a file name.
      {
        $mime_type = finfo_buffer($finfo, $content);
      } // Test if content is a file name.

      finfo_close($finfo);
    }
    else // Test if finfo extention is present.
    {

      if(is_file($content)) // Test if content is a file name.
      {
        $mime_type = mime_content_type($content);
      }
      else // Test if content is a file name.
      {
        // We create a temporary file to detect MIME type.
        $mime_temp_file = tempnam(sys_get_temp_dir(), "ecd_") . "";
        file_put_contents($mime_temp_file, $content);

        $mime_type = mime_content_type($mime_temp_file);

        unlink($mime_temp_file);
      } // Test if content is a file name.

    } // Test if finfo extention is present.

    return $mime_type;
  } // detectMimeType()



  /**
   * Return true if the content associated to the given MIME type is binary.
   * 
   * @param string $mime_type A MIME type.
   * @static
   * @access public
   * @return boolean Binary status of the content.
   */
  public static function isBinaryContent($mime_type)
  {
    list($mime_type_major, $mime_type_minor) = explode('/', $mime_type); 

    switch($mime_type_major) // According to MIME type major.
    {
      case 'application':
      case 'image':
          return true;
          break;
      case 'text':
      default:
          return false;
    } // According to MIME type major.

    return false;
  } // isBinaryContent()



  /**
   * Allow users to download a file.
   * 
   * @param string $contents A local file name or contents.
   * @param string $filename The download file name, presented to user.
   * @param boolean $inline True to display the file inline, default to false.
   * @param string $mime_type Allow to force the MIME type (like application/pdf). If not set, the MIME type is auto-detected.
   * @static
   * @access public
   * @return integer Return sfView::NONE.
   */
  public static function downloadContent(&$contents, $filename = null, $inline = false, $mime_type = null)
  {

    $is_file = is_file($contents);

    if(is_null($mime_type)) // Test if MIME type forced.
    {
      $mime_type = self::detectMimeType($contents);
    } // Test if MIME type forced.

    if(is_null($filename)) // Test if custom filename is to be used.
    {

      if($is_file) // Test if content is a file.
      {
        $filename = basename($contents);
      }
      else // Test if content is a file.
      {
        return false;
      } // Test if content is a file.

    } // Test if custom filename is to be used.

    $response = sfContext::getInstance()->getResponse();

    if($mime_type == 'application/pdf') // Test if content is PDF.
    {
      $response->setHttpHeader('Cache-Control', 'public');
      $response->setHttpHeader('Pragma', '');
      $response->setHttpHeader('Expires', '0');
    }
    else // Test if content is PDF.
    {
      $response->setHttpHeader('Pragma', 'no-cache');
      $response->setHttpHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
    } // Test if content is PDF.

    $response->setHttpHeader('Content-Type', $mime_type);
    $response->setHttpHeader('Content-Disposition', sprintf('%s; filename="%s"', $inline ? 'inline' : 'attachment', $filename));

    if(self::isBinaryContent($mime_type)) // Test if content should be presented as binary.
    {
      $response->setHttpHeader('Content-Transfer-Encoding', 'binary');
    } // Test if content should be presented as binary.

    if($is_file) // Test if content is a file.
    {
      $response->setHttpHeader('Content-Length', filesize($contents));

      // Unlock session in order to prevent php session warnings
      sfContext::getInstance()->getUser()->shutdown();

      if(self::detectServerSoftware() == self::LIGHTTPD && sfConfig::get('app_loadertool_enable_xsendfile', false))
      {
        $response->setHttpHeader('X-Sendfile', $contents);

        // Send http headers to user client
        $response->sendHttpHeaders();
        $response->setContent('');
      }
      else
      {
        // Send http headers to user client
        $response->sendHttpHeaders();

        readfile($contents);
      }
    }
    else // Test if content is a file.
    {
      $response->setHttpHeader('Content-Length', strlen($contents));

      // Unlock session in order to prevent php session warnings
      sfContext::getInstance()->getUser()->shutdown();

      // Send http headers to user client
      $response->sendHttpHeaders();

      $response->setContent($contents);
    } // Test if content is a file.

    return sfView::NONE;
  } // downloadContent()

} // class LoaderTool

