<?php



$css = "success";
$title = "Order registered";
include './header/header.php';
require_once '../bdd/connect.php';
?>



<main>
    <section>
        <div>
            <img src="../assets/img/validate_command.png" alt="validate icon">
            <p>Order successfully placed! You will receive an email within the next few seconds with your order details.</p>
            <p>You will be redirected to the homepage in <span id="countdown">8</span> seconds.</p>
        </div>
    </section>
</main>
<script> 
    let countDown = document.querySelector('#countdown');
    let countDownValue = parseInt(countDown.textContent)
    const countDownInterval = setInterval (()=>{
        countDownValue-=1
        countDown.innerHTML=countDownValue
        if (countDownValue <= 0) {
            clearInterval(countDownInterval); 
            window.location = '/index.php'; 
        }
    }, 1000)

    
</script>










<?php
include './header/footer.php';