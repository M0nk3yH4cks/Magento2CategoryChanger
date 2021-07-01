<?php
error_reporting(1);
set_time_limit(0);
ini_set('memory_limit', '2048M');

use Magento\Framework\App\Bootstrap;

/**
 * If your external file is in root folder
 */
require __DIR__ . '/app/bootstrap.php';



/**
 * If your external file is NOT in root folder
 * Let's suppose, your file is inside a folder named 'xyz'
 *
 * And, let's suppose, your root directory path is
 * /var/www/html/magento2
 */
// $rootDirectoryPath = '/var/www/html/magento2';
// require $rootDirectoryPath . '/app/bootstrap.php';

$params = $_SERVER;
$bootstrap = Bootstrap::create(BP, $params);
$objectManager = $bootstrap->getObjectManager();

// Set Area Code
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML); // or \Magento\Framework\App\Area::AREA_FRONTEND, depending on your need

// Define Zend Logger
$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/assign-remove-product-to-category.log');
$logger = new \Zend\Log\Logger();
$logger->addWriter($writer);

if (isset($_POST) && !empty($_POST)){
    $sku_list = [];
    $categoryIdsList = [];

    $sku_list = explode("|", $_POST["skus"]);
    $categoryIdsList = explode("|", $_POST["category_ids"]);

    foreach ($sku_list as $sku){
        /**
         * Assign product to categories
         */
        $categoryIds = $categoryIdsList;
        $categoryLinkRepository = $objectManager->get('\Magento\Catalog\Api\CategoryLinkManagementInterface');
        $categoryLinkRepository->assignProductToCategories($sku, $categoryIds);
    }
    echo "finito";
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Change Category</title>


    <!--    Importazione Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <!-- As a heading -->
    <nav class="navbar navbar-light bg-light">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Category Changer</span>
        </div>
    </nav>
    <form action="" method="post">
        <div class="container mt-5">
            <div class="mb-3">
                <label for="skus" class="form-label">Lista SKU</label>
                <input type="text" class="form-control" id="skus" name="skus" placeholder="SKU1|SKU2|SKU3">
            </div>

            <div class="mb-3">
                <label for="category_ids" class="form-label">Lista ID categorie da assegnare</label>
                <input type="text" class="form-control" id="category_ids" name="category_ids" placeholder="666|667|660">
            </div>
            <button type="submit" class="btn btn-primary">Cambia</button>
        </div>
    </form>

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
