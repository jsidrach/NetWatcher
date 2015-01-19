#!/bin/bash
# Commands
while [[ $# > 0 ]]
  do
    key="$1"
    shift
    case $key in
      -h|--help)
      echo './script/build.sh [-d|--doc] [-u|--upgrade] [-i|--install] [-c|--check] [-p|--permissions] [-u|--update] [-r|--clean] [-b|--backup] [-a|--all]'
      shift
      ;;
      -d|--doc)
      ./scripts/gen_doc.sh
      shift
      ;;
      -u|--upgrade)
      ./scripts/upgrade.sh
      shift
      ;;
      -i|--install)
      sudo apt-get install php5-common libapache2-mod-php5 php5-cli php5-xsl GraphViz curl gettext poedit git
      shift
      ;;
      -c|--check)
      ./scripts/check_php.sh
      shift
      ;;
      -p|--permissions)
      ./scripts/do_chmod.sh
      shift
      ;;
      -u|--update)
      ./lib/vendor/composer.phar update
      shift
      ;;
      -r|--clear)
      rm -rf log/*
      rm -rf docs/*
      shift
      ;;
      -b|--backup)
      ./scripts/backup.sh
      shift
      ;;
      -a|--all)
      rm -rf docs/api/*
      ./scripts/do_chmod.sh
      ./scripts/gen_doc.sh
      ./scripts/check_php.sh
      shift
      ;;
      *)
        shift
      ;;
    esac
  done
exit 1
