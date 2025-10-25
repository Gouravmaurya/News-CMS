// Admin Panel JavaScript

console.log('Admin panel loaded');

// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.querySelector('.admin-sidebar');
    sidebar.classList.toggle('active');
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.admin-sidebar');
    const toggle = document.querySelector('.mobile-menu-toggle');
    
    if (sidebar && toggle && window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// Confirm delete actions
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Form validation helper
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const required = form.querySelectorAll('[required]');
    let isValid = true;
    
    required.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = 'var(--danger)';
            isValid = false;
        } else {
            field.style.borderColor = '';
        }
    });
    
    return isValid;
}

// Image preview
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-generate slug from title
function generateSlug(titleInput, slugInput) {
    if (!titleInput || !slugInput) return;
    
    titleInput.addEventListener('input', function() {
        const slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        slugInput.value = slug;
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug if elements exist
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    if (titleInput && slugInput && !slugInput.value) {
        generateSlug(titleInput, slugInput);
    }
    
    // Image preview if file input exists
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            previewImage(this, 'image-preview');
        });
    }
});

// Show/hide media fields based on media type
function toggleMediaFields() {
    const mediaType = document.querySelector('input[name="media_type"]:checked');
    if (!mediaType) return;
    
    const imageField = document.getElementById('image-field');
    const videoField = document.getElementById('video-field');
    const urlField = document.getElementById('url-field');
    
    if (imageField) imageField.style.display = 'none';
    if (videoField) videoField.style.display = 'none';
    if (urlField) urlField.style.display = 'none';
    
    if (mediaType.value === 'image' && imageField) {
        imageField.style.display = 'block';
    } else if (mediaType.value === 'video' && videoField) {
        videoField.style.display = 'block';
    } else if (mediaType.value === 'url' && urlField) {
        urlField.style.display = 'block';
    }
}

// Show/hide custom date field
function toggleCustomDate() {
    const useCustom = document.getElementById('use_custom_date');
    const customDateField = document.getElementById('custom-date-field');
    
    if (useCustom && customDateField) {
        customDateField.style.display = useCustom.checked ? 'block' : 'none';
    }
}

// Initialize media type toggles
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeInputs = document.querySelectorAll('input[name="media_type"]');
    mediaTypeInputs.forEach(input => {
        input.addEventListener('change', toggleMediaFields);
    });
    toggleMediaFields();
    
    const useCustomDate = document.getElementById('use_custom_date');
    if (useCustomDate) {
        useCustomDate.addEventListener('change', toggleCustomDate);
        toggleCustomDate();
    }
});
