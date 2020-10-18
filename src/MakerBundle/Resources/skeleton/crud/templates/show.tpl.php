{% extends '@admin/default_frame.twig' %}
{% set menus = ['<?= $route_name ?>', '<?= $route_name ?>_master'] %}

{% block title %}{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_management'|trans }}{% endblock %}

{% block sub_title %}{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_detail'|trans }}{% endblock %}

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
                    <table class="table">
                        <tbody>
<?php foreach ($entity_fields as $field): ?>
                            <tr>
                                <th>{{ 'admin.<?= $route_name ?>.<?= $field['fieldName'] ?>'|trans }}</th>
                                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="controls">
                        <a 
                            href="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_edit', { <?= $entity_identifier ?> : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
                            class="btn btn-ec-conversion"
                        >
                            {{ 'admin.common.go_to_edit_page'|trans }}
                        </a>
                        <a 
                            href="#" 
                            class="btn btn-ec-delete"
                            data-toggle="modal"
                            data-target="#DeleteModal"
                            data-url="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_delete', { <?= $entity_identifier ?> : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
                            data-message="{{ 'admin.common.delete_modal__message'|trans({ '%name%' : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
                        >
                            {{ 'admin.common.delete'|trans }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{% include '@admin/<?= ucfirst($route_name) ?>/_delete_form.twig' %}
{% endblock %}
