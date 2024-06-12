<?php
require_once('functions.php');
$error = "";
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    if(!testPassworsStrenght($_POST['password'])) {
        $error = "Mot de passe trop faible";
    } else {
        $result = saveUser($_POST['username'], $_POST['email'], $_POST['password']);
        if($result === true) {
            header('Location: index.php');
        } else {
            $error = "Une erreur est survenue " . $result;
        }
    }
}
?>

<html lang="FR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ma super app sécurisée - Inscription</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.formvalidation/0.6.1/js/formValidation.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.formvalidation/0.6.1/js/framework/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
    <style>
        .password-progress {
            margin-top: 10px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Inscription</h1>
    <?php if(!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form id="registerForm" action="/register.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez un nom d'utilisateur.
            </div>
        </div>
        <div class="form-group">
            <label for="email">Adresse email :</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">
                S'il vous plaît entrez une adresse email valide.
            </div>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="progress password-progress">
                <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0;"></div>
            </div>
            <div class="invalid-feedback">
                S'il vous plaît entrez un mot de passe.
            </div>
        </div>
        <div class="form-group">
            <label for="password-confirm">Confirmez le mot de passe :</label>
            <input type="password" class="form-control" id="password-confirm" name="password-confirm" required>
            <div class="invalid-feedback">
                S'il vous plaît confirmez votre mot de passe.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
    <script>
        $('#registerForm').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        callback: {
                            callback: function(value, validator, $field) {
                                var password = $field.val();
                                if (password == '') {
                                    return true;
                                }

                                var result  = zxcvbn(password),
                                    score   = result.score,
                                    message = result.feedback.warning || 'The password is weak';

                                // Update the progress bar width and add alert class
                                var $bar = $('#strengthBar');
                                switch (score) {
                                    case 0:
                                        $bar.attr('class', 'progress-bar bg-danger')
                                            .css('width', '1%');
                                        break;
                                    case 1:
                                        $bar.attr('class', 'progress-bar bg-danger')
                                            .css('width', '25%');
                                        break;
                                    case 2:
                                        $bar.attr('class', 'progress-bar bg-danger')
                                            .css('width', '50%');
                                        break;
                                    case 3:
                                        $bar.attr('class', 'progress-bar bg-warning')
                                            .css('width', '75%');
                                        break;
                                    case 4:
                                        $bar.attr('class', 'progress-bar bg-success')
                                            .css('width', '100%');
                                        break;
                                }

                                // We will treat the password as an invalid one if the score is less than 3
                                if (score < 1) {
                                    return {
                                        valid: false,
                                        message: message
                                    }
                                }

                                return true;
                            }
                        }
                    }
                }
            }
        });

        var password = document.getElementById("password");
        var confirm_password = document.getElementById("password-confirm");

        function validatePassword(){
            if(password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Les mots de passe ne correspondent pas");
                return false;
            } else {
                confirm_password.setCustomValidity('');
                return true;
            }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</div>
</body>
</html>