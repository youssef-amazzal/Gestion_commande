<?php

class Product extends Table
{
    public static string    $TableName = PRODUCT;
    private int             $ref_produit;
    private String          $nom;
    private String          $prix_unitaire;
    private int             $qte_stockee;
    private bool            $indisponible;

    private function __construct() {}

    public static function newInstance() {
        return new static();
    }


    protected static function getAddForm(): string
    {
        if (isset($_GET['action']) && $_GET['action'] == 'add') {
            return file_get_contents(__DIR__ . "/../../public/assets/templates/add_product_form.html");
        } else {
            return '';
        }
    }

    protected function getEditForm(): string
    {
        if (isset($_GET['ref_produit']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
            $form = file_get_contents(__DIR__ . "/../../public/assets/templates/edit_product_form.html");
            $vars = [
                "{{ref_produit}}"   => $this->getRefProduit(),
                "{{nom}}"           => $this->getNom(),
                "{{prix_unitaire}}" => $this->getPrixUnitaire(),
                "{{qte_stockee}}"   => $this->getQteStockee(),
                "{{indisponible}}"  => $this->isIndisponible() ? "Oui" : "non"
            ];

            return str_replace(array_keys($vars), array_values($vars), $form);
        } else {
            return '';
        }
    }

    protected function getDeleteForm(): string
    {
        if (isset($_GET['ref_produit']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
            $form = file_get_contents(__DIR__ . "/../../public/assets/templates/delete_product_form.html");
            $vars = [
                "{{ref_produit}}"   => $this->getRefProduit(),
                "{{nom}}"           => $this->getNom()
            ];

            return str_replace(array_keys($vars), array_values($vars), $form);
        } else {
            return '';
        }
    }

    protected function getTableRow(): string
    {
        return "
            <tr>
                <td>{$this->getNom()}</td>
                <td>{$this->getPrixUnitaire()}</td>
                <td>{$this->getQteStockee()}</td>
                <td class='actions'>
                
                    <form action='index.php' method='get'>
                        <input type='hidden' name='table' value='{$this::$TableName}'>
                        <input type='hidden' name='ref_produit' value='{$this->getRefProduit()}'>
                        <input type='hidden' name='action' value='edit'>
                        <button type='submit' class='button edit'>
                            <i class='bx bxs-edit'></i>
                            <span class='text'>Modifier</span>
                        </button>
                    </form>
                    
                    <form action='index.php' method='get'>
                        <input type='hidden' name='table' value='{$this::$TableName}'>
                        <input type='hidden' name='ref_produit' value='{$this->getRefProduit()}'>
                        <input type='hidden' name='action' value='delete'>
                        <button type='submit' class='button delete'>
                            <i class='bx bxs-trash'></i>
                            <span class='text'>Supprimer</span>
                        </button>
                    </form> 
                </td>           
            </tr>
        ";
    }

    protected static function getTableHeader(): string
    {
        return "<colgroup>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:10%'>
                </colgroup>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>";
    }

    protected static function getTableBody($entities): string
    {
        $body = "<tbody>";
        foreach ($entities as $product) {
            $body .= $product->getTableRow();
        }
        $body .= "</tbody>";
        return $body;
    }

    protected static function getTable($entities): string
    {
        if (count($entities) == 0) {
            return "<p>Aucun produit trouvé</p>";
        }

        $table = "<table>";
        $table .= self::getTableHeader();
        $table .= self::getTableBody($entities);
        $table .= "</table>";

        return $table;
    }

    public static function getPage(): string
    {
        global $productDAO;

        $search = '';
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $pattern = '/(?:express\()(.+)(?:\))/';
            if (preg_match($pattern, "$search")) {
                $products = $productDAO->getByExpression($search);
            }
            else {
                $products = $productDAO->getByCriteria($search);
            }
        } else {
            $products = $productDAO->getAll();
        }

        if (isset($_GET['ref_produit']) && !empty($_GET['ref_produit']) && isset($_GET['action'])) {
            $selectedProduct = $productDAO->getById(intval($_GET['ref_produit']));
        } else {
            $selectedProduct = null;
        }

        $table_rows = static::getTable($products);

        $vars = [
            '{{table_name}}'            => 'Produits',
            '{{form_action}}'           => 'index.php',
            '{{form_method}}'           => 'get',
            '{{table_query}}'           => static::$TableName,
            '{{table_rows}}'            => $table_rows,
            '{{search_query}}'          => $search,
            '{{Product_activation}}'    => 'active',
            '{{add_form}}'              => static::getAddForm(),
            '{{edit_form}}'             => $selectedProduct ? $selectedProduct->getEditForm() : '',
            '{{delete_form}}'           => $selectedProduct ? $selectedProduct->getDeleteForm() : ''
        ];

        return strtr(TEMPLATE, $vars);
    }




    // Getters and Setters


    /**
     * @return int
     */
    public function getRefProduit(): int
    {
        return $this->ref_produit;
    }

    /**
     * @param int $ref_produit
     * @return Product
     */
    public function setRefProduit(int $ref_produit): Product
    {
        $this->ref_produit = $ref_produit;
        return $this;
    }

    /**
     * @return String
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param String $nom
     * @return Product
     */
    public function setNom(string $nom): Product
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return String
     */
    public function getPrixUnitaire(): string
    {
        return $this->prix_unitaire;
    }

    /**
     * @param String $prix_unitaire
     * @return Product
     */
    public function setPrixUnitaire(string $prix_unitaire): Product
    {
        $this->prix_unitaire = $prix_unitaire;
        return $this;
    }

    /**
     * @return int
     */
    public function getQteStockee(): int
    {
        return $this->qte_stockee;
    }

    /**
     * @param int $qte_stockee
     * @return Product
     */
    public function setQteStockee(int $qte_stockee): Product
    {
        $this->qte_stockee = $qte_stockee;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIndisponible(): bool
    {
        return $this->indisponible;
    }

    /**
     * @param bool $indisponible
     * @return Product
     */
    public function setIndisponible(bool $indisponible): Product
    {
        $this->indisponible = $indisponible;
        return $this;
    }

}