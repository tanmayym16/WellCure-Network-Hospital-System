/**
 * WellCure Network Hospital - Enhanced JavaScript with Modern Animations
 * This file handles all interactive elements and animations of the website
 */
document.addEventListener('DOMContentLoaded', () => {
    // Initialize page loader
    initPageLoader();

    // Initialize critical components immediately
    initDarkModeToggle();
    initHeaderScroll();

    // Defer non-critical initializations
    requestIdleCallback(() => {
        initSmoothScroll();
        initFadeInElements();
    });

    // Initialize animations after a slight delay to prioritize content rendering
    if (window.innerWidth > 768) { // Only run heavy animations on desktop
        setTimeout(() => {
            initGSAPAnimations();
            initTestimonialSlider();
        }, 100);
    } else {
        // Simplified animations for mobile
        initTestimonialSlider();
    }
});

// Fallback for browsers that don't support requestIdleCallback
if (!window.requestIdleCallback) {
    window.requestIdleCallback = function(callback) {
        return setTimeout(function() {
            const start = Date.now();
            callback({
                didTimeout: false,
                timeRemaining: function() {
                    return Math.max(0, 50 - (Date.now() - start));
                }
            });
        }, 1);
    };
}

/**
 * Page Loader - Shows loading animation until page is fully loaded
 */
function initPageLoader() {
    const loader = document.querySelector('.page-loader');

    // Hide loader when page is fully loaded
    window.addEventListener('load', () => {
        loader.classList.add('loaded');

        // Remove loader from DOM after animation completes
        setTimeout(() => {
            loader.style.display = 'none';
        }, 600); // Match transition duration
    });
}

/**
 * Header Scroll Effect - Changes header appearance on scroll
 * Uses throttling for better performance
 */
function initHeaderScroll() {
    const header = document.querySelector('header');
    const scrollThreshold = 50;
    let lastScrollY = 0;
    let ticking = false;

    function updateHeader() {
        if (lastScrollY > scrollThreshold) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        ticking = false;
    }

    window.addEventListener('scroll', () => {
        lastScrollY = window.scrollY;

        if (!ticking) {
            window.requestAnimationFrame(() => {
                updateHeader();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true }); // Add passive flag for better performance
}

/**
 * GSAP Animations - Enhanced animations using GSAP library
 * Optimized for performance with reduced animations on mobile
 */
function initGSAPAnimations() {
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded. Skipping advanced animations.');
        return;
    }

    // Register ScrollTrigger plugin
    if (typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);

        // Set default ScrollTrigger options for better performance
        ScrollTrigger.config({
            limitCallbacks: true, // Limits callbacks to once per scroll direction
            ignoreMobileResize: true // Reduces recalculation on mobile resize events
        });

        // Batch similar animations together for better performance
        const commonOptions = {
            opacity: 0,
            duration: 0.7,
            ease: 'power2.out',
            markers: false
        };

        // Use a single timeline for card animations to reduce overhead
        const cardTimeline = gsap.timeline({
            scrollTrigger: {
                trigger: '.card-grid',
                start: 'top 85%',
                toggleActions: 'play none none none',
                once: true // Only trigger once for better performance
            }
        });

        // Get all cards and animate them with reduced stagger
        const cards = document.querySelectorAll('.card');
        if (cards.length) {
            // Use a more efficient stagger approach
            cardTimeline.from(cards, {
                ...commonOptions,
                y: 30,
                stagger: {
                    each: 0.1,
                    from: "center",
                    grid: "auto"
                }
            });
        }

        // Animate section titles with a single batch
        gsap.from('.section-title', {
            ...commonOptions,
            y: 20,
            scrollTrigger: {
                trigger: '.section-title',
                start: 'top 85%',
                toggleActions: 'play none none none',
                once: true
            }
        });

        // Only animate about section if it exists
        const aboutSection = document.querySelector('.about-section');
        if (aboutSection) {
            const aboutTimeline = gsap.timeline({
                scrollTrigger: {
                    trigger: aboutSection,
                    start: 'top 75%',
                    toggleActions: 'play none none none',
                    once: true
                }
            });

            // Animate content and image together
            aboutTimeline
                .from('.about-content', {
                    ...commonOptions,
                    x: -30,
                }, 0)
                .from('.about-image', {
                    ...commonOptions,
                    x: 30,
                }, 0.2); // Slight delay for better visual effect
        }
    }
}

/**
 * Testimonial Slider - Shows testimonials in a rotating carousel
 * Optimized with IntersectionObserver and reduced animation frequency
 */
function initTestimonialSlider() {
    const sliderContainer = document.querySelector('.testimonial-slider');
    if (!sliderContainer) return; // Exit if slider container not found

    const slides = sliderContainer.querySelectorAll('.slide');
    if (!slides.length) return; // Exit if no slides found

    let currentSlide = 0;
    let slideInterval = null;
    let isSliderVisible = false;
    let isPaused = false;
    const slideDelay = 6000; // Increased to 6 seconds for better readability

    // Preload slide images to prevent flicker during transitions
    slides.forEach(slide => {
        const img = slide.querySelector('img');
        if (img && img.src) {
            const preloadImg = new Image();
            preloadImg.src = img.src;
        }
    });

    // More efficient slide transition with minimal DOM operations
    function showSlide(index) {
        // Use direct indexing instead of querying the DOM again
        const activeSlide = sliderContainer.querySelector('.slide.active');
        if (activeSlide) activeSlide.classList.remove('active');

        // Add active class to current slide
        slides[index].classList.add('active');
    }

    // Initialize first slide
    showSlide(currentSlide);

    // Use more efficient setTimeout instead of requestAnimationFrame for this use case
    function startSlideshow() {
        if (slideInterval) clearTimeout(slideInterval);

        slideInterval = setTimeout(() => {
            if (!isPaused && isSliderVisible) {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }
            startSlideshow(); // Recursively call to continue the slideshow
        }, slideDelay);
    }

    // Use IntersectionObserver to only run slideshow when visible
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                isSliderVisible = entry.isIntersecting;
            });
        }, {
            root: null,
            threshold: 0.25 // Only consider visible when 25% in view
        });

        observer.observe(sliderContainer);
    } else {
        // Fallback for browsers without IntersectionObserver
        isSliderVisible = true;
    }

    // Start the slideshow
    startSlideshow();

    // Pause animation when page is not visible to save resources
    document.addEventListener('visibilitychange', () => {
        isPaused = document.hidden;
    }, { passive: true });

    // Add touch/mouse interaction for better UX
    let touchStartX = 0;

    sliderContainer.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
        isPaused = true; // Pause during interaction
    }, { passive: true });

    sliderContainer.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].clientX;
        const diff = touchEndX - touchStartX;

        if (Math.abs(diff) > 50) { // Minimum swipe distance
            if (diff > 0) {
                // Swipe right - go to previous slide
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            } else {
                // Swipe left - go to next slide
                currentSlide = (currentSlide + 1) % slides.length;
            }
            showSlide(currentSlide);
        }

        // Resume slideshow after a short delay
        setTimeout(() => { isPaused = false; }, 1000);
    }, { passive: true });
}

/**
 * Dark Mode Toggle - Handles theme switching and preference saving
 */
function initDarkModeToggle() {
    const themeToggleButton = document.getElementById('toggle-mode');
    if (!themeToggleButton) return; // Exit if button not found

    // Apply saved theme preference
    const savedTheme = localStorage.getItem('theme');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;

    // Use system preference if no saved preference
    if (savedTheme === 'dark' || (!savedTheme && prefersDarkScheme)) {
        document.body.classList.add('dark-mode');
        themeToggleButton.textContent = '☀️'; // Sun icon
    } else {
        themeToggleButton.textContent = '🌙'; // Moon icon
    }

    // Handle theme toggle with debounce
    let debounceTimer;
    themeToggleButton.addEventListener('click', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.body.classList.toggle('dark-mode');
            const isDarkMode = document.body.classList.contains('dark-mode');
            themeToggleButton.textContent = isDarkMode ? '☀️' : '🌙';
            localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        }, 50); // Small debounce to prevent multiple rapid clicks
    });
}

/**
 * Smooth Scroll - Handles smooth scrolling for navigation links
 */
function initSmoothScroll() {
    // Select both pure anchor links and links with anchors in URLs
    const navLinks = document.querySelectorAll('.nav-links a[href^="#"], .nav-links a[href*="#"]');

    // Also handle direct links to sections from other pages
    const directLinks = document.querySelectorAll('a[href*="index.php#"]');

    // Function to handle smooth scrolling
    const smoothScrollToTarget = function(e) {
        // Get the full href attribute
        const href = this.getAttribute('href');

        // Check if this link has the direct-jump class
        if (this.classList.contains('direct-jump')) {
            // Let the browser handle the navigation directly
            return;
        }

        // Extract the anchor part (everything after the # symbol)
        const hashIndex = href.indexOf('#');
        if (hashIndex === -1) return; // No hash in the URL

        const targetId = href.substring(hashIndex);

        // Check if we're on the correct page for this anchor
        if (href.includes('index.php#') && !window.location.pathname.includes('index.php')) {
            // We're not on index.php, so let the browser handle the navigation
            return;
        }

        // If it's just an anchor or we're already on the correct page
        if (hashIndex === 0 || window.location.pathname.includes('index.php')) {
            e.preventDefault(); // Prevent default only if we're handling it

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                // Use native smooth scrolling with fallback
                if ('scrollBehavior' in document.documentElement.style) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    // Fallback for browsers that don't support smooth scrolling
                    window.scrollTo(0, targetElement.offsetTop);
                }

                // Update URL hash without page jump
                history.pushState(null, null, targetId);

                // Close mobile menu after click
                const menuToggle = document.getElementById('menu-toggle');
                if (menuToggle && menuToggle.checked) {
                    menuToggle.checked = false;
                }
            }
        }
    };

    // Add event listener to all navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', smoothScrollToTarget);
    });

    // Add event listener to all direct links
    directLinks.forEach(link => {
        link.addEventListener('click', smoothScrollToTarget);
    });

    // Handle initial hash in URL (for when page loads with a hash)
    if (window.location.hash) {
        setTimeout(() => {
            const targetElement = document.querySelector(window.location.hash);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }, 100); // Small delay to ensure page is fully loaded
    }
}

/**
 * Fade-in Elements - Uses Intersection Observer for better performance
 * This enhances the CSS animations by triggering them when elements come into view
 */
function initFadeInElements() {
    // Check if IntersectionObserver is supported
    if ('IntersectionObserver' in window) {
        const fadeElements = document.querySelectorAll('.fade-in');

        const fadeObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    fadeObserver.unobserve(entry.target); // Stop observing once visible
                }
            });
        }, {
            root: null, // viewport
            threshold: 0.1, // trigger when 10% visible
            rootMargin: '0px 0px -50px 0px' // trigger slightly before visible
        });

        fadeElements.forEach(element => {
            fadeObserver.observe(element);
        });
    }
}
