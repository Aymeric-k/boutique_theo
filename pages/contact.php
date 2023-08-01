<?php
$title = 'Contact me';
$css = 'contact';

include './header/header.php';
?>


<main>
    <h1>
        contact
    </h1>
    <form action="post">
        <div class="form-image">

            <img src="../assets/img/contact.jpg" alt="drawing with a letter">
        </div>

        <div class="form-content">
            <div class="input-row">
                <div class="input-group">
                    <label for="name">Name</label>
                    <input type="text" name="name">
                </div>

                <div class="input-group">
                    <label for="email">E-mail adress</label>
                    <input type="text" name="email">
                </div>
            </div>

            <div class="input-group">
                <label for="subject">Subject</label>
                <select name="subject" id="subject-contact">
                    <option value="return">Return</option>
                    <option value="delivery">Delivery</option>
                    <option value="mission">Mission</option>
                </select>
            </div>

            <div class="input-group">
                <label for="description">Content</label>
                <textarea name="description" id="description" cols="30" rows="10" placeholder="Your issue or request"></textarea>
            </div>
            <div class="submit-row">
                <input type="submit">
            </div>
        </div>
    </form>
</main>












<?php
include './header/footer.php';
?>