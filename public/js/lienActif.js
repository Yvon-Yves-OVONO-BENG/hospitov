document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            document.querySelectorAll('a').forEach(function (el) {
                el.classList.remove('active-sub');
            });
            this.classList.add('active-sub');
        });
    });
});