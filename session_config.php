<?php
class DB_Session_Handler implements SessionHandlerInterface {
    private $pdo;
    private $lockTimeout = 5; // seconds

    public function __construct() {
        try {
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=herts2u_auth',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true
                ]
            );

            $this->verifySessionTable();
        } catch (PDOException $e) {
            error_log("SESSION INIT FAILED: " . $e->getMessage());
            throw new RuntimeException("Database connection failed.");
        }
    }

    private function verifySessionTable() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS sessions_config (
                session_id VARCHAR(128) NOT NULL PRIMARY KEY,
                data TEXT NOT NULL,
                last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                session_lifetime INT NOT NULL DEFAULT 86400,
                INDEX (last_activity)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function open($savePath, $sessionName): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string {
        try {
            $stmt = $this->pdo->prepare(
                "SELECT data FROM sessions_config WHERE session_id = ?"
            );
            $stmt->execute([$id]);
            $result = $stmt->fetch();
            return $result['data'] ?? '';
        } catch (PDOException $e) {
            error_log("SESSION READ ERROR: " . $e->getMessage());
            return '';
        }
    }

    public function write($id, $data): bool {
        try {
            $stmt = $this->pdo->prepare("
                REPLACE INTO sessions_config 
                (session_id, data, last_activity, session_lifetime) 
                VALUES (?, ?, NOW(), ?)
            ");
            return $stmt->execute([
                $id, 
                $data, 
                ini_get('session.gc_maxlifetime')
            ]);
        } catch (PDOException $e) {
            error_log("SESSION WRITE ERROR: " . $e->getMessage());
            return false;
        }
    }

    public function destroy($id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions_config WHERE session_id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("SESSION DESTROY ERROR: " . $e->getMessage());
            return false;
        }
    }

    public function gc($max_lifetime): int|false {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM sessions_config 
                WHERE last_activity < DATE_SUB(NOW(), INTERVAL ? SECOND)
            ");
            $stmt->execute([$max_lifetime]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("SESSION GC ERROR: " . $e->getMessage());
            return false;
        }
    }
}


// setup session

ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('UTC');

// Secure session config
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 86400); // 1 day
ini_set('session.cookie_lifetime', 86400);
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_name('HERTS2U_SESS');

// use session handler
$handler = new DB_Session_Handler();
session_set_save_handler($handler, true);
register_shutdown_function('session_write_close');

// validate session
function validateSession() {
    if (!isset($_SESSION['_validated'])) {
        session_regenerate_id(true);
        $_SESSION['_validated'] = true;
        $_SESSION['_created'] = time();
        $_SESSION['_ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['_ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return;
    }

    if ($_SESSION['_ip'] !== ($_SERVER['REMOTE_ADDR'] ?? '') ||
        $_SESSION['_ua'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')
    ) {
        session_destroy();
        throw new RuntimeException('Session security check failed');
    }

    if (time() - ($_SESSION['_created'] ?? 0) > 1800) {
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }
}

// start session
try {
    session_start();
    validateSession();
} catch (Exception $e) {
    error_log("SESSION ERROR: " . $e->getMessage());
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
    $_SESSION = [];
    validateSession();
}
?>
