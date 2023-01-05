<?php

class ClientDAO
{
    // Hold the class instance.
    private static ?ClientDAO $instance = null;

    // The database connection.
    private PDO $conn;

    // The constructor is private to prevent direct creation of object.
    private function __construct()
    {
        $connection = DBConnection::getInstance();
        $this->conn = $connection->getConnection();
    }

    // The object is created from within the class itself only if the class has no instance.
    public static function getInstance(): static
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Create a new client in the database.
     *
     * @param Client $client The client to create.
     * @return int The ID of the created client.
     */
    public function create(Client $client): int
    {
        $query = "INSERT INTO client (nom, raison_social, adresse, ville, pays, telephone) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $client->getNom(),
            $client->getRaisonSociale(),
            $client->getAdresse(),
            $client->getVille(),
            $client->getPays(),
            $client->getTelephone()
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * Get a client by its ID.
     *
     * @param int $id The ID of the client.
     * @return Client|null The client, or null if not found.
     */

    public function getById(int $id): ?Client
    {
        $query = "SELECT * FROM client WHERE num_client = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result == null) {
            return null;
        }

        if ($result) {
            $client = Client::newInstance();
            $client
                ->setNumClient($result['num_client'])
                ->setNom($result['nom'])
                ->setRaisonSociale($result['raison_social'])
                ->setAdresse($result['adresse'])
                ->setVille($result['ville'])
                ->setPays($result['pays'])
                ->setTelephone($result['telephone']);


            return $client;
        }

        return null;
    }

    /**
     * Get all clients.
     *
     * @return Client[] The clients.
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM client";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];

        foreach ($result as $row) {
            $client = Client::newInstance();
            $client
                ->setNumClient($row['num_client'])
                ->setNom($row['nom'])
                ->setRaisonSociale($row['raison_social'])
                ->setAdresse($row['adresse'])
                ->setVille($row['ville'])
                ->setPays($row['pays'])
                ->setTelephone($row['telephone']);


            $clients[] = $client;
        }

        return $clients;
    }

    /**
     * get clients by a random criteria
     * @param string $criteria
     * @return Client[] The clients.
     */
    public function getByCriteria(string $criteria): array
    {
        $query = "SELECT * FROM client WHERE num_client = ? OR nom LIKE ? OR raison_social LIKE ? OR adresse LIKE ? OR ville LIKE ? OR pays LIKE ? OR telephone LIKE ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute(["%$criteria%", "%$criteria%", "%$criteria%", "%$criteria%", "%$criteria%", "%$criteria%", "%$criteria%"]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];

        foreach ($result as $row) {
            $client = Client::newInstance();
            $client
                ->setNumClient($row['num_client'])
                ->setNom($row['nom'])
                ->setRaisonSociale($row['raison_social'])
                ->setAdresse($row['adresse'])
                ->setVille($row['ville'])
                ->setPays($row['pays'])
                ->setTelephone($row['telephone']);

            $clients[] = $client;

        }

        return $clients;

    }

    /**
     * Update a client in the database.
     *
     * @param Client $client The client to update.
     * @return bool True if the client was updated, false otherwise.
     */
    public function update(Client $client): bool {
        $query = "UPDATE client SET nom = ?, raison_social = ?, adresse = ?, ville = ?, pays = ?, telephone = ? WHERE num_client = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $client->getNom(),
            $client->getRaisonSociale(),
            $client->getAdresse(),
            $client->getVille(),
            $client->getPays(),
            $client->getTelephone(),
            $client->getNumClient()
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a client from the database.
     *
     * @param int $id The ID of the client to delete.
     * @return bool True if the client was deleted, false otherwise.
     */
    public function delete(int $id): bool
    {
        $query = "DELETE FROM client WHERE num_client = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }


}