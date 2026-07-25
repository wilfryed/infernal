<?php $this->getTemplatePart('header'); ?>
<div class="row">
    <?php $this->getTemplatePart('sidebar'); ?>
    <section class="col-9">
        <article>
            <?= $page->getContent() ?>
        </article>
        <div class="row">
            <?php
            foreach ($this->query('blog')->orderBy('date', 'desc')->limit(3)->get() as $entry): ?>
            
                <article class="col-4">
                    <a href="blog/<?= $entry->getUrl() ?>">
                        <img src="<?= $entry->getThumbnail() ?>">
                        <h3><?= $entry->getTitle() ?></h3>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>