console() {
   php bin/console "$@"
}

start() {
    symfony server:start "$@"
}

stop() {
    symfony server:stop
}
