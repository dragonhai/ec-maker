{{ form_start(form) }}
    {{ form_widget(form) }}
    <button class="btn btn-ec-conversion">{{ (<?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>?'admin.common.edit':'admin.common.save')|trans }}</button>
    {% if <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> %}
        <a 
            href="#" 
            class="btn btn-ec-delete"
            data-toggle="modal"
            data-target="#DeleteModal"
            data-url="{{ url('admin_<?= $route_name ?>_<?= $route_name ?>_delete', {'<?= $entity_identifier ?>' : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}"
            data-message="{{ 'admin.common.delete_modal__message'|trans({ '%name%' : <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?> }) }}"
        >
            {{ 'admin.common.delete'|trans }}
        </a>
    {% endif %}
{{ form_end(form) }}
