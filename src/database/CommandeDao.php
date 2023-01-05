<?php

class CommandeDao
{
    // Hold the class instance.
    private static ?CommandeDao $instance = null;

    // The database connection.
    private PDO $conn;

    // The constructor is private to prevent direct creation of object.
    private function __construct()
    {
        $connection = DBConnection::getInstance();
        $this->conn = $connection->getConnection();
    }

    // The object is created from within the class itself only
    // if the class has no instance.
    public static function getInstance(): static
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Create a new commande in the database.
     *
     * @param Commande $commande The commande to create.
     * @return int The ID of the created commande.
     */
    public function create(Commande $commande): int
    {
        $query = "INSERT INTO commande (num_client, date_commande) VALUES (?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $commande->getClient()->getNumClient(),
            $commande->getDateCommande()
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * Get a commande by its ID.
     *
     * @param int $id The ID of the commande.
     * @return Commande|null The commande, or null if not found.
     */
    public function getById(int $id): ?Commande
    {
        $query = "SELECT * FROM commande WHERE num_commande = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $clientDao = ClientDao::getInstance();
            $client = $clientDao->getById($result['num_client']);

            try {
                return Commande::newInstance()
                    ->setNumCommande($result['num_commande'])
                    ->setClient($client)
                    ->setDateCommande(new DateTime($result['date_commande']));
            } catch (Exception $e) {
                print_r($e->getTraceAsString());
            }
        }

        return null;
    }

    /**
     * Get all commandes.
     *
     * @return Commande[] The commandes.
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM commande";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $commandes = [];
        $clientDao = ClientDao::getInstance();

        foreach ($results as $result) {
            $client = $clientDao->getById($result['num_client']);

            try {
                $commandes[] = Commande::newInstance()
                    ->setNumCommande($result['num_commande'])
                    ->setClient($client)
                    ->setDateCommande(new DateTime($result['date_commande']));
            } catch (Exception $e) {
                print_r($e->getTraceAsString());
            }
        }

        return $commandes;
    }

    /**
     * Update a commande in the database.
     *
     * @param Commande $commande The commande to update.
     */
    public function update(Commande $commande): void
    {
        $query = "UPDATE commande SET num_client = ?, date_commande = ? WHERE num_commande = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $commande->getClient()->getNumClient(),
            $commande->getDateCommande(),
            $commande->getNumCommande()
        ]);
    }

    /**
     * Delete a commande from the database.
     *
     * @param int $id The ID of the commande to delete.
     */
    public function delete(int $id): void
    {
        $query = "DELETE FROM commande WHERE num_commande = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
    }

    /**
     * Get the number of commandes.
     *
     * @return int The number of commandes.
     */
    public function count(): int
    {
        $query = "SELECT COUNT(*) FROM commande";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Get the number of commandes for a client.
     *
     * @param int $id The ID of the client.
     * @return int The number of commandes.
     */
    public function countByClient(int $id): int
    {
        $query = "SELECT COUNT(*) FROM commande WHERE num_client = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt->fetchColumn();
    }

    /**
     * Get the commandes of a client.
     *
     * @param int $id The ID of the client.
     * @return Commande[] The commandes.
     */
    public function getByClient(int $id): array
    {
        $query = "SELECT * FROM commande WHERE num_client = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $commandes = [];
        $clientDao = ClientDao::getInstance();

        foreach ($results as $result) {
            $client = $clientDao->getById($result['num_client']);

            try {
                $commandes[] = Commande::newInstance()
                    ->setNumCommande($result['num_commande'])
                    ->setClient($client)
                    ->setDateCommande(new DateTime($result['date_commande']));
            } catch (Exception $e) {
                print_r($e->getTraceAsString());
            }
        }

        return $commandes;
    }
}