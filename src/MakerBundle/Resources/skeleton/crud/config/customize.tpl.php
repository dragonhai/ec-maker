parameters:
    eccube_nav:
        <?= $route_name ?>:
            name: admin.<?= $route_name ?>.<?= $route_name ?>_management
            icon: fa-cube
            children:
                <?= $route_name ?>_master:
                    name: admin.<?= $route_name ?>.<?= $route_name ?>_list
                    url: admin_<?= $route_name ?><?= PHP_EOL ?>
                <?= $route_name ?>_new:
                    name: admin.<?= $route_name ?>.<?= $route_name ?>_new
                    url: admin_<?= $route_name ?>_<?= $route_name ?>_new
framework:
    translator:
        paths:
            - '%kernel.project_dir%/app/Customize/Resource/generator/<?= $version ?>/locale/'