<?php /** @var array $popular @var array $nav */ ?>

<header class="page-head">
    <p class="kicker">404</p>
    <h1>This page printed a red candle</h1>
    <p class="lede">The page you were after does not exist or has been moved. Here is what people are reading instead.</p>
    <a class="btn" href="/">← Back to the front page</a>
</header>

<h2 class="visually-hidden">Popular articles</h2>
<div class="grid">
    <?php foreach ($popular as $a) { echo article_card($a); } ?>
</div>
