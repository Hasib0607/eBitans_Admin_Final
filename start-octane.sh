#!/bin/bash

cd /home/ebitans/admin.ebitans.com  # Laravel root
fuser -k 8000/tcp 2>/dev/null       # Kill old process
nohup php artisan octane:start --server=swoole --host=127.0.0.1 --port=8000 > storage/logs/octane.log 2>&1 &
