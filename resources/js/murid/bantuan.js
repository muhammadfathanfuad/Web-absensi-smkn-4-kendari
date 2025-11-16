// Bantuan JavaScript
// This file handles help/FAQ functionality for students

function searchFAQ() {
    const searchFAQInput = document.getElementById('searchFAQ');
    if (!searchFAQInput) return;
    
    const searchTerm = searchFAQInput.value.toLowerCase();
    const accordionItems = document.querySelectorAll('.accordion-item');
    
    accordionItems.forEach(item => {
        const button = item.querySelector('.accordion-button');
        const content = item.querySelector('.accordion-body');
        
        if (!button || !content) return;
        
        // Get original text content (before highlighting)
        const originalText = content.getAttribute('data-original-text') || content.textContent;
        if (!content.getAttribute('data-original-text')) {
            content.setAttribute('data-original-text', content.textContent);
        }
        
        const text = (button.textContent + ' ' + originalText).toLowerCase();
        
        if (text.includes(searchTerm)) {
            item.style.display = '';
            // Highlight search term
            if (searchTerm) {
                const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                content.innerHTML = originalText.replace(regex, '<mark>$1</mark>');
            } else {
                // Restore original content if no search term
                content.innerHTML = originalText;
            }
        } else {
            item.style.display = 'none';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const searchFAQInput = document.getElementById('searchFAQ');
    
    if (searchFAQInput) {
        searchFAQInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchFAQ();
            }
        });
    }
});

// Expose function to global scope for onclick handlers
window.searchFAQ = searchFAQ;

