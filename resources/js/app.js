import './bootstrap';

import Alpine from 'alpinejs';
import './pages/books.js';  // Import books page JS to register the Alpine component
import './pages/rating.js';
import './pages/book-detail.js';

window.Alpine = Alpine;

Alpine.start();
