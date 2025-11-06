// resources/js/pages/dashboard.js
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboard', () => ({
        currentRatingId: null,
        isDeleteModalOpen: false,
        isUserMenuOpen: false,
        
        openDeleteModal(ratingId) {
            this.currentRatingId = ratingId;
            this.isDeleteModalOpen = true;
        },
        
        closeDeleteModal() {
            this.isDeleteModalOpen = false;
            this.currentRatingId = null;
        },
        
        toggleUserMenu() {
            this.isUserMenuOpen = !this.isUserMenuOpen;
        },
        
        closeUserMenu() {
            this.isUserMenuOpen = false;
        },
        
        async confirmDelete() {
            if (!this.currentRatingId) return;
            
            try {
                const response = await fetch(`/api/ratings/${this.currentRatingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Reload the page or remove the row from the table
                    location.reload();
                } else {
                    alert('Error deleting rating');
                    this.closeDeleteModal();
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting rating');
                this.closeDeleteModal();
            }
        }
    }));
});