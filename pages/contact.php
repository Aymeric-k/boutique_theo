<?php

$title = 'Contact me';
$css = 'contact';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
include './header/header.php';
?>

<script src="https://www.google.com/recaptcha/api.js?render=6LdYvOMpAAAAAOKwn5eAec2Cs36RANao8pShqAjs"></script>

<main>
    <h1>Contact</h1>
    <form id="contactForm" method="post" action="./send_contact">
        <div class="form-image">
            <img src="../assets/img/contact.png" alt="dog with a letter">
        </div>

        <div class="form-content">
            <div class="input-row">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="input-group">
                    <label for="email">E-mail address</label>
                    <input type="email" name="email" required>
                </div>
            </div>

            <div class="input-group">
                <label for="subject">Subject</label>
                <select name="subject" id="subject-contact" required>
                    <option value="return">Return</option>
                    <option value="delivery">Delivery</option>
                    <option value="mission">Mission</option>
                </select>
            </div>

            <div class="input-group">
                <label for="description">Content</label>
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Your issue or request" required></textarea>
            </div>

            <div class="submit-row">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <input type="hidden" id="captchaToken" name="captchaToken">
                <button class="g-recaptcha" data-sitekey="6LdYvOMpAAAAAOKwn5eAec2Cs36RANao8pShqAjs" data-action="submit" type="button">Submit</button>
            </div>
        </div>
    </form>
</main>

<script src="https://www.google.com/recaptcha/api.js?render=6LdYvOMpAAAAAOKwn5eAec2Cs36RANao8pShqAjs"></script>
<script>
    document.querySelector('.g-recaptcha').addEventListener('click', function(e) {
        e.preventDefault(); // Empêche la soumission immédiate du formulaire

        grecaptcha.ready(function () {
            grecaptcha.execute('6LdYvOMpAAAAAOKwn5eAec2Cs36RANao8pShqAjs', { action: 'submit' }).then(function (token) {
                // Ajouter le token dans le champ caché
                document.getElementById("captchaToken").value = token;
                
                // Soumettre le formulaire après la génération du token
                document.getElementById("contactForm").submit();
            });
        });
    });
</script>

<?php
include './header/footer.php';
?>