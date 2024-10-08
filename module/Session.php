<?php


namespace Module;


/**
 * SessionHandler Class
 *
 * A utility class for managing user sessions. 
 * Provides methods to initialize, retrieve, and destroy sessions.
 * Ideal for handling user authentication, session data, and session lifecycle management.
 * 
 */
class Session {

    public function __construct() {
    }

    public function initialize(): void {
        session_start();
    }

    public function destroy(): void {
        session_destroy();

        // Remove PHPSESSID cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, 
                $params["path"], 
                $params["domain"], 
                $params["secure"], 
                $params["httponly"]
            );
        }
        //Clear all values
        $_SESSION = [];
    }

    public function get($key): mixed {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public function set($key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

}