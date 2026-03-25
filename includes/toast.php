<?php if(isset($_SESSION['message'])){ ?>

<div id="toast" class="toast">
    <?php echo $_SESSION['message']; ?>
</div>

<script>
setTimeout(() => {
    const toast = document.getElementById("toast");
    if(toast){
        toast.style.opacity = "0";
        toast.style.transform = "translateY(-20px)";
    }
}, 2500);

setTimeout(() => {
    const toast = document.getElementById("toast");
    if(toast){
        toast.remove();
    }
}, 3000);
</script>

<?php unset($_SESSION['message']); } ?>