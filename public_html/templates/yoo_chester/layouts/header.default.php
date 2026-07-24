<?php if ($this['widgets']->count('menu + offcanvas + logo-small')) : ?>
<div class="tm-navbar-container" data-uk-sticky="{top:-200, animation: 'uk-animation-slide-top'}">
    <div class="header uk-flex uk-flex-middle uk-flex-space-between">        
        <?php if ($this['widgets']->count('logo-small + offcanvas')) : ?>
        <div class=" uk-flex uk-flex-middle uk-flex-space-between">
            <?php if ($this['widgets']->count('logo-small')) : ?>
            <a class="tm-logo-small uk-visible-large" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a>
            <?php endif; ?>            
        </div>
        <?php endif; ?>
        <div class="menu">
            <nav class="tm-navbar uk-navbar">
                <?php if ($this['widgets']->count('menu')) : ?>
                <?php echo $this['widgets']->render('menu'); ?>
                <?php endif; ?>
                <?php if ($this['widgets']->count('logo-small')) : ?>
                <a class="tm-logo-small uk-hidden-large" href="<?php echo $this['config']->get('site_url'); ?>"><?php echo $this['widgets']->render('logo-small'); ?></a>
                <?php endif; ?>
            </nav>

        </div>
        <div class="menu-off uk-hidden-large">
             <?php if ($this['widgets']->count('offcanvas')) : ?>
            <a href="#offcanvas" class="uk-navbar-toggle uk-hidden-large" data-uk-offcanvas="{mode:'slide'}"></a>
            <?php endif; ?>
        </div>
        <?php if ($this['widgets']->count('search + headerbar')) : ?>
        <div class="uk-flex uk-flex-middle">
            <?php if ($this['widgets']->count('search')) : ?>
            <div class="uk-visible-large"><?php echo $this['widgets']->render('search'); ?></div>
            <?php endif; ?>
            <?php if ($this['widgets']->count('headerbar')) : ?>
            <?php echo $this['widgets']->render('headerbar'); ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>