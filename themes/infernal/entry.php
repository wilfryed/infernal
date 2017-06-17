<div class="row">
    <?php echo $articles->menu(); ?>
</div>

<div class="row">
    <?php echo $articles->getEntry() ?>
</div>

<div class="row">
    <?php echo $articles->get_preventry($_GET['entry']); ?>
    <?php echo $articles->get_nextentry($_GET['entry']); ?>
</div>