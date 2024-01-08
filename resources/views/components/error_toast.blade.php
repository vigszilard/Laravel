@if(session('error'))
    <div id="toast-container">
        <div id="error-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" tabindex="-1" data-autohide="false" style="opacity:1;">
            <div class="toast-header">
                <i class="fas fa-exclamation-circle text-danger mr-2"></i>
                <strong class="mr-auto">Error</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onClick="hideErrorToast()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
