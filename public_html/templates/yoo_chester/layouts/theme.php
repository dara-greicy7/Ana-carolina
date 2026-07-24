<?php
/**
* @package   yoo_chester
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// get theme configuration
include($this['path']->path('layouts:theme.config.php'));

?>
<!DOCTYPE HTML>
<html lang="<?php echo $this['config']->get('language'); ?>" dir="<?php echo $this['config']->get('direction'); ?>"  data-config='<?php echo $this['config']->get('body_config','{}'); ?>'>

<head>
<?php echo $this['template']->render('head'); ?>

<meta name="theme-color" content="#85a940">

<link rel="stylesheet" href="<?php echo JURI::root();?>font/stylesheet.css">
<link rel="stylesheet" href="<?php echo JURI::root();?>templates/yoo_chester/css/svg.css">
</head>

<body class="<?php echo $this['config']->get('body_classes'); ?>">

    <?php if ($this['widgets']->count('toolbar-l + toolbar-r')) : ?>
    <div class="tm-toolbar uk-clearfix uk-visible-large">

        <?php if ($this['widgets']->count('toolbar-l')) : ?>
        <div class="uk-float-left"><?php echo $this['widgets']->render('toolbar-l'); ?></div>
        <?php endif; ?>

        <?php if ($this['widgets']->count('toolbar-r')) : ?>
        <div class="uk-float-right"><?php echo $this['widgets']->render('toolbar-r'); ?></div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php echo $this['template']->render('header.'.$this['config']->get('navbar_template', 'default').'', array('sticky' => $this['config']->get('navbar_sticky', 0))); ?>

    <?php if ($this['widgets']->count('sidepanel')) : $side_widgets = $this['widgets']->load('sidepanel'); $side_widget = $side_widgets[0]; ?>
    <div class="tm-sidepanel">
        <button class="uk-button uk-button-large uk-button-primary uk-hidden-small" data-uk-toggle="{target: '.tm-sidepanel', cls: 'uk-open'}"><?php echo $side_widget->title; ?> <i class="uk-icon-angle-down uk-margin-small-left"></i></button>
        <button class="uk-button uk-button-large uk-button-primary uk-visible-small" data-uk-toggle="{target: '.tm-sidepanel', cls: 'uk-open'}"><i class="uk-icon-calendar"></i></button>
        <?php echo $this['widgets']->render('sidepanel'); ?>
    </div>
    <?php endif; ?>

     <?php if ($this['config']->get('system_output', true)) : ?>
        <main id="tm-content" class="tm-content">
            <?php if ($this['widgets']->count('breadcrumbs')) : ?>
           		 <?php echo $this['widgets']->render('breadcrumbs'); ?>
            <?php endif; ?>
            
        </main>
   <?php endif; ?>

    <?php if ($this['widgets']->count('top-a')) : ?>
    <div id="tm-top-a" class="uk-block tm-block-top-a <?php echo @$block_classes['top-a']; echo $display_classes['top-a']; ?>">
        <div class="<?php echo $container_class['top-a']; ?>">
            <section class="<?php echo @$grid_classes['top-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-a', array('layout'=>$this['config']->get('grid.top-a.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('top-b')) : ?>
    <div id="tm-top-b" class="uk-block tm-block-top-b <?php echo @$block_classes['top-b']; echo $display_classes['top-b']; ?>">
        <div class="<?php echo $container_class['top-b']; ?>">
            <section class="<?php echo @$grid_classes['top-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-b', array('layout'=>$this['config']->get('grid.top-b.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('top-c')) : ?>
    <div id="tm-top-c" class="uk-block tm-block-top-c <?php echo @$block_classes['top-c']; echo $display_classes['top-c']; ?>">
        <div class="<?php echo $container_class['top-c']; ?>">
            <section class="<?php echo @$grid_classes['top-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-c', array('layout'=>$this['config']->get('grid.top-c.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('top-d')) : ?>
    <div id="tm-top-d" class="uk-block tm-block-top-d <?php echo @$block_classes['top-d']; echo $display_classes['top-d']; ?>">
        <div class="<?php echo $container_class['top-d']; ?>">
            <section class="<?php echo @$grid_classes['top-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('top-d', array('layout'=>$this['config']->get('grid.top-d.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('main-top + main-bottom + sidebar-a + sidebar-b') || $this['config']->get('system_output', true)) : ?>
        <div class="uk-block tm-block-main <?php echo $block_classes['main']; ?>">
            <div class="<?php echo $container_class['main']; ?>">

                <div id="tm-middle" class="tm-middle uk-grid" data-uk-grid-match data-uk-grid-margin>

                    <?php if ($this['widgets']->count('main-top + main-bottom') || $this['config']->get('system_output', true)) : ?>
                    <div class="<?php echo $columns['main']['class'] ?>">

                        <?php if ($this['widgets']->count('main-top')) : ?>
                        <section id="tm-main-top" class="<?php echo $grid_classes['main-top']; echo $display_classes['main-top']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-top', array('layout'=>$this['config']->get('grid.main-top.layout'))); ?></section>
                        <?php endif; ?>

                        <?php if ($this['config']->get('system_output', true)) : ?>
                                <main id="tm-content" class="tm-content">
                                    <?php echo $this['template']->render('content'); ?>
                             </main>
                           <?php endif; ?>   

                        <?php if ($this['widgets']->count('main-bottom')) : ?>
                        <section id="tm-main-bottom" class="<?php echo $grid_classes['main-bottom']; echo $display_classes['main-bottom']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('main-bottom', array('layout'=>$this['config']->get('grid.main-bottom.layout'))); ?></section>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <?php foreach($columns as $name => &$column) : ?>
                    <?php if ($name != 'main' && $this['widgets']->count($name)) : ?>
                    <aside class="<?php echo $column['class'] ?>"><?php echo $this['widgets']->render($name) ?></aside>
                    <?php endif ?>
                    <?php endforeach ?>

                </div>

        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('bottom-a')) : ?>
    <div id="tm-bottom-a" class="uk-block tm-block-bottom-a <?php echo @$block_classes['bottom-a']; echo $display_classes['bottom-a']; ?>">
        <div class="<?php echo $container_class['bottom-a']; ?>">
            <section class="<?php echo @$grid_classes['bottom-a']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-a', array('layout'=>$this['config']->get('grid.bottom-a.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('bottom-b')) : ?>
    <div id="tm-bottom-b" class="uk-block tm-block-bottom-b <?php echo @$block_classes['bottom-b']; echo $display_classes['bottom-b']; ?>">
        <div class="<?php echo $container_class['bottom-b']; ?>">
            <section class="<?php echo @$grid_classes['bottom-b']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-b', array('layout'=>$this['config']->get('grid.bottom-b.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('bottom-c')) : ?>
    <div id="tm-bottom-c" class="uk-block tm-block-bottom-c <?php echo @$block_classes['bottom-c']; echo $display_classes['bottom-c']; ?>">
        <div class="<?php echo $container_class['bottom-c']; ?>">
            <section class="<?php echo @$grid_classes['bottom-c']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-c', array('layout'=>$this['config']->get('grid.bottom-c.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('bottom-d')) : ?>
    <div id="tm-bottom-d" class="uk-block tm-block-bottom-d <?php echo @$block_classes['bottom-d']; echo $display_classes['bottom-d']; ?>">
        <div class="<?php echo $container_class['bottom-d']; ?>">
            <section class="<?php echo @$grid_classes['bottom-d']; ?>" data-uk-grid-match="{target:'> div > .uk-panel'}" data-uk-grid-margin><?php echo $this['widgets']->render('bottom-d', array('layout'=>$this['config']->get('grid.bottom-d.layout'))); ?></section>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($this['widgets']->count('footer + debug') || $this['config']->get('warp_branding', true) || $this['config']->get('totop_scroller', true)) : ?>
    <div id="tm-footer" class="uk-block tm-block-footer uk-text-center <?php echo @$block_classes['footer']; echo @$display_classes['footer']; ?>">
        <div class="<?php echo $container_class['footer']; ?>">

            <footer class="tm-footer">

                <?php
                    echo $this['widgets']->render('footer');
                    $this->output('warp_branding');
                    echo $this['widgets']->render('debug');
                ?>

                <?php if ($this['config']->get('totop_scroller', true)) : ?>
                <a class="tm-totop-scroller" data-uk-smooth-scroll href="#"></a>
                <?php endif; ?>

            </footer>

        </div>
    </div>
    <?php endif; ?>

    <?php echo $this->render('footer'); ?>

    <?php if ($this['widgets']->count('offcanvas')) : ?>
    <div id="offcanvas" class="uk-offcanvas">
        <div class="uk-offcanvas-bar"><?php echo $this['widgets']->render('offcanvas'); ?></div>
    </div>
    <?php endif; ?>

      <script>(function(d){var s = d.createElement("script");s.setAttribute("data-account", "8cbJUwNQHn");s.setAttribute("src", "https://cdn.userway.org/widget.js");(d.body || d.head).appendChild(s);})(document)</script><noscript>Please ensure Javascript is enabled for purposes of <a href="https://userway.org">website accessibility</a></noscript>
      
</body>
</html>
