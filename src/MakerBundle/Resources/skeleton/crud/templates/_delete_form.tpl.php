<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">
                    {{ 'admin.common.delete_modal__title'|trans }}
                </h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <p class="text-left modal-message">&nbsp;</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ec-sub" type="button" data-dismiss="modal">
                    {{ 'admin.common.cancel'|trans }}
                </button>
                <a class="btn btn-ec-delete" href="#" {{ csrf_token_for_anchor() }}
                    data-method="delete" data-confirm="false">
                    {{ 'admin.common.delete'|trans }}
                </a>
            </div>
        </div>
    </div>
</div>
