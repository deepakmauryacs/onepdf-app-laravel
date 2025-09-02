        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Demo interactivity
        document.querySelectorAll('.form-check-input').forEach(input => {
            input.addEventListener('change', function() {
                const card = this.closest('.border');
                if (this.checked) {
                    card.classList.add('border-primary');
                } else {
                    card.classList.remove('border-primary');
                }
            });
        });

