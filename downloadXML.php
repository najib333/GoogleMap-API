<?php
try
{
    $pdo = new PDO('mysql:host=localhost;dbname=google_markers;charset=utf8', 'test', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: " . $e->getMessage();
}

$select = $pdo->prepare("SELECT * FROM markers");
$select->execute();

// creating object of SimpleXMLElement
$xml = new SimpleXMLElement('<?xml version="1.0"?><markers></markers>');

$xmlData = array();
foreach($select as $value){

    $marker = $xml->addChild('marker');
    $marker->addAttribute('id', $value['id']);
    $marker->addAttribute('name', $value['name']);
    $marker->addAttribute('description', $value['description']);
    $marker->addAttribute('lat', $value['lat']);
    $marker->addAttribute('lng', $value['lng']);
    $marker->addAttribute('discount_value', $value['discount_value']);
}

//saving generated xml file; 
$result = $xml->asXML('google_markers.xml');
?>
