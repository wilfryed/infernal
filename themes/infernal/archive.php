<?php $this->getTemplatePart('header'); ?>
<div class="row">
    <?php $this->getTemplatePart('sidebar'); ?>
    <div class="col-9">
        <h2><?= $title ?></h2>
        <div class="row">
        <?php foreach ($this->query('blog')->orderBy('date', 'desc')->limit(24)->get() as $entry): ?>
            <article class="col-4">
                <a href="<?= $entry->getUrl() ?>">
                <img src="<?= $entry->getThumbnail() ?>">
                <h3><?= $entry->getTitle() ?></h3>
                </a>
            </article>
        <?php endforeach; ?>
        </div>
    </div>
</div>