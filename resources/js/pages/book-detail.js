document.addEventListener('alpine:init', () => {
    Alpine.data('bookData', () => {
        // Get the book data from the data attributes
        const bookDataElement = document.getElementById('book-data');
        const bookId = bookDataElement.dataset.bookId;
        const averageRating = parseFloat(bookDataElement.dataset.averageRating);
        const totalRatings = parseInt(bookDataElement.dataset.totalRatings);
        const userRating = parseInt(bookDataElement.dataset.userRating);

        return {
            bookId: bookId,
            averageRating: averageRating,
            totalRatings: totalRatings,
            currentRating: userRating,
            hoverRating: userRating,
            submitting: false,
            message: '',
            messageType: '', // 'success' or 'error',
            
            init() {
                // Initialize with the user's existing rating
                this.hoverRating = this.currentRating;
            },
            
            submitRating() {
                this.submitting = true;
                
                fetch(`/api/books/${this.bookId}/rate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        rating: parseInt(this.currentRating)
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
                            window.location.reload();
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
            
            availabilityStatusClass(status) {
                switch(status) {
                    case 'available':
                        return 'bg-green-500';
                    case 'rented':
                        return 'bg-yellow-500';
                    case 'reserved':
                        return 'bg-red-500';
                    default:
                        return 'bg-gray-500';
                }
            }
        }
    });
});