<div id="<?= $route_name ?>">
    <h4>{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_management'|trans }}</h4>
    <ul>
        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
            <li>{{ <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }}</li>
        {% endfor %}
    </ul>
</div>

<script>
    $(function () {
        $('#<?= $route_name ?>').insertAfter($('.ec-productRole__tags'));
    });
</script>
