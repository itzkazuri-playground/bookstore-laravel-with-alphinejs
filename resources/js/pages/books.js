
window.booksData = function () {
    return {
        books: [],
        categories: [],
        authors: [],
        searchQuery: '',
        filters: {
            categories: [],
            author_id: '',
            from_year: '',
            to_year: '',
            min_rating: '',
            max_rating: ''
        },
        sortBy: 'average_rating',
        loading: false,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 15,
            total: 0,
            from: 1,
            to: 15
        },
        showCategoryDropdown: false,
        filterCategorySearch: '',

        init() {
            this.loadBooks();
            this.loadCategories();
            this.loadAuthors();
        },

        get filteredCategories() {
            if (!this.filterCategorySearch) {
                return this.categories;
            }
            return this.categories.filter(category =>
                category.name.toLowerCase().includes(this.filterCategorySearch.toLowerCase())
            );
        },

        loadBooks() {
            this.loading = true;

            const params = new URLSearchParams({
                search: this.searchQuery,
                author_id: this.filters.author_id,
                from_year: this.filters.from_year,
                to_year: this.filters.to_year,
                min_rating: this.filters.min_rating,
                max_rating: this.filters.max_rating,
                categories: this.filters.categories.join(','),
                sort_by: this.sortBy,
                page: this.pagination.current_page
            });

            fetch(`/api/books?${params}`)
                .then(response => response.json())
                .then(data => {
                    this.books = data.data || [];
                    this.pagination = {
                        current_page: data.current_page || 1,
                        last_page: data.last_page || 1,
                        per_page: data.per_page || 15,
                        total: data.total || 0,
                        from: data.from || 1,
                        to: data.to || 15
                    };
                })
                .catch(error => {
                    console.error('Error loading books:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        loadCategories() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(data => {
                    this.categories = data.data || [];
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                });
        },

        loadAuthors() {
            fetch('/api/authors/dropdown')
                .then(response => response.json())
                .then(data => {
                    this.authors = data.data || [];
                })
                .catch(error => {
                    console.error('Error loading authors:', error);
                });
        },

        goToPage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.pagination.current_page = page;
                this.loadBooks();
            }
        },

        getPageNumbers() {
            const pages = [];
            const start = Math.max(1, this.pagination.current_page - 2);
            const end = Math.min(this.pagination.last_page, this.pagination.current_page + 2);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            return pages;
        },

        toggleCategory(categoryId) {
            const index = this.filters.categories.indexOf(categoryId);
            if (index > -1) {
                this.filters.categories.splice(index, 1);
            } else {
                this.filters.categories.push(categoryId);
            }
            this.loadBooks();
        },

        removeCategory(categoryId) {
            const index = this.filters.categories.indexOf(categoryId);
            if (index > -1) {
                this.filters.categories.splice(index, 1);
                this.loadBooks();
            }
        },

        isCategorySelected(categoryId) {
            return this.filters.categories.includes(categoryId);
        },

        getCategoryName(categoryId) {
            const category = this.categories.find(cat => cat.id == categoryId);
            return category ? category.name : '';
        },

        handleInputDelete() {
            if (this.filterCategorySearch === '') {
                if (this.filters.categories.length > 0) {
                    const lastCategoryId = this.filters.categories[this.filters.categories.length - 1];
                    this.removeCategory(lastCategoryId);
                }
            }
        },

        addNewCategory(categoryName) {
            alert('Adding new category functionality would go here: ' + categoryName);
        },

        clearCategoryFilters() {
            this.filters.categories = [];
            this.loadBooks();
        }
    };
};
