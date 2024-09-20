<?php

use CFPropertyList\CFPropertyList;

class Homebrew_info_model extends \Model {

    function __construct($serial='')
    {
        parent::__construct('id', 'homebrew_info'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['serial_number'] = $serial;
        $this->rs['core_tap_head'] = null;
        $this->rs['core_tap_origin'] = null; 
        $this->rs['core_tap_last_commit'] = null;
        $this->rs['head'] = null;
        $this->rs['last_commit'] = null;
        $this->rs['origin'] = null;
        $this->rs['homebrew_bottle_domain'] = null;
        $this->rs['homebrew_cellar'] = null;
        $this->rs['homebrew_prefix'] = null;
        $this->rs['homebrew_repository'] = null;
        $this->rs['homebrew_version'] = null;
        $this->rs['homebrew_ruby'] = null;
        $this->rs['command_line_tools'] = null;
        $this->rs['cpu'] = null;
        $this->rs['git'] = null;
        $this->rs['clang'] = null;
        $this->rs['java'] = null;
        $this->rs['perl'] = null;
        $this->rs['python'] = null;
        $this->rs['ruby'] = null;
        $this->rs['x11'] = null;
        $this->rs['xcode'] = null;
        $this->rs['macos'] = null;
        $this->rs['homebrew_git_config_file'] = null;
        $this->rs['homebrew_noanalytics_this_run'] = null;
        $this->rs['curl'] = null;
        $this->rs['core_cask_tap'] = null;
        $this->rs['homebrew_cask_opts'] = null;
        $this->rs['homebrew_make_jobs'] = null;
        $this->rs['rosetta_2'] = null;

        if ($serial) {
            $this->retrieve_record($serial);
        }

        $this->serial_number = $serial;
    }

    // ------------------------------------------------------------------------

    /**
     * Process data sent by postflight
     *
     * @param string data
     * @author tuxudo
     **/
    function process($data)
    {
        // Check if data was uploaded
        if (! $data) {
            throw new Exception("Error Processing homebrew_info Module Request: No data found", 1);
        } else if (substr( $data, 0, 30 ) != '<?xml version="1.0" encoding="' ) { // Else if old style json, process with old json based handler
            // Process json into object thingy
            $brewinfo = json_decode($data, true);

            // Translate brew info strings to db fields
            $translate = array(
                'CLT' => 'command_line_tools',
                'CPU' => 'cpu',
                'Clang' => 'clang',
                'Core tap HEAD' => 'core_tap_head',
                'Core tap ORIGIN' => 'core_tap_origin',
                'Core tap last commit' => 'core_tap_last_commit',
                'Git' => 'git',
                'HEAD' => 'head',
                'HOMEBREW_BOTTLE_DOMAIN' => 'homebrew_bottle_domain',
                'HOMEBREW_CELLAR' => 'homebrew_cellar',
                'HOMEBREW_PREFIX' => 'homebrew_prefix',
                'HOMEBREW_REPOSITORY' => 'homebrew_repository',
                'HOMEBREW_VERSION' => 'homebrew_version',
                'Homebrew Ruby' => 'homebrew_ruby',
                'Java' => 'java',
                'Last commit' => 'last_commit',
                'ORIGIN' => 'origin',
                'Perl' => 'perl',
                'Python' => 'python',
                'Ruby' => 'ruby',
                'X11' => 'x11',
                'Xcode' => 'xcode',
                'HOMEBREW_GIT_CONFIG_FILE' => 'homebrew_git_config_file',
                'HOMEBREW_NO_ANALYTICS_THIS_RUN' => 'homebrew_noanalytics_this_run',
                'Curl' => 'curl',
                'macOS' => 'macos'
            );

            // Traverse the brew info with translations
            foreach ($translate as $search => $field) {

                if (! array_key_exists($search, $brewinfo[0])){
                    // Skip keys that may not exist and null the value
                    $this->$field = null;
                } else if (! empty($brewinfo[0][$search])) {
                   // If key is not empty, save it to the object
                        $this->$field = $brewinfo[0][$search];
                } else if ($brewinfo[0][$search] == "0"){
                    // Set the value to 0 if it's 0
                    $this->$field = $brewinfo[0][$search];
                } else {
                    // Else, null the value
                    $this->$field = null;
                }
            }
            // Save the info
            $this->save();

        } else { // Else process with new XML handler 

            // Process incoming hombrew_info.plist
            $parser = new CFPropertyList();
            $parser->parse($data, CFPropertyList::FORMAT_XML);
            $plist = $parser->toArray();

            // Add the serial number to each entry
            $plist['serial_number'] = $this->serial_number;

            // Delete previous set
            $this->deleteWhere('serial_number=?', $this->serial_number);

            foreach ($this->rs as $key => $value){
                // If key does not exist in $plist, null it
                if ( ! array_key_exists($key, $plist) || $plist[$key] == '' && $plist[$key] != '0') {
                    $this->rs[$key] = null;
                // Set the db fields to be the same as those in the plist file
                } else {
                    $this->rs[$key] = $plist[$key];
                }
            }

            // Save the info
            $this->save();
        }
    }
}
