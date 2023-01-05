<?php

class DetailsDao
{
    // Hold the class instance.
    private static ?DetailsDao $instance = null;

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
     * Create a new details in the database.
     *
     * @param DetailsCommande $details The details to create.
     */
    public function create(DetailsCommande $details): void
    {
        $query = "INSERT INTO details_commande (num_commande, ref_produit, qte_commandee) VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $details->getCommande()->getNumCommande(),
            $details->getProduit()->getRefProduit(),
            $details->getQteCommande()
        ]);
    }

    /**
     * Get a details by order number and product reference.
     *
     * @param int $num_commande The order number of the details.
     * @param int $ref_produit The product reference of the details.
     * @return DetailsCommande|null The details, or null if not found.
     */
    public function getByNumCommandeAndRefProduit(int $num_commande, int $ref_produit): ?DetailsCommande
    {
        $query = "SELECT * FROM details_commande WHERE num_commande = ? AND ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$num_commande, $ref_produit]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $details = DetailsCommande::newInstance()
                ->setQteCommande($result['qte_commandee'])
                ->setCommande(CommandeDao::getInstance()->getById($result['num_commande']))
                ->setProduit(ProductDao::getInstance()->getById($result['ref_produit']));

            return $details;
        }

        return null;
    }

    /**
     * Get all details by order number.
     *
     * @param int $num_commande The order number of the details.
     * @return DetailsCommande[]|null The details, or null if not found.
     */

    public function getAllByCommande(int $num_commande): ?array
    {
        $query = "SELECT * FROM details_commande WHERE num_commande = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$num_commande]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $details = [];
            foreach ($result as $row) {
                $details[] = DetailsCommande::newInstance()
                    ->setQteCommande($row['qte_commandee'])
                    ->setCommande(CommandeDao::getInstance()->getById($row['num_commande']))
                    ->setProduit(ProductDao::getInstance()->getById($row['ref_produit']));
            }

            return $details;
        }

        return null;
    }

    /**
     * Get all details by product reference.
     *
     * @param int $ref_produit The product reference of the details.
     * @return DetailsCommande[]|null The details, or null if not found.
     */
    public function getAllByProduit(int $ref_produit): ?array
    {
        $query = "SELECT * FROM details_commande WHERE ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$ref_produit]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $details = [];
            foreach ($result as $row) {
                $details[] = DetailsCommande::newInstance()
                    ->setQteCommande($row['qte_commandee'])
                    ->setCommande(CommandeDao::getInstance()->getById($row['num_commande']))
                    ->setProduit(ProductDao::getInstance()->getById($row['ref_produit']));
            }

            return $details;
        }

        return null;
    }

    /**
     * Get all details.
     *
     * @return DetailsCommande[]|null The details, or null if not found.
     */
    public function getAll(): ?array
    {
        $query = "SELECT * FROM details_commande";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            $details = [];
            foreach ($result as $row) {
                $details[] = DetailsCommande::newInstance()
                    ->setQteCommande($row['qte_commandee'])
                    ->setCommande(CommandeDao::getInstance()->getById($row['num_commande']))
                    ->setProduit(ProductDao::getInstance()->getById($row['ref_produit']));
            }

            return $details;
        }

        return null;
    }

    /**
     * Update a details in the database.
     *
     * @param DetailsCommande $details The details to update.
     */
    public function update(DetailsCommande $details): void
    {
        $query = "UPDATE details_commande SET qte_commandee = ? WHERE num_commande = ? AND ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $details->getQteCommande(),
            $details->getCommande()->getNumCommande(),
            $details->getProduit()->getRefProduit()
        ]);
    }

    /**
     * Delete a details in the database.
     *
     * @param DetailsCommande $details The details to delete.
     */
    public function delete(DetailsCommande $details): void
    {
        $query = "DELETE FROM details_commande WHERE num_commande = ? AND ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $details->getCommande()->getNumCommande(),
            $details->getProduit()->getRefProduit()
        ]);
    }

    /**
     * Delete all details by order number in the database.
     *
     * @param int $num_commande The order number of the details.
     */
    public function deleteAllByNumCommande(int $num_commande): void
    {
        $query = "DELETE FROM details_commande WHERE num_commande = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$num_commande]);
    }

}