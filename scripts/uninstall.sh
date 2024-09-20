#!/bin/bash

# Remove homebrew_info scripts
rm -f "${MUNKIPATH}preflight.d/homebrew_info.sh"
rm -f "${MUNKIPATH}preflight.d/homebrew_info.py"

# Remove homebrew_info.json and .plist files
rm -f "${CACHEPATH}homebrew_info.json"
rm -f "${CACHEPATH}homebrew_info.plist"
