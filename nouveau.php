<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouveau client (Intelligent)</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .valid {
            border: 2px solid green !important;
            background-color: #e8f0fe;
        }

        .invalid {
            border: 2px solid red !important;
            background-color: #fce8e8;
        }

        .error-msg {
            color: red;
            font-size: 0.85em;
            display: none;
            margin-bottom: 10px;
        }

        #loading {
            display: none;
            color: blue;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <header id="header">
        <div class="container_header">
            <div class="logo"><a href="index.php"><img src="images/logo.png" alt="logo"></a></div>
            <div class="header_droite">
                <ul>
                    <li><a href="index.php">Accueil</a></li>
                </ul>
            </div>
        </div>
    </header>

    <div class="client_page">
        <h1>Créer un compte</h1>
        <div id="global-msg"></div>
        <form id="registerForm">
            <label>Nom :</label>
            <input type="text" id="nom" name="n" required>

            <label>Prénom :</label>
            <input type="text" id="prenom" name="p" required>

            <label>Adresse e-mail :</label>
            <input type="email" id="mail" name="mail" required>
            <div id="email-error" class="error-msg">Email invalide ou déjà utilisé</div>

            <label>Mot de passe :</label>
            <input type="password" id="mdp1" name="mdp1" required>
            <div id="mdp-error" class="error-msg">Min 8 caractères, 1 chiffre, 1 lettre, 1 spécial</div>

            <label>Confirmer mot de passe :</label>
            <input type="password" id="mdp2" name="mdp2" required>
            <div id="match-error" class="error-msg">Les mots de passe ne correspondent pas</div>

            <label>Adresse :</label><input type="text" name="adr">
            <label>Téléphone :</label><input type="text" name="num" required>
            <br>
            <button type="submit" id="btnSubmit" disabled style="opacity: 0.5;">S’inscrire</button>
            <span id="loading">Looddiinggg</span>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // lse regles 
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // password regles
            var passRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/;

            // function pour validet 
            function validateField(field, isValid, errorId) {
                if (isValid) {
                    $(field).addClass('valid').removeClass('invalid');
                    $(errorId).hide();
                } else {
                    $(field).addClass('invalid').removeClass('valid');
                    $(errorId).show();
                }
                checkGlobalValidity();
            }

            // 1. verifier pasword 
            $('#mdp1').on('input', function() {
                var val = $(this).val();
                validateField(this, passRegex.test(val), '#mdp-error');
                // si changer
                $('#mdp2').trigger('input');
            });

            // 2.verifier 2iem pasword es corect
            $('#mdp2').on('input', function() {
                var val = $(this).val();
                var original = $('#mdp1').val();
                validateField(this, val === original && val !== '', '#match-error');
            });

            // 3.check email ajaxx
            $('#mail').on('blur', function() { // verifier quand il finit de ectir
                var email = $(this).val();
                var field = this;

                if (!emailRegex.test(email)) {
                    validateField(field, false, '#email-error');
                    $('#email-error').text("Format d'email invalide");
                    return;
                }

                // ajax verifier
                $.post('check_email.php', {
                    mail: email
                }, function(response) {
                    if (response.trim() === 'exists') {
                        $(field).addClass('invalid').removeClass('valid');
                        $('#email-error').text("Cette adresse e-mail est déjà utilisée !").show();
                        $('#btnSubmit').prop('disabled', true).css('opacity', 0.5);
                    } else {
                        $(field).addClass('valid').removeClass('invalid');
                        $('#email-error').hide();
                        checkGlobalValidity();
                    }
                });
            });

            // 4.verifier les format
            function checkGlobalValidity() {
                var n = $('#nom').val() !== '';
                var p = $('#prenom').val() !== '';
                var m = $('#mail').hasClass('valid');
                var p1 = $('#mdp1').hasClass('valid');
                var p2 = $('#mdp2').hasClass('valid');

                if (n && p && m && p1 && p2) {
                    $('#btnSubmit').prop('disabled', false).css('opacity', 1);
                } else {
                    $('#btnSubmit').prop('disabled', true).css('opacity', 0.5);
                }
            }

            // check changement
            $('#nom, #prenom').on('input', checkGlobalValidity);

            // 5. envoyet form ajax
            $('#registerForm').submit(function(e) {
                e.preventDefault(); //non renoulvende page

                $('#btnSubmit').hide();
                $('#loading').show();

                $.ajax({
                    url: 'register_ajax.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#global-msg').html('<h3 style="color:green">' + response.message + '</h3>');
                            // 1.5 second 
                            setTimeout(function() {
                                window.location = 'index.php';
                            }, 1500);
                        } else {
                            $('#global-msg').html('<h3 style="color:red">' + response.message + '</h3>');
                            $('#btnSubmit').show();
                            $('#loading').hide();
                        }
                    },
                    error: function() {
                        alert("Une erreur s'est produite.");
                        $('#btnSubmit').show();
                        $('#loading').hide();
                    }
                });
            });
        });
    </script>

</body>

</html>