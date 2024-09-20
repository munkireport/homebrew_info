<?php 

/**
 * homebrew_info module class
 *
 * @package munkireport
 * @author tuxudo
 **/
class Homebrew_info_controller extends Module_controller
{
    /*** Protect methods with auth! ****/
    function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    /**
     * Default method
     * @author tuxudo
     *
     **/
    function index()
    {
        echo "You've loaded the homebrew_info module!";
    }

    /**
     * Retrieve data in json format
     *
     **/
    public function get_tab_data($serial_number = '')
    {
        // Remove non-serial number characters
        $serial_number = preg_replace("/[^A-Za-z0-9_\-]]/", '', $serial_number);

        $obj = new View();

        if (! $this->authorized()) {
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }

        $sql = "SELECT homebrew_version, homebrew_noanalytics_this_run, core_tap_head, core_tap_origin, core_tap_last_commit, core_cask_tap, head, last_commit, origin, homebrew_bottle_domain, homebrew_cellar, homebrew_prefix, homebrew_repository, homebrew_git_config_file, homebrew_ruby, homebrew_cask_opts, homebrew_make_jobs, command_line_tools, cpu, git, clang, curl, java, perl, python, ruby, x11, xcode, macos, rosetta_2
                        FROM homebrew_info 
                        WHERE serial_number = '$serial_number'";

        $queryobj = new Homebrew_info_model();
        $homebrew_info_tab = $queryobj->query($sql);
        $obj->view('json', array('msg' => current(array('msg' => $homebrew_info_tab[0])))); 
    }
} // END class Homebrew_info_controller
