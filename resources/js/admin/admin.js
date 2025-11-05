// Admin-specific JavaScript

function initAutocomplete() {
    const searchInputs = document.querySelectorAll('[data-autocomplete="true"]');
    
    searchInputs.forEach(input => {
        const type = input.getAttribute('data-type');
        const url = input.getAttribute('data-url');
        
        let timeout;
        
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                return;
            }
            
            timeout = setTimeout(() => {
                fetch(`${url}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => showSuggestions(data, this));
            }, 300);
        });
        
        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target)) {
                const suggestionsBox = input.parentNode.querySelector('.suggestions-box');
                if (suggestionsBox) {
                    suggestionsBox.style.display = 'none';
                }
            }
        });
    });
}

function showSuggestions(data, input) {
    // Remove existing suggestions box
    const existingBox = input.parentNode.querySelector('.suggestions-box');
    if (existingBox) {
        existingBox.remove();
    }
    
    // Create new suggestions box
    const suggestionsBox = document.createElement('div');
    suggestionsBox.className = 'suggestions-box';
    
    if (data.length === 0) {
        const noResult = document.createElement('div');
        noResult.className = 'px-4 py-2 text-gray-500 text-sm';
        noResult.textContent = 'No results found';
        suggestionsBox.appendChild(noResult);
    } else {
        data.forEach(item => {
            const suggestion = document.createElement('div');
            suggestion.className = 'px-4 py-2 hover:bg-gray-100 cursor-pointer suggestion-item';
            
            if (input.getAttribute('data-type') === 'books') {
                suggestion.innerHTML = `<div><strong>${item.title}</strong><br><small class="text-gray-500">${item.author}</small></div>`;
                suggestion.addEventListener('click', function() {
                    input.value = item.title;
                    // If there's an author select field, update it too
                    const authorSelect = document.querySelector('#author_id');
                    if (authorSelect && item.author_id) {
                        authorSelect.value = item.author_id;
                    }
                });
            } else {
                suggestion.textContent = item.name;
                suggestion.addEventListener('click', function() {
                    input.value = item.name;
                });
            }
            
            suggestionsBox.appendChild(suggestion);
        });
    }
    
    input.parentNode.style.position = 'relative';
    input.parentNode.appendChild(suggestionsBox);
}

document.addEventListener('DOMContentLoaded', initAutocomplete);