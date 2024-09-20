#!/bin/bash

# homebrew_info controller
CTL="${BASEURL}index.php?/module/homebrew_info/"

# Get the scripts in the proper directories
"${CURL[@]}" "${CTL}get_script/homebrew_info.py" -o "${MUNKIPATH}preflight.d/homebrew_info.py"

# Check exit status of curl
if [ $? = 0 ]; then
	# Make executable
	chmod a+x "${MUNKIPATH}preflight.d/homebrew_info.py"

	# Set preference to include this file in the preflight check
	setreportpref "homebrew_info" "${CACHEPATH}homebrew_info.plist"

else
	echo "Failed to download all required components!"
	rm -f "${MUNKIPATH}preflight.d/homebrew_info.py"

	# Signal that we had an error
	ERR=1
fi


