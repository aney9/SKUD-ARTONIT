<?php defined('SYSPATH') OR die('No direct script access.');

class Kohana_ExceptionCRM2 extends Kohana_Kohana_Exception
{
    /**
     * Overriden to show custom page for 404 errors
     */
    public static function handler(Exception $e)
    {
        //echo Debug::vars('10 Test my exception', $e);exit;
		
		switch (get_class($e))
        {
            case 'HTTP_Exception_404':
                $response = new Response;
                $response->status(404);
                $view = new View('error/report_404');
                $view->message = $e->getMessage();
                echo $response->body($view)->send_headers()->body();
                if (is_object(Kohana::$log))
                {
                    // Add this exception to the log
                    Kohana::$log->add(Log::ERROR, $e);
                    // Make sure the logs are written
                    Kohana::$log->write();
                }
                return TRUE;
                break;

            default:
                return Kohana_Kohana_Exception::handler($e);
                break;
        }
    }

  /**
    * Override if necessary.  E.g. below include logged in user's info in the log
   */
   /* public static function text(Exception $e)
   {

    $id = <get user id from session>;
    return sprintf('[user: %s] %s [ %s ]: %s ~ %s [ %d ]',
            $id, get_class($e), $e->getCode(), strip_tags($e->getMessage()), Debug::path($e->getFile()), $e->getLine());

   } */
}