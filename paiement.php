<?php
session_start();
// je fait avec api https://github.com/stripe/stripe-php/releases
require_once 'stripe/init.php'; 
require_once 'db.php';

// seccccuurrrrttttt
if (!isset($_SESSION['client']) || !isset($_SESSION['panier']) || count($_SESSION['panier']) === 0) {
    header('Location: index.php');
    exit;
}

// 1. Stripe test sk
\Stripe\Stripe::setApiKey('sk_test_51SQoxfD95YOAhUESvnWBSVUi5HUFfmsG718wyBepdgqeq6Nonk20qYEVPNeIV0FGAZ9H4ed8R1YN4j92n0GFx91Q0000z7LV2B');

// 2. je calcule somme de panier pour ça recuperer les prix sur db
$pdo = getBD();
$idList = array_column($_SESSION['panier'], 'id_art');
$placeholders = implode(',', array_fill(0, count($idList), '?'));
$sql = "SELECT id_art, nom, prix FROM Articles WHERE id_art IN ($placeholders)";
$stmt = $pdo->prepare($sql);
$stmt->execute($idList);
$productsDB = $stmt->fetchAll(PDO::FETCH_ASSOC);

//fformat stripe
$line_items = [];
foreach ($_SESSION['panier'] as $item) {
    foreach ($productsDB as $p) {
        if ($p['id_art'] == $item['id_art']) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $p['nom'],
                    ],
                    'unit_amount' => $p['prix'] * 100, // calculer les cent pour stripe
                ],
                'quantity' => $item['quantite'],
            ];
        }
    }
}

// 3. paimenttt
try {
    $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost:8888/VAROL/web_dev_e_commerce/success.php',
'cancel_url' => 'http://localhost:8888/VAROL/web_dev_e_commerce/commande.php',
    ]);

    // directionnn stripeee
    header("Location: " . $checkout_session->url);
} catch (Error $e) {
    http_response_code(500);
    echo "Erreur: " . $e->getMessage(); #corriger lang 
}