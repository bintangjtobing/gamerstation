<script>
    $(document).on("click", ".logout-btn", function(event) {

        event.preventDefault();
        var actionRoute = "{{ setRoute('user.logout') }}";
        var target = "auth()->user()->id";
        var message = `{{ __('Are You Sure To') }} <strong>{{ __('Logout') }}</strong>?`;
        var logout = `{{ __('Logout') }}`;
        openDeleteModal(actionRoute, target, message, logout, "POST");
    });
</script>
