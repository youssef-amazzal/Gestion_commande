<?php

class Commande extends Table
{
    private int         $num_commande;
    private DateTime    $date_commande;
    private Client      $client;

    private function __construct() {}

    public static function newInstance(): static
    {
        return new static();
    }

    protected function getTableRow(): string
    {
        global $detailsDAO;
        $details = $detailsDAO->getAllByCommande($this->getNumCommande());
        $details_data = '';
        $somme = 0;
        foreach ($details as $detail) {
            $details_data .= $detail->getProduit()->getNom();
            $details_data .= " ( &times; ";
            $details_data .= $detail->getQteCommande();
            $details_data .= ' units)<br>';
            $somme += $detail->getProduit()->getPrixUnitaire() * $detail->getQteCommande();
        }

        return "<tr>
                    <td class='text'>{$this->getNumCommande()}</td>
                    <td class='text'>{$this->getDateCommande()->format('Y-m-d')}</td>
                    <td class='text'>{$this->getClient()->getNom()}</td>
                    <td class='text'>$details_data</td>
                    <td class='text'>{$somme} Dh</td>
                </tr>";
    }

    protected static function getTableHeader(): string
    {
        return "<thead>
                    <tr>
                        <th class='text'>No°</th>
                        <th class='text'>Date</th>
                        <th class='text'>Client</th>
                        <th class='text'>Details</th>
                        <th class='text'>Prix total</th>
                    </tr>
                </thead>";
    }

    protected static function getTableBody(array $entities): string
    {
        $body = "<tbody>";

        foreach ($entities as $entity) {
            $body .= $entity->getTableRow();
        }

        $body .= "</tbody>";

        return $body;
    }

    protected static function getTable(array $entities): string
    {
        if (count($entities) == 0) {
            return "<p>Aucune commande trouvée</p>";
        }

        $table = "<table>";
        $table .= self::getTableHeader();
        $table .= self::getTableBody($entities);
        $table .= "</table>";

        return $table;
    }

    public static function getPage(): string
    {
        // TODO: Implement getPage() method.
        return "";
    }





    //-------------------- GETTERS & SETTERS --------------------//

    /**
     * @return int
     */
    public function getNumCommande(): int
    {
        return $this->num_commande;
    }

    /**
     * @param int $num_commande
     * @return commande
     */
    public function setNumCommande(int $num_commande): commande
    {
        $this->num_commande = $num_commande;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateCommande(): DateTime
    {
        return $this->date_commande;
    }

    /**
     * @param DateTime $date_commande
     * @return commande
     */
    public function setDateCommande(DateTime $date_commande): commande
    {
        $this->date_commande = $date_commande;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return commande
     */
    public function setClient(Client $client): commande
    {
        $this->client = $client;
        return $this;
    }

    static protected function getAddForm(): string
    {
        // TODO: Implement getAddForm() method.
        return "";
    }

    protected function getEditForm(): string
    {
        // TODO: Implement getEditForm() method.
        return "";
    }

    protected function getDeleteForm(): string
    {
        // TODO: Implement getDeleteForm() method.
        return "";
    }
}