<div class="vg_modal vg-center">
    <?php if (isset($_SESSION['res']['answer'])) {
        echo $_SESSION['res']['answer'];
        unset($_SESSION['res']);
    } ?>
</div>

