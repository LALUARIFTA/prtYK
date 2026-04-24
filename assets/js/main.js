// --- 1. Supabase Initialization ---
const SUPABASE_URL = 'https://abrxshzkgshklgmaztlp.supabase.co';
const SUPABASE_ANON_KEY = 'sb_publishable_EGPAkhdoARUwWv7Q2K2gVw_3PnwahcQ'; 

let supabase = null;

// Initialize Supabase ONLY when the library is ready
function initSupabase() {
    if (window.supabase) {
        supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        console.log('Supabase initialized successfully.');
        fetchData();
    } else {
        console.error('Supabase library not found. Retrying in 500ms...');
        setTimeout(initSupabase, 500);
    }
}

// --- 2. Data Fetching ---
async function fetchData() {
    if (!supabase) return;
    
    try {
        const [
            { data: designs },
            { data: websites },
            { data: certificates },
            { data: skills },
            { data: partners },
            { data: testimonials },
            { data: knowledge }
        ] = await Promise.all([
            supabase.from('designs').select('*').order('id', { ascending: false }),
            supabase.from('websites').select('*').order('id', { ascending: false }),
            supabase.from('certificates').select('*').order('id', { ascending: false }),
            supabase.from('skills').select('*').order('id', { ascending: true }),
            supabase.from('partners').select('*').order('id', { ascending: true }),
            supabase.from('testimonials').select('*').order('id', { ascending: false }),
            supabase.from('chatbot_knowledge').select('*')
        ]);

        renderDesigns(designs);
        renderWebsites(websites);
        renderCertificates(certificates);
        renderSkills(skills);
        renderPartners(partners);
        renderTestimonials(testimonials);
        initChatbot(knowledge);

    } catch (error) {
        console.error('Error loading data from Supabase:', error);
    }
}

// --- 3. Rendering Logic ---

function renderDesigns(designs) {
    const container = document.getElementById('design-slider-content');
    const section = document.getElementById('design-section');
    if (!designs || designs.length === 0) return;

    section.classList.remove('hidden');
    const itemsHtml = designs.map(d => `
        <div class="design-slider-item">
            <img src="${d.image_path}" alt="${d.title}" loading="lazy">
        </div>
    `).join('');
    
    container.innerHTML = itemsHtml + itemsHtml; 
}

function renderWebsites(websites) {
    const container = document.getElementById('website-slider-content');
    const section = document.getElementById('website-section');
    if (!websites || websites.length === 0) return;

    section.classList.remove('hidden');
    const itemsHtml = websites.map(w => `
        <div class="design-slider-item">
            <img src="${w.image_path}" alt="${w.title}" loading="lazy">
        </div>
    `).join('');
    
    container.innerHTML = itemsHtml + itemsHtml;
}

function renderCertificates(certs) {
    const container = document.getElementById('certificate-slider-content');
    const section = document.getElementById('certificate-section');
    const statsGrid = document.getElementById('cert-stats-grid');
    if (!certs || certs.length === 0) return;

    section.classList.remove('hidden');

    const platforms = [
        { name: 'Camy.id', count: 12, url: 'https://camy.id/', logo: 'Camy.id' },
        { name: 'Dicoding', count: 90, url: 'https://www.dicoding.com/', logo: 'Dicoding' },
        { name: 'Red Hat', count: 2, url: 'https://www.redhat.com/', logo: 'Red Hat' },
        { name: 'MBKM', count: 2, url: 'https://kampusmerdeka.kemdikbud.go.id/', logo: 'MBKM' },
        { name: 'IEEE', count: 1, url: 'https://www.ieee.org/', logo: 'IEEE' },
        { name: 'Corisindo', count: 2, url: 'https://coris.or.id/', logo: 'Corisindo' }
    ];

    statsGrid.innerHTML = platforms.map(p => `
        <div class="cert-stat-card">
            <div class="cert-stat-content">
                <div class="cert-stat-front">
                    <div class="cert-stat-back-content">
                        <div class="platform-logo"><h2>${p.logo}</h2></div>
                        <div class="cert-count-display">
                            <span class="count-num">${p.count}</span>
                            <span class="count-label">Certificates</span>
                        </div>
                        <a href="${p.url}" target="_blank" class="visit-link">Visit Website</a>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    const itemsHtml = certs.map(c => `
        <div class="design-slider-item cert-item" data-title="${c.title.toLowerCase()}">
            <div class="cert-card-inner">
                ${c.platform ? `<div class="cert-badge">${c.platform}</div>` : ''}
                <div class="cert-media-container">
                    <img src="${c.image_path}" alt="${c.title}" loading="lazy">
                    <a href="${c.image_path}" target="_blank" class="cert-view-btn">VIEW DOCUMENT</a>
                </div>
                <div class="cert-info-overlay">
                    <h4>${c.title}</h4>
                    <p>${c.description || ''}</p>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = itemsHtml + itemsHtml;
}

function renderSkills(skills) {
    const container = document.getElementById('skills-content');
    if (!skills) return;
    const itemsHtml = skills.map(s => `
        <div class="skill-item" title="${s.name}">
            <img src="${s.icon_path}" alt="${s.name}">
        </div>
    `).join('');
    container.innerHTML = itemsHtml + itemsHtml + itemsHtml;
}

function renderPartners(partners) { /* Logic... */ }

function renderTestimonials(testimonials) {
    const container = document.getElementById('testimonials-content');
    if (!testimonials || testimonials.length === 0) return;

    const list = testimonials.length > 3 ? testimonials : [
        { name: "Briana Patton", role: "Manager", text: "Exceptional quality!", image_path: "https://randomuser.me/api/portraits/women/1.jpg" },
        { name: "Bilal Ahmed", role: "CEO", text: "Fast and reliable service.", image_path: "https://randomuser.me/api/portraits/men/2.jpg" }
    ];

    const perCol = Math.ceil(list.length / 3);
    const cols = [];
    for (let i = 0; i < 3; i++) cols.push(list.slice(i * perCol, (i + 1) * perCol));

    const durations = [15, 19, 17];
    container.innerHTML = cols.map((col, idx) => `
        <div class="testimonial-column" style="animation-duration: ${durations[idx]}s;">
            ${[...col, ...col].map(t => `
                <div class="testimonial-card">
                    <p>${t.text}</p>
                    <div class="testimonial-user">
                        <img src="${t.image_path}" alt="${t.name}">
                        <div class="testimonial-user-info">
                            <h4>${t.name}</h4>
                            <span>${t.role}</span>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `).join('');
}

// --- 4. Chatbot Logic ---
function initChatbot(knowledge) {
    const chatBubble = document.getElementById('chatBubble');
    const chatWindow = document.getElementById('chatWindow');
    const closeChat = document.getElementById('closeChat');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.getElementById('chatMessages');

    const botKnowledge = {};
    if (knowledge) knowledge.forEach(k => botKnowledge[k.keyword.toLowerCase()] = k.response);

    if (chatBubble) {
        chatBubble.onclick = () => { chatWindow.style.display = 'flex'; chatBubble.style.display = 'none'; };
        closeChat.onclick = () => { chatWindow.style.display = 'none'; chatBubble.style.display = 'flex'; };

        chatForm.onsubmit = async (e) => {
            e.preventDefault();
            const text = chatInput.value.trim();
            if (!text) return;
            addMessage(text, 'user');
            chatInput.value = '';
            const lowerText = text.toLowerCase();
            let response = "Maaf, saya tidak mengerti. Bisa coba kata kunci lain?";
            for (let key in botKnowledge) {
                if (lowerText.includes(key)) { response = botKnowledge[key]; break; }
            }
            addMessage(response, 'bot');
        };

        function addMessage(text, type) {
            const msg = document.createElement('div');
            msg.className = `message ${type}`;
            msg.textContent = text;
            chatMessages.appendChild(msg);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }
}

// --- 5. Core UI Initialization ---
document.addEventListener('DOMContentLoaded', () => {
    initUI();
    initSupabase(); // Start trying to initialize Supabase
});

function initUI() {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.remove(), 1000);
            }, 500);
        });
    }

    const nav = document.querySelector('.glass-nav');
    window.addEventListener('scroll', () => {
        if(nav) nav.classList.toggle('scrolled', window.scrollY > 50);
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add('active');
        });
    }, { threshold: 0.15 });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    initScramble();
}

function initScramble() {
    const CHARS = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*";
    document.querySelectorAll('.text-scramble-wrapper').forEach(wrapper => {
        const textEl = wrapper.querySelector('.scramble-text');
        if (!textEl) return;
        const originalText = textEl.innerText;
        wrapper.onmouseenter = () => {
            let frame = 0;
            const duration = originalText.length * 3;
            const interval = setInterval(() => {
                frame++;
                const progress = frame / duration;
                const revealedLength = Math.floor(progress * originalText.length);
                textEl.innerText = originalText.split('').map((char, i) => {
                    if (char === ' ') return ' ';
                    return i < revealedLength ? originalText[i] : CHARS[Math.floor(Math.random() * CHARS.length)];
                }).join('');
                if (frame >= duration) clearInterval(interval);
            }, 30);
        };
    });
}
