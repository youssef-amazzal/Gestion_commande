<?php

class Client extends Table {

    public static string    $TableName = CLIENT;
    private int             $num_client;
    private String          $nom;
    private String          $raison_sociale;
    private String          $adresse;
    private String          $ville;
    private String          $pays;
    private String          $telephone;

    private function __construct() {}

    public static function newInstance() {
        return new static();
    }

    protected static function getAddForm(): string
    {
        if (isset($_GET['action']) && $_GET['action'] == 'add') {
            return file_get_contents(__DIR__."/../../public/assets/templates/add_client_form.html");
        } else {
            return '';
        }
    }

    protected function getEditForm(): string
    {
        if (isset($_GET['num_client']) && isset($_GET['action']) && $_GET['action'] == 'edit') {
            $form = file_get_contents(__DIR__ . "/../../public/assets/templates/edit_client_form.html");
            $vars = [
                "{{num_client}}"    => $this->getNumClient(),
                "{{nom}}"           => $this->getNom(),
                "{{raison_sociale}}" => $this->getRaisonSociale(),
                "{{adresse}}"       => $this->getAdresse(),
                "{{ville}}"         => $this->getVille(),
                "{{pays}}"          => $this->getPays(),
                "{{telephone}}"     => $this->getTelephone()
            ];

            return str_replace(array_keys($vars), array_values($vars), $form);
        } else {
            return '';
        }
    }

    protected function getDeleteForm(): string
    {
        if (isset($_GET['num_client']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
            $form = file_get_contents(__DIR__ . "/../../public/assets/templates/delete_client_form.html");
            $vars = [
                "{{num_client}}"    => $this->getNumClient(),
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
                <td><a href='index.php?table=Client&num_client={$this->getNumClient()}&action=view'>{$this->getNumClient()}. {$this->getNom()}</a></td>
                <td>{$this->getRaisonSociale()}</td>
                <td>{$this->getVille()}</td>
                <td>{$this->getPays()}</td>
                <td>{$this->getTelephone()}</td>
                <td class='actions'>
                
                    <form action='index.php' method='get'>
                        <input type='hidden' name='table' value='{$this::$TableName}'>
                        <input type='hidden' name='num_client' value='{$this->getNumClient()}'>
                        <input type='hidden' name='action' value='edit'>
                        <button type='submit' class='button edit trigger'>
                            <i class='bx bxs-edit'></i>
                            <span class='text'>Modifier</span>
                        </button>
                    </form>
                    
                    <form action='index.php' method='get'>
                        <input type='hidden' name='table' value='{$this::$TableName}'>
                        <input type='hidden' name='num_client' value='{$this->getNumClient()}'>
                        <input type='hidden' name='action' value='delete'>
                        <button type='submit' class='button delete trigger'>
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
        return "
                <colgroup>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:auto'>
                    <col style='width:10%'>
                </colgroup>                    
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Raison sociale</th>
                        <th>Ville</th>
                        <th>Pays</th>
                        <th>Telephone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
        ";
    }

    protected static function getTableBody($entities): string
    {
        $body = "<tbody>";
        foreach ($entities as $client) {
            $body .= $client->getTableRow();
        }
        $body .= "</tbody>";

        return $body;
    }

    protected static function getTable($entities): string
    {
        if (count($entities) == 0) {
            return "<p>Aucun client trouvé</p>";
        }

        $table = "<table>";
        $table .= self::getTableHeader();
        $table .= self::getTableBody($entities);
        $table .= "</table>";

        return $table;
    }

    public static function getPage(): string
    {
        global $clientDAO;

        $search = '';
        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $clients = $clientDAO->getByCriteria($search);
        } else {
            $clients = $clientDAO->getAll();
        }

        if (isset($_GET['num_client']) && !empty($_GET['num_client']) && isset($_GET['action'])) {
            $selectedClient = $clientDAO->getById(intval($_GET['num_client']));
        } else {
            $selectedClient = null;
        }

        $table_rows = static::getTable($clients);

        $vars = [
            '{{table_name}}'        => 'Clients',
            '{{form_action}}'       => 'index.php',
            '{{form_method}}'       => 'get',
            '{{table_query}}'       => static::$TableName,
            '{{table_rows}}'        => $table_rows,
            '{{search_query}}'      => $search,
            '{{Client_activation}}' => 'active',
            '{{add_form}}'          => static::getAddForm(),
            '{{edit_form}}'         => $selectedClient ? $selectedClient->getEditForm() : '',
            '{{delete_form}}'       => $selectedClient ? $selectedClient->getDeleteForm() : '',
            '{{view_commandes_window}}' => $selectedClient ? $selectedClient->getCommandeWindow() : ''
        ];

        return strtr(TEMPLATE, $vars);
    }

    public function getCommandeWindow(): string
    {
        if (isset($_GET['num_client']) && isset($_GET['action']) && $_GET['action'] == 'view') {
            global $commandeDAO;
            $commandes = $commandeDAO->getByClient($this->getNumClient());
            $commandes_table = Commande::getTable($commandes);

            $commandeWindow = file_get_contents(__DIR__ . "/../../public/assets/templates/view_commandes_window.html");

            return str_replace('{{table_rows}}', $commandes_table, $commandeWindow);
        } else {
            return '';
        }
    }





    // Getters and Setters

    /**
     * @return int
     */
    public function getNumClient(): int
    {
        return $this->num_client;
    }

    /**
     * @param int $num_client
     * @return Client
     */
    public function setNumClient(int $num_client): Client
    {
        $this->num_client = $num_client;
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
     * @return Client
     */
    public function setNom(string $nom): Client
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return String
     */
    public function getRaisonSociale(): string
    {
        return $this->raison_sociale;
    }

    /**
     * @param String $raison_sociale
     * @return Client
     */
    public function setRaisonSociale(string $raison_sociale): Client
    {
        $this->raison_sociale = $raison_sociale;
        return $this;
    }

    /**
     * @return String
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param String $adresse
     * @return Client
     */
    public function setAdresse(string $adresse): Client
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return String
     */
    public function getVille(): string
    {
        return $this->ville;
    }

    /**
     * @param String $ville
     * @return Client
     */
    public function setVille(string $ville): Client
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @return String
     */
    public function getPays(): string
    {
        return $this->pays;
    }

    /**
     * @param String $pays
     * @return Client
     */
    public function setPays(string $pays): Client
    {
        $this->pays = $pays;
        return $this;
    }

    /**
     * @return String
     */
    public function getTelephone(): string
    {
        return $this->telephone;
    }

    /**
     * @param String $telephone
     * @return Client
     */
    public function setTelephone(string $telephone): Client
    {
        $this->telephone = $telephone;
        return $this;
    }




}