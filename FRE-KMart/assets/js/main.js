// SongSong Mart - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileMenuToggle && navMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
        });
    }

    // Mobile dropdown toggles
    const mobileDropdowns = document.querySelectorAll('.dropdown');
    mobileDropdowns.forEach(dropdown => {
        const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
        if (dropdownToggle) {
            dropdownToggle.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    // Only prevent default on mobile to show dropdown
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                } else {
                    // On desktop, allow normal navigation to products.php
                    // The dropdown will work on hover (CSS handles this)
                }
            });
        }
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.main-nav') && !e.target.closest('.mobile-menu-toggle')) {
            navMenu?.classList.remove('active');
            mobileMenuToggle?.classList.remove('active');
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            mobileDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }
    });

    // Header scroll effect (simplified for new design)
    let lastScrollTop = 0;
    const header = document.querySelector('.header');
    
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down - slightly fade header
            header.style.opacity = '0.95';
        } else {
            // Scrolling up - full opacity
            header.style.opacity = '1';
        }
        
        lastScrollTop = scrollTop;
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Category filter active state
    const categoryBtns = document.querySelectorAll('.category-btn');
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
        });
    });

    // Lazy loading for product images
    const productImages = document.querySelectorAll('.product-image img');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        productImages.forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Contact form validation
    const contactForm = document.querySelector('form[action="contact.php"]');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = this.querySelector('[name="name"]').value.trim();
            const email = this.querySelector('[name="email"]').value.trim();
            const subject = this.querySelector('[name="subject"]').value;
            const message = this.querySelector('[name="message"]').value.trim();
            
            let errors = [];
            
            if (!name) errors.push('Name is required');
            if (!email) errors.push('Email is required');
            if (!isValidEmail(email)) errors.push('Please enter a valid email');
            if (!subject) errors.push('Please select a subject');
            if (!message) errors.push('Message is required');
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Please fix the following errors:\n• ' + errors.join('\n• '));
            }
        });
    }

    // Email validation helper
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Price formatting animation
    const prices = document.querySelectorAll('.current-price');
    prices.forEach(price => {
        price.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        price.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Add loading states for buttons
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Add loading state for form submissions
            if (this.type === 'submit') {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                this.disabled = true;
            }
        });
    });

    // Initialize tooltips (if needed)
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.cssText = `
                position: absolute;
                background: #333;
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 12px;
                z-index: 1000;
                pointer-events: none;
            `;
            document.body.appendChild(tooltip);
            this.tooltipElement = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this.tooltipElement) {
                document.body.removeChild(this.tooltipElement);
                this.tooltipElement = null;
            }
        });
        
        element.addEventListener('mousemove', function(e) {
            if (this.tooltipElement) {
                this.tooltipElement.style.left = (e.pageX + 10) + 'px';
                this.tooltipElement.style.top = (e.pageY - 30) + 'px';
            }
        });
    });

    // Performance optimization: Debounce scroll events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Apply debouncing to scroll events
    const debouncedScroll = debounce(function() {
        // Add any scroll-based functionality here
    }, 100);

    window.addEventListener('scroll', debouncedScroll);

    // Console log for development
    console.log('SongSong Mart website loaded successfully! 🍜');
    console.log('Visit us at our physical stores or shop online!');
});

// Utility functions
const SongSongMart = {
    // Format price in Philippine Peso
    formatPrice: function(price) {
        return '₱' + Number(price).toLocaleString('en-PH', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    },
    
    // Show notification (can be enhanced with a proper notification library)
    showNotification: function(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            color: white;
            border-radius: 5px;
            z-index: 9999;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
};

// Make utility functions globally available
window.SongSongMart = SongSongMart;