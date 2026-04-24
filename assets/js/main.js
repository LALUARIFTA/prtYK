// --- 1. Supabase Initialization ---
const SUPABASE_URL = 'https://abrxshzkgshklgmaztlp.supabase.co';
const SUPABASE_ANON_KEY = 'sb_publishable_EGPAkhdoARUwWv7Q2K2gVw_3PnwahcQ'; 

let supabase = null;

function initSupabase() {
    if (window.supabase) {
        supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
        console.log('Supabase initialized successfully.');
        fetchData();
    } else {
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
            { data: testimonials },
            { data: knowledge }
        ] = await Promise.all([
            supabase.from('designs').select('*').order('id', { ascending: false }),
            supabase.from('websites').select('*').order('id', { ascending: false }),
            supabase.from('certificates').select('*').order('id', { ascending: false }),
            supabase.from('skills').select('*').order('id', { ascending: true }),
            supabase.from('testimonials').select('*').order('id', { ascending: false }),
            supabase.from('chatbot_knowledge').select('*')
        ]);

        renderDesigns(designs);
        renderWebsites(websites);
        renderCertificates(certificates);
        renderSkills(skills);
        renderTestimonials(testimonials);
        initChatbot(knowledge);
        initFilters();
        initCertSearch();

    } catch (error) {
        console.error('Error loading data:', error);
    }
}

// --- 3. Rendering Logic ---

function renderDesigns(designs) {
    const container = document.getElementById('design-slider-content');
    const section = document.getElementById('design-section');
    if (!designs || designs.length === 0) return;
    section.classList.remove('hidden');
    const itemsHtml = designs.map(d => `<div class="design-slider-item"><img src="${d.image_path}" alt="${d.title}" loading="lazy"></div>`).join('');
    container.innerHTML = itemsHtml + itemsHtml; 
}

function renderWebsites(websites) {
    const container = document.getElementById('website-slider-content');
    const section = document.getElementById('website-section');
    if (!websites || websites.length === 0) return;
    section.classList.remove('hidden');
    const itemsHtml = websites.map(w => `<div class="design-slider-item"><img src="${w.image_path}" alt="${w.title}" loading="lazy"></div>`).join('');
    container.innerHTML = itemsHtml + itemsHtml;
}

function renderCertificates(certs) {
    const container = document.getElementById('certificate-slider-content');
    const section = document.getElementById('certificate-section');
    const statsGrid = document.getElementById('cert-stats-grid');
    if (!certs || certs.length === 0) return;
    section.classList.remove('hidden');

    // Calculate Dynamic Stats
    const platformCounts = {};
    certs.forEach(c => {
        if (c.platform) platformCounts[c.platform] = (platformCounts[c.platform] || 0) + 1;
    });

    const platforms = [
        { name: 'Camy.id', url: 'https://camy.id/' },
        { name: 'Dicoding', url: 'https://www.dicoding.com/' },
        { name: 'Red Hat', url: 'https://www.redhat.com/' },
        { name: 'MBKM', url: 'https://kampusmerdeka.kemdikbud.go.id/' },
        { name: 'IEEE', url: 'https://www.ieee.org/' },
        { name: 'Corisindo', url: 'https://coris.or.id/' }
    ];

    statsGrid.innerHTML = platforms.map(p => `
        <div class="cert-stat-card">
            <div class="cert-stat-content">
                <div class="cert-stat-front">
                    <div class="cert-stat-back-content">
                        <div class="platform-logo"><h2>${p.name}</h2></div>
                        <div class="cert-count-display"><span class="count-num">${platformCounts[p.name] || 0}</span><span class="count-label">Certificates</span></div>
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
                <div class="cert-media-container"><img src="${c.image_path}" alt="${c.title}" loading="lazy"><a href="${c.image_path}" target="_blank" class="cert-view-btn">VIEW DOCUMENT</a></div>
                <div class="cert-info-overlay"><h4>${c.title}</h4><p>${c.description || ''}</p></div>
            </div>
        </div>
    `).join('');
    container.innerHTML = itemsHtml + itemsHtml;
}

function renderSkills(skills) {
    const container = document.getElementById('skills-content');
    if (!skills) return;
    const itemsHtml = skills.map(s => `<div class="skill-item" title="${s.name}"><img src="${s.icon_path}" alt="${s.name}"></div>`).join('');
    container.innerHTML = itemsHtml + itemsHtml + itemsHtml;
}

function renderTestimonials(testimonials) {
    const container = document.getElementById('testimonials-content');
    if (!testimonials || testimonials.length === 0) return;
    const perCol = Math.ceil(testimonials.length / 3);
    const cols = [];
    for (let i = 0; i < 3; i++) cols.push(testimonials.slice(i * perCol, (i + 1) * perCol));
    const durations = [15, 19, 17];
    container.innerHTML = cols.map((col, idx) => `
        <div class="testimonial-column" style="animation-duration: ${durations[idx]}s;">
            ${[...col, ...col].map(t => `
                <div class="testimonial-card">
                    <p>${t.text}</p>
                    <div class="testimonial-user">
                        <img src="${t.image_path}" alt="${t.name}">
                        <div class="testimonial-user-info"><h4>${t.name}</h4><span>${t.role}</span></div>
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

    if (!chatBubble) return;

    chatBubble.onclick = () => { chatWindow.style.display = 'flex'; chatBubble.style.display = 'none'; };
    closeChat.onclick = () => { chatWindow.style.display = 'none'; chatBubble.style.display = 'flex'; };

    chatForm.onsubmit = (e) => {
        e.preventDefault();
        const text = chatInput.value.trim();
        if (!text) return;
        
        addMessage(text, 'user');
        chatInput.value = '';

        // Bot Response Logic
        setTimeout(() => {
            let response = "Maaf, saya tidak mengerti. Bisa coba kata kunci lain seperti 'project' atau 'kontak'?";
            const lowerText = text.toLowerCase();
            
            if (knowledge) {
                const found = knowledge.find(k => lowerText.includes(k.keyword.toLowerCase()));
                if (found) response = found.response;
            }
            
            addMessage(response, 'bot');
        }, 600);
    };

    function addMessage(text, type) {
        const msg = document.createElement('div');
        msg.className = `message ${type}`;
        msg.textContent = text;
        chatMessages.appendChild(msg);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// --- 5. Core UI & Event Listeners ---
document.addEventListener('DOMContentLoaded', () => {
    initUI();
    initSupabase();
});

function initUI() {
    // 1. Preloader
    const preloader = document.getElementById('preloader');
    if (preloader) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('fade-out');
                setTimeout(() => preloader.remove(), 1000);
            }, 1000);
        });
    }

    // 2. Custom Cursor
    const dot = document.querySelector('.cursor-dot');
    const outline = document.querySelector('.cursor-outline');
    if (dot && outline) {
        window.addEventListener('mousemove', (e) => {
            dot.style.left = `${e.clientX}px`;
            dot.style.top = `${e.clientY}px`;
            outline.animate({ left: `${e.clientX}px`, top: `${e.clientY}px` }, { duration: 500, fill: "forwards" });
        });
    }

    // 3. Scroll Progress
    const scrollBar = document.querySelector('.scroll-progress-bar');
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        if(scrollBar) scrollBar.style.width = scrolled + "%";
    });

    // 4. Reveal Animation
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('active'); });
    }, { threshold: 0.15 });
    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    // 5. Scramble Text
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
            const interval = setInterval(() => {
                frame++;
                textEl.innerText = originalText.split('').map((char, i) => {
                    if (char === ' ') return ' ';
                    return frame > (i * 2) ? originalText[i] : CHARS[Math.floor(Math.random() * CHARS.length)];
                }).join('');
                if (frame > originalText.length * 2) clearInterval(interval);
            }, 40);
        };
    });
}

function initFilters() {
    const buttons = document.querySelectorAll('.filter-btn');
    const sections = document.querySelectorAll('.design-slider-section');
    buttons.forEach(btn => {
        btn.onclick = () => {
            const filter = btn.dataset.filter;
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            sections.forEach(sec => {
                if (filter === 'all' || sec.dataset.category === filter) {
                    sec.style.display = 'block';
                    setTimeout(() => sec.style.opacity = '1', 10);
                } else {
                    sec.style.opacity = '0';
                    setTimeout(() => sec.style.display = 'none', 300);
                }
            });
        };
    });
}

function initCertSearch() {
    const searchInput = document.getElementById('certSearch');
    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.cert-item').forEach(item => {
            const title = item.dataset.title;
            item.style.display = title.includes(query) ? 'block' : 'none';
        });
    });
}
