<?php
// Configuración de la base de datos para SQL Server en Azure
class Database {
    private $serverName = "tcp:snarf.database.windows.net,1433"; // Cambia <tu-servidor> por el nombre de tu servidor
    private $database = "taller"; // Cambia <tu-base-de-datos> por el nombre de tu base de datos
    private $username = "rafaelito"; // Cambia <tu-usuario> por tu usuario de base de datos
    private $password = "Rafaelo10"; // Cambia <tu-contraseña> por tu contraseña de base de datos
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO("sqlsrv:server=$this->serverName;Database=$this->database", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function disconnect() {
        $this->conn = null;
    }
}
?>