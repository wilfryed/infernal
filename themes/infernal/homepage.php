<div class="row">    
    <div class="small-12">
        <div class="row column text-center">
            <h1><?php echo $this->getParam('site_title'); ?></h1>
            <h2 class="subheader"><?php echo $this->getParam('site_subtitle'); ?></h2>
        </div>
    </div>    
</div>

<div class="row">
    <div class="small-12">
        <?php echo $articles->menu(); ?>
    </div>
</div>

<div class="row medium-8 large-7 columns">
    <?php
    if (have_entries()){
        the_entries();
    }else{
        echo "No entries for the moment!";
    }
    ?>
</div>

<div class="row medium-12 large-2 columns">
    <ul class="menu simple">
        <li><a href="#"><?php echo $articles->pagination(); ?></a></li>
    </ul>
</div>


