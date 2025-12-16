<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CodeZone.in
 * Author: Dineshkumar
 * Contact : hi@codezone.in
 * Add functionality to read into more than one sub-folder of controller
 * Compatible with Codeigniter 3.x
 *
 */
 
Class MY_Router extends CI_Router
{
    Function __construct()
    {
        parent::__construct();
    }

    function _validate_request($segments)
    {

       if (file_exists(APPPATH.'controllers/'.$segments[0].".php"))
        {
            return $segments;
        }

        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            /* ----------- CHECK SUB FOLDER STARTS ------------ */

            while(count($segments) > 0 && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0]))
            {
            if (substr($this->directory, -1, 1) == '/')
                $this->directory = $this->directory . $segments[0];
            else
                $this->directory = $this->directory . '/' . $segments[0];

            $segments = array_slice($segments, 1);
            }

            if (substr($this->directory, -1, 1) != '/')
                $this->directory = $this->directory . '/';

            /* ----------- CHECK SUB FOLDER ENDS ------------ */

            if (count($segments) > 0)
            {

                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().'/'.$segments[0].".php"))
                {
                    show_404($this->fetch_directory().$segments[0]);
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');

                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().'/' .$this->default_controller.".php"))
                {
                    $this->directory = '';
                    return array();
                }

            }

            return $segments;
        }

        show_404($segments[0]);
    }
}