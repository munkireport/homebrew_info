Homebrew Info module
==============

Gathers and returns information on the client's Homebrew installation. It does not collect information on installed bottles/packages. It can be installed separately from the `homebrew` module.

This module requires Homebrew to be installed [https://brew.sh/](https://brew.sh/)

Table Schema
-----
* core_tap_head - varchar(255) - Hash of the core tap head
* core_tap_origin - varchar(255) - URI of the core tap origin
* core_tap_last_commit - varchar(255) - Static local time of when brew last checked for updates
* head - varchar(255) - Hash of the head
* last_commit - varchar(255) - When brew itself was last updated
* origin - varchar(255) - Git repo used for origin pulls
* homebrew_bottle_domain - varchar(255) - Domain used for making bottles
* homebrew_cellar - varchar(255) - Path to cellar
* homebrew_prefix - varchar(255) - Homebrew prefix
* homebrew_repository - varchar(255) - Path to Homebrew repository
* homebrew_version - varchar(255) - Version of Homebrew currently installed
* homebrew_ruby - varchar(255) - Version of Ruby currently used by Homebrew
* command_line_tools - varchar(255) - Command line tools version
* cpu - varchar(255) - Quick general information about the CPU
* git - varchar(255) - Version and path of Git used by brew
* clang - varchar(255) - Clang version
* java - varchar(255) - Java path and version
* perl - varchar(255) - Path to perl
* python - varchar(255) - Path to Python
* ruby - varchar(255) - Path to Ruby
* x11 - varchar(255) - Version of X11 installed
* xcode - varchar(255) - Xcode version
* macos - varchar(255) - macOS version and type detected by Homebrew
* core_cask_tap - varchar(255) - Cask tap URL
* homebrew_cask_opts - varchar(255) - Homebrew cask options
* homebrew_make_jobs - varchar(255) - Homebrew make jobs count
* rosetta_2 - varchar(255) - Using Rosetta 2


