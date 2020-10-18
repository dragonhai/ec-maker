{% extends '@admin/default_frame.twig' %}
{% set menus = ['<?= $route_name ?>', '<?= $route_name ?>_master'] %}

{% block title %}{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_management'|trans }}{% endblock %}

{% block sub_title %}{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_list'|trans }}{% endblock %}

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
                        <thead>
                            <tr>
<?php foreach ($entity_fields as $field): ?>
                                <th>{{ 'admin.<?= $route_name ?>.<?= $field['fieldName'] ?>'|trans }}</th>
<?php endforeach; ?>
                                <th>{{ 'admin.<?= $route_name ?>.actions'|trans }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
                            <tr>
<?php foreach ($entity_fields as $field): ?>
                                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
<?php endforeach; ?>
                                <td>
                                    <a 
                                        class="btn btn-ec-actionIcon"
                                        href="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_show', { <?= $entity_identifier ?> : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
                                    >
                                        <i class="fa fa-eye fa-lg text-secondary"></i>
                                    </a>
                                    <a 
                                        href="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_edit', { <?= $entity_identifier ?> : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}" 
                                        class="btn btn-ec-actionIcon mr-2 action-edit" 
                                        data-tooltip="true" 
                                        data-placement="top" 
                                        title="{{ 'admin.common.edit'|trans }}"
                                    >
                                        <i class="fa fa-pencil fa-lg text-secondary"></i>
                                    </a>
                                    <a 
                                        class="btn btn-ec-actionIcon" 
                                        data-tooltip="true" 
                                        data-placement="top" 
                                        title="{{ 'admin.common.delete'|trans }}"
                                        data-toggle="modal"
                                        data-target="#DeleteModal"
                                        data-url="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_delete', {'<?= $entity_identifier ?>' : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}"
                                        data-message="{{ 'admin.common.delete_modal__message'|trans({ '%name%' : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
                                    >
                                        <i class="fa fa-close fa-lg text-secondary"></i>
                                    </a>
                                </td>
                            </tr>
                            {% else %}
                                <tr>
                                    <td colspan="<?= (count($entity_fields) + 1) ?>">{{ 'admin.<?= $route_name ?>.<?= $route_name ?>_not_found'|trans }}</td>
                                </tr>
                            {% endfor %}
                        </tbody>
                    </table>
                    <a class="btn btn-ec-conversion" href="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_new') }}">{{ 'admin.common.create__new'|trans }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
{% include '@admin/<?= ucfirst($route_name) ?>/_delete_form.twig' %}
{% endblock %}
