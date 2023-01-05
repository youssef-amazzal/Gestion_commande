<?php

global $clientDAO;
global $productDAO;

// add a new client
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Client'
    &&  $_GET['action'] == 'add'
    &&  $_GET['confirmed'] == '1'
) {
    $client = Client::newInstance();
    $client = $client
                    ->setNom($_GET['nom'])
                    ->setRaisonSociale($_GET['raison_sociale'])
                    ->setAdresse($_GET['adresse'])
                    ->setVille($_GET['ville'])
                    ->setPays($_GET['pays'])
                    ->setTelephone($_GET['telephone']);
    $clientDAO->create($client);
    header('Location: index.php?table=Client'); // Redirect the user's browser to a different page
}


// add a new product
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Product'
    &&  $_GET['action'] == 'add'
    &&  $_GET['confirmed'] == '1'
) {
    $product = Product::newInstance();
    $product = $product
                    ->setNom($_GET['nom'])
                    ->setPrixUnitaire($_GET['prix_unitaire'])
                    ->setQteStockee($_GET['qte_stockee'])
                    ->setIndisponible($_GET['qte_stockee'] == 0);
    $productDAO->create($product);
    header('Location: index.php?table=Product');
}

// update a client
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Client'
    &&  $_GET['action'] == 'update'
    &&  $_GET['confirmed'] == '1'
) {
    $client = Client::newInstance();
    $client = $client
                    ->setNumClient(intval($_GET['num_client']))
                    ->setNom($_GET['nom'])
                    ->setRaisonSociale($_GET['raison_sociale'])
                    ->setAdresse($_GET['adresse'])
                    ->setVille($_GET['ville'])
                    ->setPays($_GET['pays'])
                    ->setTelephone($_GET['telephone']);
    $clientDAO->update($client);
    header('Location: index.php?table=Client');
}

// update a product
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Product'
    &&  $_GET['action'] == 'update'
    &&  $_GET['confirmed'] == '1'
) {
    $product = Product::newInstance();
    $product = $product
                    ->setRefProduit(intval($_GET['ref_produit']))
                    ->setNom($_GET['nom'])
                    ->setPrixUnitaire($_GET['prix_unitaire'])
                    ->setQteStockee($_GET['qte_stockee'])
                    ->setIndisponible($_GET['qte_stockee'] == 0);
    $productDAO->update($product);
    header('Location: index.php?table=Product');
}

// delete a client
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Client'
    &&  $_GET['action'] == 'delete'
    &&  $_GET['confirmed'] == '1'
) {
    $clientDAO->delete(intval($_GET['num_client']));
    header('Location: index.php?table=Client');
}

// delete a product
if (    isset($_GET['table'])
    &&  isset($_GET['action'])
    &&  isset($_GET['confirmed'])
    &&  $_GET['table'] == 'Product'
    &&  $_GET['action'] == 'delete'
    &&  $_GET['confirmed'] == '1'
) {
    $productDAO->delete(intval($_GET['ref_produit']));
    header('Location: index.php?table=Product');
}


