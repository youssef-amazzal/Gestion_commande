<?php
require_once __DIR__ . '/../database/DbConnection.php';

require_once __DIR__ . '/../models/Table.php';

require_once __DIR__.'/../database/ClientDao.php';
require_once __DIR__ . '/../models/Client.php';

require_once __DIR__.'/../database/ProductDao.php';
require_once __DIR__ . '/../models/Product.php';

require_once __DIR__.'/../database/CommandeDao.php';
require_once __DIR__ . '/../models/Commande.php';

require_once __DIR__.'/../database/DetailsDao.php';
require_once __DIR__ . '/../models/DetailsCommande.php';


require_once __DIR__.'/../utils/ContentManager.php';

if (!isset($_GET['table'])) {
    $url = "index.php?table=Client";
    header("Location: $url");
}

$table_query = $_GET['table'];

define('TEMPLATE' ,file_get_contents(__DIR__."/../../public/assets/templates/template.html"));
const CLIENT = 'Client';
const PRODUCT = 'Product';


$clientDAO = ClientDAO::getInstance();
$productDAO = ProductDAO::getInstance();
$commandeDAO = CommandeDAO::getInstance();
$detailsDAO = DetailsDAO::getInstance();

