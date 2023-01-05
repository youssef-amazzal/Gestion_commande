<?php

class DetailsCommande
{
    private int         $qte_commande;
    private Commande    $commande;
    private Product     $produit;

    private function __construct() {}

    public static function newInstance() {
        return new static();
    }

    /**
     * @return int
     */
    public function getQteCommande(): int
    {
        return $this->qte_commande;
    }

    /**
     * @param int $qte_commande
     * @return DetailsCommande
     */
    public function setQteCommande(int $qte_commande): DetailsCommande
    {
        $this->qte_commande = $qte_commande;
        return $this;
    }

    /**
     * @return Commande
     */
    public function getCommande(): Commande
    {
        return $this->commande;
    }

    /**
     * @param Commande $commande
     * @return DetailsCommande
     */
    public function setCommande(Commande $commande): DetailsCommande
    {
        $this->commande = $commande;
        return $this;
    }

    /**
     * @return Product
     */
    public function getProduit(): Product
    {
        return $this->produit;
    }

    /**
     * @param Product $produit
     * @return DetailsCommande
     */
    public function setProduit(Product $produit): DetailsCommande
    {
        $this->produit = $produit;
        return $this;
    }



}