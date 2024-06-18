<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <title>Accueil</title>
    <meta charset="utf-8">
    <meta name="author" content="HOUEDEC Kylian">
    <meta name="description" content="Présentation de mon Portfolio." >
    <link rel="icon" type="image/vnd.icon" href="asset/img/khFondNoir.png">
    
    <link rel="stylesheet" type="text/css" href="headerFooterStyle.css" >
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Intégration de reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    
<header>
    <img src="asset/img/kh.png" width="150px">
    <nav id="navMenu">
        <ul>
            <li><a href="index.html">A propos de moi</a></li>
            <li><a href="formation.html">Formation</a></li>
            <li><a href="competence.html">Competences</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <a class="bouton" href="./asset/pdf/CV_HOUEDEC_Kylian.pdf" download="">Mon CV</a>
</header>
<main id="mainContact">
    <section id="contact">
        <h1 class="rubrique">Contactez-moi</h1>
        <?php
        require 'Secure/config.php';
        // Validation reCAPTCHA
        $nombreErreur = 0; // Variable qui compte le nombre d'erreur
        $nom_value = isset($_POST['nom']) ? $_POST['nom'] : '';
        $email_value = isset($_POST['email']) ? $_POST['email'] : '';
        $message_value = isset($_POST['message']) ? $_POST['message'] : '';
        $erreur = ''; // Message d'erreur

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérification du reCAPTCHA uniquement si le formulaire est soumis
            if (isset($_POST['g-recaptcha-response'])) {
                $recaptcha_secret = RECAPTCHA; // Remplacez par votre clé secrète reCAPTCHA
                $recaptcha_response = $_POST['g-recaptcha-response'];
                $recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
                $recaptcha_data = json_decode($recaptcha);

                // Traitement du formulaire si reCAPTCHA est validé
                if (!$recaptcha_data->success) {
                    $nombreErreur++;
                    $erreur = 'reCAPTCHA invalide. Veuillez réessayer.';
                }
            }

            // Si le reCAPTCHA est validé, alors on traite le reste du formulaire
            if ($nombreErreur == 0) {
                // Récupération des données du formulaire
                $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
                $email = isset($_POST['email']) ? $_POST['email'] : '';
                $message = isset($_POST['message']) ? $_POST['message'] : '';

                // Vérification des données
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $nombreErreur++;
                    $erreur = 'Veuillez entrer une adresse email valide.';
                } elseif (empty($message)) {
                    $nombreErreur++;
                    $erreur = 'Veuillez entrer un message.';
                } else {
                    // Envoi de l'email si aucune erreur
                    $destinataire = 'contact@kylian-houedec.fr'; // Adresse email du destinataire
                    $sujet = 'Nouveau message de contact depuis votre site';
                    $contenu = '<html><head><title>Titre du message</title><style>body {background-color: black;color: white;font-family:margin: 0;padding: 20px;}p {color: red;font-size: 16px;line-height: 1.5;margin-bottom: 10px;}strong {color: white;font-weight: bold; }h1 {color: white;border-bottom: 2px solid white;padding-bottom: 10px;}</style></head><body>';
                    $contenu .= '<p>Bonjour, vous avez reçu un message à partir de votre site web.</p>';
                    $contenu .= '<p><strong>Nom</strong>: '.$nom.'</p>';
                    $contenu .= '<p><strong>Email</strong>: '.$email.'</p>';
                    $contenu .= '<p><strong>Message</strong>: '.$message.'</p>';
                    $contenu .= '</body></html>'; // Contenu du message de l'email
    
                    // Pour envoyer un email HTML, l'en-tête Content-type doit être défini
                    $entetes = 'MIME-Version: 1.0'."\r\n";
                    $entetes .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
                    $entetes .= "From: $nom <$email>";

                    // Envoi de l'email
                    if (mail( $destinataire, $sujet, $contenu, $entetes)) {
                        echo '<h2>Message envoyé!</h2>';
                        // Réinitialiser les valeurs des champs après l'envoi du mail
                        $nom_value = '';
                        $email_value = '';
                        $message_value = '';
                    } else {
                        echo '<p>Une erreur est survenue lors de l\'envoi du message.</p>';
                    }
                }
            }
        }
        ?>
        
        <?php
        // Affichage du message d'erreur
        if (!empty($erreur)) {
            echo '<div class="erreur">' . $erreur . '</div>';
        }
        ?>
        <section id="form">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p class="input">
                    <label for="nom">Votre nom et prénom :</label>
                    <input type="text" name="nom" id="nom" size="30" value="<?php echo htmlspecialchars($nom_value); ?>"/>
                </p>
                <p class="input">
                    <label for="email">Votre email <span>*</span> :</label>
                    <input type="text" name="email" id="email" size="30" value="<?php echo htmlspecialchars($email_value); ?>" />
                </p>
                <p id="message">
                    <label for="message">Message <span>*</span> :</label>
                    <textarea name="message" id="message" cols="60" rows="10"><?php echo htmlspecialchars($message_value); ?></textarea>
                </p>
                <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_PUBLIC; ?>"></div>
                <p><input type="submit" name="submit" value="Envoyer" /></p>
            </form>
        </section>
        
    </section>
</main>
<footer>
    <section>
        <div>
            <h1>COORDONNEES</h1>
            <div id="contact">
                <span>Hennebont (56700), France</span>
                <span><a href="tel: +33 6 45 49 06 51"> +33 6 45 49 06 51 </a></span>
                <span><a href="mailto: contact@kylian-houedec.fr"> contact@kylian-houedec.fr</a></span>
                <span><a href="asset/pdf/CV_HOUEDEC_Kylian.pdf" download=""> Télécharger mon cv</a></span>
            </div>
        </div>
        <div>
            <h1>NAVIGATION</h1>
            <div>
                <span><a href="index.html">Accueil</a></span>
                <span><a href="formation.html">Formation</a></span>
                <span><a href="competence.html">Compétences</a></span>
                <span><a href="contact.php">Contact</a></span>
            </div>
        </div>
        <div>
            <h1>Reseaux</h1>
            <div>
                <span><a href="https://github.com/BZHKylian5">Github</a></span>
                <span><a href="https://www.linkedin.com/in/kylian-houedec-37a0792a9/">Linkedin</a></span>
            </div>
        </div>
    </section>
    <p>© 2024 par HOUEDEC Kylian - <a href="mention-legale.html">Mentions Légales</a></p>
</footer>
</body>
</html>

