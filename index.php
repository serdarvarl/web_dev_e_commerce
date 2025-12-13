<?php
session_start();
require_once __DIR__ . '/db.php'; // connection db

try {
    $pdo = getBD();
    //une seul fois prendre les artice
    $sql = "SELECT * FROM Articles";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur Database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Miel - Accueil</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
           #chat-box {
        position: fixed;
        bottom: 0;
        right: 20px;
        width: 320px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 10px 10px 0 0;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        z-index: 9999;
        font-family: Arial, sans-serif;
    }

    
    #chat-header {
        background: #FFC107; /* Bal rengi */
        color: #333;
        padding: 12px;
        font-weight: bold;
        cursor: pointer;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    
    #chat-content {
        height: 300px;
        overflow-y: auto;
        padding: 15px;
        background: #f9f9f9;
        display: none; 
        border-bottom: 1px solid #eee;
    }

    
    #chat-form {
        display: none;
        padding: 10px;
        background: #fff;
    }

    #chat-input {
        width: 70%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        outline: none;
    }

    #chat-btn {
        width: 25%;
        padding: 8px;
        background: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
    }

    /* Tekil Mesaj Stili */
    .msg-item {
        margin-bottom: 10px;
        font-size: 0.9em;
        line-height: 1.4;
    }
    .msg-user {
        font-weight: bold;
        color: #d35400;
    }
    .msg-text {
        color: #555;
    }
    .msg-time {
        font-size: 0.7em;
        color: #999;
        float: right;
    }
   
    </style>

</head>
<body>

    <header id="header">
        <div class="container_header">
            <div id="top_menu">
                <ul class="menu">
                    <li><a href="index.php">Miel</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div class="logo">
                <a href="index.php"><img src="images/logo.png" alt="logo"></a>
            </div>
            <div class="header_droite">
                <ul>
                    <li><input type="text" placeholder="Rechercher"></li>
                    <?php if (!isset($_SESSION['client'])): ?>
                        <li><a href="nouveau.php">Nouveau client ?</a></li>
                        <li><a href="connexion.php">Se connecter</a></li>
                    <?php else: ?>
                        <li>Bonjour, <?= htmlspecialchars($_SESSION['client']['prenom']) ?></li>
                        <li><a href="panier.php">Panier</a></li>
                        <li><a href="deconnexion.php">Se déconnecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </header>

    <div id="product-list-container">
        <?php if (count($articles) > 0): ?>
            <?php foreach ($articles as $row): ?>
                <div class="product-item">
                    <a href="article.php?id_art=<?= htmlspecialchars($row['id_art']) ?>">
                        <h2 class="product_name"><?= htmlspecialchars($row['nom']) ?></h2>
                        <img src="<?= htmlspecialchars($row['url_photo']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>">
                    </a>
                    <p class="product_description"><?= htmlspecialchars($row['description']) ?></p>
                    <p class="product_price">Prix: <?= htmlspecialchars($row['prix']) ?> €</p>

                    <?php if (isset($_SESSION['client'])): ?>
                        <form action="ajouter.php" method="post" style="margin-top:8px;">
                            <input type="hidden" name="id_art" value="<?= $row['id_art'] ?>">
                            <label>Quantité</label>
                            <input type="number" name="quantite" min="1" value="1" required style="width:50px;">
                            <button type="submit">Ajouter</button>
                        </form>
                    <?php else: ?>
                        <p><a href="connexion.php" style="color:red;">Connectez-vous pour acheter</a></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé.</p>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Miel. All rights reserved.</p>
    </footer>
<div id="chat-box">
    <div id="chat-header">
        <span>💬 Discussion en direct</span>
        <span id="toggle-icon">▲</span>
    </div>
    
    <div id="chat-content">
        <p style="text-align:center; color:gray;">Chargement...</p>
    </div>

    <div id="chat-form">
        <input type="text" id="chat-input" placeholder="Votre message..." maxlength="256">
        <button id="chat-btn">Envoyer</button>
    </div>
</div>


<script>
$(document).ready(function() {
    var chatOpen = false;
    var intervalId = null;

    // toogle open ferme
    $('#chat-header').click(function() {
        $('#chat-content, #chat-form').slideToggle();
        chatOpen = !chatOpen;
        $('#toggle-icon').text(chatOpen ? '▼' : '▲');
        
        if (chatOpen) {
            loadMessages();
            intervalId = setInterval(loadMessages, 3000);
        } else {
            clearInterval(intervalId);
        }
    });

    // msg televerse 
    function loadMessages() {
        $.ajax({
            url: 'chat_api.php',
            type: 'GET',
            data: { action: 'load' },
            dataType: 'json',
            success: function(messages) {
                var html = '';
                if (messages.length === 0) {
                    html = '<p style="text-align:center; color:#999;">Aucun message récent.</p>';
                } else {
                    $.each(messages, function(index, msg) {
                        var user = $('<div>').text(msg.nom_user).html();
                        var text = $('<div>').text(msg.message).html();
                        
                        html += '<div class="msg-item">';
                        html += '<span class="msg-user">' + user + '</span> dit : ';
                        html += '<span class="msg-text">"' + text + '"</span>';
                        html += '</div>';
                    });
                }
                $('#chat-content').html(html);
                var div = $('#chat-content');
                div.scrollTop(div.prop("scrollHeight"));
            }
        });
    }

    // envoyer msg
    $('#chat-btn').click(sendMessage);
    $('#chat-input').keypress(function(e) {
        if(e.which == 13) sendMessage();
    });

    function sendMessage() {
        var txt = $('#chat-input').val().trim();
        if (txt === '') return;

        $.post('chat_api.php', { action: 'send', message: txt }, function(response) {
            if (response.status === 'success') {
                $('#chat-input').val('');
                loadMessages();
            } else {
                
                alert("Erreur: " + (response.msg || response.message || "Erreur inconnue"));
            }
        }, 'json');
    }
});
</script>
</body>
</html>


