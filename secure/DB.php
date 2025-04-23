
    <?php 
    // DB.php
    
    // Include configuration file for database credentials
    require_once('config.php');

    try {
        $db = DB::getInstance();
        echo "Database connected successfully!";
    } catch (Exception $e) {
        echo "Database connection failed: " . $e->getMessage();
    }
    
    class DB {
        private static $instance = null;
        private $connection;
    
        private function __construct() {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            }
        }
    
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new DB();
            }
            return self::$instance->connection;
        }
    
        public function query($sql) {
            return $this->connection->query($sql);
        }
    
        public function prepare($sql) {
            return $this->connection->prepare($sql);
        }
    }
    