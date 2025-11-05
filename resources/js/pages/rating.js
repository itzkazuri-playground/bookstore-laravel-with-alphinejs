document.addEventListener('alpine:init', () => {
    // Register the rating component with Alpine.js
    Alpine.data('ratingData', () => ({
        allAuthors: [],
        filteredAuthors: [],
        books: [],
        searchTerm: '',
        showAuthorDropdown: false,
        highlightedAuthorIndex: -1,
        form: {
            author_id: '',
            book_id: '',
            voter_name: '', // This will now be ignored since we use authenticated user ID
            rating: 5
        },
        hoverRating: null,
        submitting: false,
        message: '',
        messageType: '', // 'success' or 'error',
        
        init() {
            this.loadAllAuthors();
        },
        
        loadAllAuthors() {
            fetch('/api/authors/dropdown')
                .then(response => response.json())
                .then(data => {
                    this.allAuthors = data.data || [];
                    this.filteredAuthors = [...this.allAuthors];
                })
                .catch(error => {
                    console.error('Error loading authors:', error);
                    this.message = 'Error loading authors. Please try again later.';
                    this.messageType = 'error';
                });
        },
        
        searchAuthors() {
            if (!this.searchTerm) {
                this.filteredAuthors = [...this.allAuthors];
            } else {
                this.filteredAuthors = this.allAuthors.filter(author => 
                    author.name.toLowerCase().includes(this.searchTerm.toLowerCase())
                );
            }
            this.highlightedAuthorIndex = -1;
            this.showAuthorDropdown = true;
        },
        
        selectAuthor(author) {
            this.form.author_id = author.id;
            this.searchTerm = author.name;
            this.showAuthorDropdown = false;
            this.form.book_id = '';
            this.books = [];
            
            if (author.id) {
                this.loadBooksByAuthor(author.id);
            }
        },
        
        highlightNextAuthor() {
            if (this.highlightedAuthorIndex < this.filteredAuthors.length - 1) {
                this.highlightedAuthorIndex++;
            }
        },
        
        highlightPrevAuthor() {
            if (this.highlightedAuthorIndex > 0) {
                this.highlightedAuthorIndex--;
            }
        },
        
        selectHighlightedAuthor() {
            if (this.highlightedAuthorIndex >= 0 && this.filteredAuthors[this.highlightedAuthorIndex]) {
                this.selectAuthor(this.filteredAuthors[this.highlightedAuthorIndex]);
                this.highlightedAuthorIndex = -1;
            }
        },
        
        loadBooksByAuthor(authorId) {
            fetch(`/api/books?author_id=${authorId}`)
                .then(response => response.json())
                .then(data => {
                    this.books = data.data || [];
                })
                .catch(error => {
                    console.error('Error loading books:', error);
                    this.message = 'Error loading books for this author. Please try again later.';
                    this.messageType = 'error';
                });
        },
        
        submitRating() {
            this.submitting = true;
            
            fetch('/api/ratings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    book_id: parseInt(this.form.book_id),
                    rating: parseInt(this.form.rating),
                    voter_name: this.form.voter_name // Still sending this for validation but it won't be used
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Error submitting rating');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Show success alert
                Swal.fire({
                    title: 'Success!',
                    text: 'Your rating has been submitted successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#667eea',
                    timer: 2000,
                    timerProgressBar: true,
                    willClose: () => {
                        // Reset form
                        this.form = {
                            author_id: '',
                            book_id: '',
                            voter_name: '',
                            rating: 5
                        };
                        this.searchTerm = '';
                        this.books = [];
                        this.allAuthors = [];
                        this.filteredAuthors = [];
                        this.hoverRating = null; // Reset hover rating
                        this.loadAllAuthors(); // Reload authors to keep search functional
                        
                        // Go back to home after successful rating
                        window.location.href = '/home';
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                // Check if the error is about authentication
                if (error.message.includes('Authentication required') || error.message.includes('log in')) {
                    // Redirect to login page
                    window.location.href = '/login';
                    return;
                }
                
                // Show error alert
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#e53e3e'
                });
            })
            .finally(() => {
                this.submitting = false;
            });
        },
        
        goHome() {
            window.location.href = '/home';
        }
    }));
});
