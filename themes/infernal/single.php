<?php $this->getTemplatePart('header'); ?>
<div class="row">
<?php $this->getTemplatePart('sidebar'); ?>
    <article class="col-9">

        <h1><?= $entry->getTitle(); ?></h1>


        <?php if ($entry->getDate()) : ?>

            <p class="entry-date">
                <?= $entry->getFormattedDate(); ?>
            </p>

        <?php endif; ?>


        <?= $entry->getContent(); ?>
            <?php
$query = $this->query('blog')
    ->orderBy('date', 'desc');

$previous = $query->previous($entry);
$next = $query->next($entry);
?>
<nav class="post-navigation">

    <?php if ($previous): ?>

        <a href="<?= $previous->getUrl(); ?>">
            ← <?= $previous->getTitle(); ?>
        </a>

    <?php endif; ?>


    <?php if ($next): ?>

        <a href="<?= $next->getUrl(); ?>">
            <?= $next->getTitle(); ?> →
        </a>

    <?php endif; ?>

</nav>
    </article>
</div>

<?php $this->getTemplatePart('footer'); ?>