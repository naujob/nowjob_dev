<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    public function __construct() {

        $this->_ci_ob_level  = ob_get_level();

		$this->_ci_helper_paths = [APPPATH, SHAREDPATH, BASEPATH];
        $this->_ci_library_paths = [APPPATH, SHAREDPATH, BASEPATH];
        $this->_ci_model_paths = [APPPATH, SHAREDPATH];
    }
	

    function _ci_autoloader($basepath = NULL)
    {
        if ($basepath !== NULL) {
            $autoload_path = $basepath . 'config/autoload.php';
        }
        else {
            $autoload_path = APPPATH . 'config/autoload.php';
        }

        if (file_exists($autoload_path) === FALSE) {
            $autoload_path = SHAREDPATH . 'config/autoload.php';
        }

        if (!file_exists($autoload_path)) {
            return FALSE;
        }

        include_once($autoload_path);

        if (!isset($autoload)) {
            return FALSE;
        }

        // Autoload packages
        if (isset($autoload['packages'])) {
            foreach ($autoload['packages'] as $package_path) {
                $this->add_package_path($package_path);
            }
        }

        // Autoload sparks
        if (method_exists(__CLASS__, 'spark') === TRUE && isset($autoload['sparks'])) {
            foreach ($autoload['sparks'] as $spark) {
                $this->spark($spark);
            }
        }

        if (isset($autoload['config'])) {
            // Load any custom config file
            if (count($autoload['config']) > 0) {
                $CI =& get_instance();
                foreach ($autoload['config'] as $key => $val) {
                    $CI->config->load($val);
                }
            }
        }

        // Autoload helpers and languages
        foreach (array('helper', 'language') as $type) {
            if (isset($autoload[$type]) and count($autoload[$type]) > 0) {
                $this->$type($autoload[$type]);
            }
        }

        // A little tweak to remain backward compatible
        // The $autoload['core'] item was deprecated
        if (!isset($autoload['libraries']) and isset($autoload['core'])) {
            $autoload['libraries'] = $autoload['core'];
        }

        // Load libraries
        if (isset($autoload['libraries']) and count($autoload['libraries']) > 0) {
            // Load the database driver.
            if (in_array('database', $autoload['libraries'])) {
                $this->database();
                $autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
            }

            // Load all other libraries
            foreach ($autoload['libraries'] as $item) {
                $this->library($item);
            }
        }

        // Autoload models
        if (isset($autoload['model'])) {
            $this->model($autoload['model']);
        }
    }

    protected function _ci_load($_ci_data) {
        // Set the default data variables
        foreach ([
                     '_ci_view',
                     '_ci_vars',
                     '_ci_path',
                     '_ci_return'
                 ] as $_ci_val) {
            $$_ci_val = isset($_ci_data[$_ci_val]) ? $_ci_data[$_ci_val] : FALSE;
        }

        $file_exists = FALSE;

        // Set the path to the requested file
        if (is_string($_ci_path) && $_ci_path !== '') {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        }
        else {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext === '' || $_ci_ext !== 'php') ? $_ci_view . '.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $_ci_view_file => $cascade) {
                if (file_exists($_ci_view_file . $_ci_file)) {
                    $_ci_path = $_ci_view_file . $_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                if (!$cascade) {
                    break;
                }
            }
        }

        if (!$file_exists && !file_exists($_ci_path)) {
            show_error('Unable to load the requested file: ' . $_ci_file);
        }

        $_ci_CI =& get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var) {
            if (!isset($this->$_ci_key)) {
                $this->$_ci_key =& $_ci_CI->$_ci_key;
            }
        }

        empty($_ci_vars) or $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        extract($this->_ci_cached_vars);

      
        ob_start();

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.
        if (!is_php('5.4') && !ini_get('short_open_tag') && config_item('rewrite_short_tags') === TRUE) {
            echo eval('?>' . preg_replace('/;*\s*\?>/', '; ?>', str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        }
        else {
            include($_ci_path); // include() vs include_once() allows for multiple views with the same name
        }

        log_message('info', 'File loaded: ' . $_ci_path);

        // Return the file data if requested
        if ($_ci_return === TRUE) {
            $buffer = ob_get_contents();
            @ob_end_clean();
            return $buffer;
        }

        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         */
        if (ob_get_level() > $this->_ci_ob_level + 1) {
            ob_end_flush();
        }
        else {
            $_ci_CI->output->append_output(ob_get_contents());
            @ob_end_clean();
        }

        return $this;
    }

    public function model($model, $name = '', $db_conn = FALSE)
    {
        if (empty($model)) {
            return $this;
        }
        else if (is_array($model)) {
            foreach ($model as $key => $value) {
                is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
            }

            return $this;
        }

        $path = '';

        // Is the model in a sub-folder? If so, parse out the filename and path.
        if (($last_slash = strrpos($model, '/')) !== FALSE) {
            // The path is in front of the last slash
            $path = substr($model, 0, ++$last_slash);

            // And the model name behind it
            $model = substr($model, $last_slash);
        }

        if (empty($name)) {
            $name = $model;
        }

        if (in_array($name, $this->_ci_models, TRUE)) {
            return $this;
        }

        $CI =& get_instance();
        if (isset($CI->$name)) {
            throw new RuntimeException('The model name you are loading is the name of a resource that is already being used: ' . $name);
        }

        if ($db_conn !== FALSE && !class_exists('CI_DB', FALSE)) {
            if ($db_conn === TRUE) {
                $db_conn = '';
            }

            $this->database($db_conn, FALSE, TRUE);
        }

        if (!class_exists('CI_Model', FALSE)) {
            $app_path = APPPATH . 'core' . DIRECTORY_SEPARATOR;
            if (file_exists($app_path . 'Model.php')) {
                require_once($app_path . 'Model.php');
                if (!class_exists('CI_Model', FALSE)) {
                    throw new RuntimeException($app_path . "Model.php exists, but doesn't declare class CI_Model");
                }

                log_message('info', 'CI_Model class loaded');
            }
            else if (!class_exists('CI_Model', FALSE)) {
                require_once(BASEPATH . 'core' . DIRECTORY_SEPARATOR . 'Model.php');
            }

            $class = config_item('subclass_prefix') . 'Model';
            // 이 부부만 재정의
            $paths = [APPPATH, SHAREDPATH];
            foreach ($paths as $app_path) {
                $app_path .= 'core' . DIRECTORY_SEPARATOR;
                if (file_exists($app_path . $class . '.php')) {
                    require_once($app_path . $class . '.php');
                    if (!class_exists($class, FALSE)) {
                        throw new RuntimeException($app_path . $class . ".php exists, but doesn't declare class " . $class);
                    }

                    log_message('info', config_item('subclass_prefix') . 'Model class loaded');
                }
            }
        }

        $model = strtolower($model);
        if (!class_exists($model, FALSE)) {
            foreach ($this->_ci_model_paths as $mod_path) {
                if (!file_exists($mod_path . 'models/' . $path . $model . '.php')) {
                    continue;
                }

                require_once($mod_path . 'models/' . $path . $model . '.php');
                if (!class_exists($model, FALSE)) {
                    throw new RuntimeException($mod_path . "models/" . $path . $model . ".php exists, but doesn't declare class " . $model);
                }

                break;
            }

            if (!class_exists($model, FALSE)) {
                throw new RuntimeException('Unable to locate the model you have specified: ' . $model);
            }
        }
        else if (!is_subclass_of($model, 'CI_Model')) {
            throw new RuntimeException("Class " . $model . " already exists and doesn't extend CI_Model");
        }

        $this->_ci_models[] = $name;
        $model = new $model();
        $CI->$name = $model;
        log_message('info', 'Model "' . get_class($model) . '" initialized');
        return $this;
    }
}