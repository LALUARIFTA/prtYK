document.addEventListener('DOMContentLoaded', () => {
    // 0. Premium Preloader
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.remove(), 1000);
            }, 500);
        });
    }

    // 1. Mobile Menu Toggle
    const menuToggle = document.getElementById('mobile-menu');
    const navLinks = document.querySelector('.nav-links');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    // 2. Navbar Scroll Effect
    const nav = document.querySelector('.glass-nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });

    // 3. Banner Slider
    const slides = document.querySelectorAll('.slide');
    if (slides.length > 1) {
        let currentSlide = 0;
        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        }, 5000);
    }

    // 4. Scroll Reveal Animation (Enhanced)
    const reveals = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                // Unobserve after revealing to save resources
                // revealObserver.unobserve(entry.target); 
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    reveals.forEach(el => revealObserver.observe(el));

    // 5. Project Filtering
    const filterBtns = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.card[data-category]');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');

            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    setTimeout(() => card.style.opacity = '1', 10);
                } else {
                    card.style.opacity = '0';
                    setTimeout(() => card.style.display = 'none', 300);
                }
            });
        });
    });

    // 6. Lightbox Functionality
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = lightbox.querySelector('img');
    const triggerImgs = document.querySelectorAll('.card-img img');

    triggerImgs.forEach(img => {
        img.addEventListener('click', (e) => {
            // Prevent lightbox for website visit buttons
            if (e.target.closest('.visit-btn')) return;

            lightboxImg.src = img.src;
            lightbox.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Stop scroll
        });
    });

    // 7. Back to Top
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTop.classList.add('active');
        } else {
            backToTop.classList.remove('active');
        }
    });

    backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Chatbot logic
    const chatBubble = document.getElementById('chatBubble');
    const chatWindow = document.getElementById('chatWindow');
    const closeChat = document.getElementById('closeChat');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');

    if (chatBubble) {
        chatBubble.addEventListener('click', () => {
            chatWindow.style.display = 'flex';
            chatBubble.style.display = 'none';
        });

        closeChat.addEventListener('click', () => {
            chatWindow.style.display = 'none';
            chatBubble.style.display = 'flex';
        });

        const botResponses = window.BOT_KNOWLEDGE || {};

        const getBotResponse = async (input) => {
            const lowerInput = input.toLowerCase();
            // Check local knowledge first
            for (let key in botResponses) {
                if (lowerInput.includes(key.toLowerCase())) return botResponses[key];
            }

            // If no match, use Gemini AI
            try {
                const res = await fetch('includes/chat_ai.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ message: input })
                });
                const data = await res.json();
                return data.response;
            } catch (err) {
                return "Maaf, otak AI saya sedang offline. Silakan coba lagi nanti.";
            }
        };

        const addMessage = (text, type) => {
            const msg = document.createElement('div');
            msg.className = `message ${type}`;
            msg.textContent = text;
            chatMessages.appendChild(msg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;

            addMessage(text, 'user');
            chatInput.value = '';

            // Loading indicator
            const loading = document.createElement('div');
            loading.className = 'message bot';
            loading.textContent = 'Mengetik...';
            chatMessages.appendChild(loading);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            const response = await getBotResponse(text);
            loading.remove();
            addMessage(response, 'bot');
        });
    }

    // 9. Text Scramble Animation
    const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*";
    const scrambleElements = document.querySelectorAll('.text-scramble-wrapper');

    scrambleElements.forEach(wrapper => {
        const textEl = wrapper.querySelector('.scramble-text');
        if (!textEl) return;

        const originalText = textEl.innerText;
        let isScrambling = false;
        let interval = null;

        const scramble = () => {
            if (isScrambling) return;
            isScrambling = true;

            let frame = 0;
            const duration = originalText.length * 3;

            if (interval) clearInterval(interval);

            interval = setInterval(() => {
                frame++;
                const progress = frame / duration;
                const revealedLength = Math.floor(progress * originalText.length);

                const result = originalText.split('').map((char, i) => {
                    if (char === ' ') return ' ';
                    if (i < revealedLength) return originalText[i];
                    return CHARS[Math.floor(Math.random() * CHARS.length)];
                }).join('');

                textEl.innerText = result;

                if (frame >= duration) {
                    clearInterval(interval);
                    textEl.innerText = originalText;
                    isScrambling = false;
                }
            }, 30);
        };

        wrapper.addEventListener('mouseenter', scramble);
    });
});

// --- Project Filter (Slider Toggle) ---
document.addEventListener('DOMContentLoaded', () => {
    const filterBtns = document.querySelectorAll('.filter-btn[data-filter]');
    const sliderSections = document.querySelectorAll('.design-slider-section[data-category]');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.dataset.filter;

            sliderSections.forEach(section => {
                if (filter === 'all' || section.dataset.category === filter) {
                    section.style.display = '';
                    section.style.opacity = '1';
                    section.style.transform = 'translateY(0)';
                } else {
                    section.style.opacity = '0';
                    section.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        section.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
