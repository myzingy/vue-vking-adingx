#!/bin/bash
delayed_ajax() {
  local url=$1
  local callback=$2
  /usr/bin/curl -s "$url" | "$callback"
}

my_handler() {
  # Read from stdin and do something.
  # E.g. just append to a file:
  cat > /dev/null 2>&1
  #cat >> /tmp/some_file.txt
}

for delay in {1..4}; do
  delayed_ajax http://127.0.0.1/facebook/wwwroot/apido/cron.demon my_handler &
  sleep 15
done
