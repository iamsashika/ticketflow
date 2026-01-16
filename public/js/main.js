/**
 * Main JavaScript for Event Registration and Ticketing System
 * Handles animations, interactions, and dynamic effects
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {

    // ========== Card Animation on Scroll ==========
    const cards = document.querySelectorAll('.card');

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const cardObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    entry.target.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, 50);

                cardObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    cards.forEach(card => {
        cardObserver.observe(card);
    });

    // ========== Smooth Scroll for Anchor Links ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ========== Auto-hide Flash Messages ==========
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Add fade-in animation
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '1';
            alert.style.transform = 'translateY(0)';
        }, 100);

        // Auto-hide after 5 seconds
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // ========== Form Input Animations ==========
    const formInputs = document.querySelectorAll('.form-input, .form-textarea, .form-select');

    formInputs.forEach(input => {
        // Add floating label effect
        input.addEventListener('focus', function () {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function () {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });

        // Check if already has value on load
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });

    // ========== Button Ripple Effect (Already in CSS) ==========
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function (e) {
            this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        });
    });

    // ========== Table Row Highlight ==========
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function () {
            // Remove highlight from all rows
            tableRows.forEach(r => r.style.background = '');

            // Highlight clicked row briefly
            this.style.background = 'linear-gradient(90deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%)';
        });
    });

    // ========== Navbar Scroll Effect ==========
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            navbar.style.padding = '0.75rem 0';
        } else {
            navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
            navbar.style.padding = '1rem 0';
        }

        lastScroll = currentScroll;
    });

    // ========== Number Counter Animation for Stats ==========
    const statNumbers = document.querySelectorAll('.card-body h3');

    const countObserver = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const text = target.textContent;

                // Check if it's a number
                const numberMatch = text.match(/[\d,]+/);
                if (numberMatch) {
                    const finalNumber = parseInt(numberMatch[0].replace(/,/g, ''));
                    if (!isNaN(finalNumber)) {
                        animateNumber(target, 0, finalNumber, 1500);
                    }
                }

                countObserver.unobserve(entry.target);
            }
        });
    }, observerOptions);

    statNumbers.forEach(num => {
        countObserver.observe(num);
    });

    function animateNumber(element, start, end, duration) {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        // Store the original text to check for % or decimal
        const originalText = element.textContent;
        const hasPercent = originalText.includes('%');
        const hasDecimal = originalText.includes('.');

        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }

            let formatted;
            if (hasDecimal) {
                // Keep decimal for percentages
                formatted = current.toFixed(1);
            } else {
                formatted = Math.floor(current).toLocaleString();
            }

            // Preserve any text before/after the number
            if (originalText.includes('Rs.')) {
                element.textContent = 'Rs. ' + formatted;
            } else if (hasPercent) {
                element.textContent = formatted + '%';
            } else {
                element.textContent = formatted;
            }
        }, 16);
    }

    console.log('Event Ticketing System initialized with enhanced animations');
});
