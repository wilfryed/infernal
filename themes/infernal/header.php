<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="<?= $inferno->getThemeUrl() ?>/style.css">
    <title><?php echo $this->getParam('site_title'); ?></title>
</head>

<body>
    <header>
        <h1 class="site-title"><?php echo $this->getParam('site_title'); ?></h1>
        <p class="site-tagline"><?php echo $this->getParam('site_subtitle'); ?></p>
        <nav>
            <ul>
                <li><a href="<?= $inferno->getParam('base_url') ?>/">Accueil</a></li>
            </ul>
        </nav>
    </header>