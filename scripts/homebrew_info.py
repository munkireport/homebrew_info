#!/usr/local/munkireport/munkireport-python3

import subprocess
import os
import plistlib
import re
import pwd

def get_brew_info(brew):

    # Get homebrew's owner because it runs everything in the user's context
    homebrew_owner = pwd.getpwuid(os.stat(brew).st_uid).pw_name
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))

    # We need this awful, ugly workaround because homebrew is evil when it comes to scripting as `root`
    os.system("/usr/bin/sudo -HE -u "+homebrew_owner+" "+brew+" config > "+cachedir+"/homebrew_info.txt")

    result = {}

    for item in open(cachedir+"/homebrew_info.txt").readlines():
        if "HOMEBREW_VERSION: " in item:
            result['homebrew_version'] = item.replace("HOMEBREW_VERSION: ", "").strip()
        elif "ORIGIN: " in item:
            result['origin'] = item.replace("ORIGIN: ", "").strip()
        elif "HEAD: " in item:
            result['core_tap_head'] = item.replace("HEAD: ", "").strip()
        elif "Last commit: " in item:
            result['last_commit'] = item.replace("Last commit: ", "").strip()
        elif "Core tap: " in item:
            result['core_tap_origin'] = item.replace("Core tap: ", "").strip()
        elif "Core cask tap: " in item:
            result['core_cask_tap'] = item.replace("Core cask tap: ", "").strip() #######
        elif "HOMEBREW_PREFIX: " in item:
            result['homebrew_prefix'] = item.replace("HOMEBREW_PREFIX: ", "").strip()
        elif "HOMEBREW_CASK_OPTS: " in item:
            result['homebrew_cask_opts'] = item.replace("HOMEBREW_CASK_OPTS: ", "").strip() #######
        elif "HOMEBREW_MAKE_JOBS: " in item:
            result['homebrew_make_jobs'] = item.replace("HOMEBREW_MAKE_JOBS: ", "").strip() #######
        elif "Homebrew Ruby: " in item:
            result['homebrew_ruby'] = item.replace("Homebrew Ruby: ", "").strip()
        elif "Clang: " in item:
            result['clang'] = item.replace("Clang: ", "").strip()
        elif "CPU: " in item:
            result['cpu'] = item.replace("CPU: ", "").strip()
        elif "Git: " in item:
            result['git'] = item.replace("Git: ", "").strip()
        elif "Curl: " in item:
            result['curl'] = item.replace("Curl: ", "").strip()
        elif "macOS: " in item:
            result['macos'] = item.replace("macOS: ", "").strip()
        elif "Java: " in item:
            result['java'] = item.replace("Java: ", "").strip()
        elif "Perl: " in item:
            result['perl'] = item.replace("Perl: ", "").strip()
        elif "Python: " in item:
            result['python'] = item.replace("Python: ", "").strip()
        elif "Ruby: " in item and "Homebrew Ruby: " not in item:
            result['ruby'] = item.replace("Ruby: ", "").strip()
        elif "X11: " in item:
            result['x11'] = item.replace("X11: ", "").strip()
        elif "CLT: " in item:
            result['command_line_tools'] = item.replace("CLT: ", "").strip()
        elif "Xcode: " in item:
            result['xcode'] = item.replace("Xcode: ", "").strip()
        elif "Rosetta 2: " in item:
            result['rosetta_2'] = item.replace("Rosetta 2: ", "").strip() #######

    # Cleanup after ourselves
    try:
        os.remove(cachedir+"/homebrew_info.txt")
    except OSError:
        pass

    return result

def get_brew_config(brew_config):

    config = {}

    # Process config
    for line in open(brew_config).readlines():

        config['homebrew_git_config_file'] = brew_config
        if "analyticsdisabled = true" in line :
            config["homebrew_noanalytics_this_run"] = "1"
            break
        if "analyticsdisabled = false" in line :
            config["homebrew_noanalytics_this_run"] = "0"
            break
        # elif "  analyticsmessage = " in line:
        #     config["ringer"] = line.replace("   analyticsmessage = ", ")").strip()
        # elif "  caskanalyticsmessage = " in line:
        #     config["ringer"] = line.replace("   caskanalyticsmessage = ", ")").strip()
        # elif "  donationmessage = " in line:
        #     config["ringer"] = line.replace("   donationmessage = ", ")").strip()
        # elif "  influxanalyticsmessage = " in line:
        #     config["ringer"] = line.replace("   influxanalyticsmessage = ", ")").strip()
    return config

def main():
    """Main"""

    # Remove old homebrew_info.sh script, if it exists
    if os.path.isfile(os.path.dirname(os.path.realpath(__file__))+'/homebrew_info.sh'):
        os.remove(os.path.dirname(os.path.realpath(__file__))+'/homebrew_info.sh')

    # Check if homebrew exists
    if os.path.isfile('/usr/local/bin/brew'):
        # If Intel Mac
        brew="/usr/local/bin/brew"
        result = get_brew_info(brew)
        result.update(get_brew_config('/usr/local/Homebrew/.git/config'))
    elif os.path.isfile('/opt/homebrew/bin/brew'):
        # Else if Apple Silicon Mac
        brew="/opt/homebrew/bin/brew"
        result = get_brew_info(brew)
        result.update(get_brew_config('/opt/homebrew/.git/config'))
    else:
        # We have no brew installed
        print("Homebrew is not installed, skipping")
        result = []

    # Write homebrew_info results to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'homebrew_info.plist')
    try:
        plistlib.writePlist(result, output_plist)
    except:
        with open(output_plist, 'wb') as fp:
            plistlib.dump(result, fp, fmt=plistlib.FMT_XML)

if __name__ == "__main__":
    main()
