<section id="sidebar" class="col-3 right">
    <header>
        <h1 class="site-title"><a href="<?= $this->getParam('base_url') ?>"><?php echo $this->getParam('site_title'); ?></a></h1>
        <p class="site-tagline"><?php echo $this->getParam('site_subtitle'); ?></p>
    </header>
    <nav>
        <ul>
            <li><a href="<?= $this->getParam('base_url') ?>">home</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>blog">blog</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>fanzines">fanzines</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>widmo">widmo</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>oldies">oldies</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>misc">misc.</a></li>
            <li><a href="<?= $this->getParam('base_url') ?>contact">contact</a></li>
        </ul>
    </nav>
</section>