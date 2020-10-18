{% extends '@admin/default_frame.twig' %}

{% set menus = ['<?= $route_name ?>', <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> ? '<?= $route_name ?>_master' : '<?= $route_name ?>_new'] %}

{% block title %}{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_management'|trans }}{% endblock %}

{% block sub_title %}{{ (<?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> ? 'admin.<?= $route_name ?>.<?= $route_name ?>_edit' : 'admin.<?= $route_name ?>.<?= $route_name ?>_new')|trans }}{% endblock %}

{% block javascript %}
    <script>
        $(function() {
            $('#DeleteModal').on('shown.bs.modal', function(event) {
                var target = $(event.relatedTarget);
                $(this).find('[data-method="delete"]').attr('href', target.data('url'));
                $(this).find('p.modal-message').text(target.data('message'));
            });
        });
    </script>
{% endblock %}

{% block main %}
<div class="c-contentsArea__cols">
    <div class="c-contentsArea__primaryCol">
        <div class="c-primaryCol">
            <div class="card rounded border-0 mb-4">
                <div class="card-body">
                    {% include '@admin/<?= ucfirst($route_name) ?>/_form.twig' %}
                </div>
            </div>
        </div>
    </div>
</div>
{% include '@admin/<?= ucfirst($route_name) ?>/_delete_form.twig' %}
{% endblock %}
