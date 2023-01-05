<?php

class ProductDao
{
    // Hold the class instance.
    private static $instance = null;

    // The database connection.
    private PDO $conn;

    // The constructor is private to prevent direct creation of object.
    private function __construct()
    {
        $connection = DBConnection::getInstance();
        $this->conn = $connection->getConnection();
    }

    // The object is created from within the class itself only if the class has no instance.
    public static function getInstance() : static
    {
        if (self::$instance == null)
        {
            self::$instance = new ProductDao();
        }

        return self::$instance;
    }

    /**
     * Create a new product in the database.
     *
     * @param Product $product The product to create.
     * @return int The ID of the created product.
     */
    public function create(Product $product) : int
    {
        $query = "INSERT INTO produit (nom, prix_unitaire, qte_stockee, indisponible) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $product->getNom(),
            $product->getPrixUnitaire(),
            $product->getQteStockee(),
            $product->isIndisponible() ? 1 : 0
        ]);

        return $this->conn->lastInsertId();
    }

    /**
     * Get a product by its ID.
     *
     * @param int $id The ID of the product.
     * @return Product|null The product, or null if not found.
     */
    public function getById(int $id) : ?Product
    {
        $query = "SELECT * FROM produit WHERE ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        $result = $stmt->fetch();

        if ($result) {
            $product = Product::newInstance();
            $product
                ->setRefProduit($result['ref_produit'])
                ->setNom($result['nom'])
                ->setPrixUnitaire($result['prix_unitaire'])
                ->setQteStockee($result['qte_stockee'])
                ->setIndisponible($result['indisponible'] == 1);

            return $product;
        }

        return null;
    }

    /**
     * Get all products in the database.
     *
     * @return Product[] The products.
     */
    public function getAll() : array {
        $query = "SELECT * FROM produit";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $products = [];

        foreach ($result as $row) {
            $product = Product::newInstance();
            $product
                ->setRefProduit($row['ref_produit'])
                ->setNom($row['nom'])
                ->setPrixUnitaire($row['prix_unitaire'])
                ->setQteStockee($row['qte_stockee'])
                ->setIndisponible($row['indisponible'] == 1);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * get products by a random criteria
     * @param string $criteria
     * @return Product[] the products
     */
    public function getByCriteria(string $criteria) : array {
        $query = "SELECT * FROM produit WHERE ref_produit = ? OR nom LIKE ? OR prix_unitaire LIKE ? OR qte_stockee LIKE ? OR indisponible LIKE ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$criteria, "%$criteria%", "%$criteria%", "%$criteria%", "%$criteria%"]);

        $result = $stmt->fetchAll();

        $products = [];

        foreach ($result as $row) {
            $product = Product::newInstance();
            $product
                ->setRefProduit($row['ref_produit'])
                ->setNom($row['nom'])
                ->setPrixUnitaire($row['prix_unitaire'])
                ->setQteStockee($row['qte_stockee'])
                ->setIndisponible($row['indisponible'] == 1);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * get all product where an expression is true
     * @param string $expression
     * @return Product[] the products
     */
    public function getByExpression(string $expression) : array {
        $expression = self::translateToSql($expression);

        $query = "SELECT * FROM produit WHERE $expression";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        $products = [];

        foreach ($result as $row) {
            $product = Product::newInstance();
            $product
                ->setRefProduit($row['ref_produit'])
                ->setNom($row['nom'])
                ->setPrixUnitaire($row['prix_unitaire'])
                ->setQteStockee($row['qte_stockee'])
                ->setIndisponible($row['indisponible'] == 1);

            $products[] = $product;
        }

        return $products;
    }

    /**
     * translate expression to sql
     * @param string $expression
     * @return string the sql expression
     */
    private static function translateToSql(string $expression) : string {
        $dict = [
            '$prix'     =>  "prix_unitaire",
            '$qte'      =>  "qte_stockee",
            '$quantiée' =>  "qte_stockee",
            '&&'        =>  "AND",
            '&'         =>  "AND",
            '||'        =>  "OR",
            '|'         =>  "OR",
            '=='        =>  "=",
            '!='        =>  "<>",
            '!'         =>  "NOT "
        ];

        $pattern = '/(?:express\()(.+)(?:\))/';

        preg_match($pattern, $expression, $expression);

        $expression = $expression[1];

        return str_replace(array_keys($dict), array_values($dict), strtolower($expression));
    }





    /**
     * Update a product in the database.
     *
     * @param Product $product The product to update.
     * @return bool True if the product was updated, false otherwise.
     */
    public function update(Product $product): bool
    {
        $query = "UPDATE produit SET nom = ?, prix_unitaire = ?, qte_stockee = ?, indisponible = ? WHERE ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            $product->getNom(),
            $product->getPrixUnitaire(),
            $product->getQteStockee(),
            $product->isIndisponible() ? 1 : 0,
            $product->getRefProduit()
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a product from the database.
     *
     * @param int $id The ID of the product to delete.
     * @return bool True if the client was deleted, false otherwise.
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM produit WHERE ref_produit = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0;
    }

}