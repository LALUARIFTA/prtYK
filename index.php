<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch public data
$banners = $pdo->query("SELECT * FROM banners ORDER BY id DESC LIMIT 5")->fetchAll();
$designs = $pdo->query("SELECT * FROM designs ORDER BY id DESC")->fetchAll();
$websites = $pdo->query("SELECT * FROM websites ORDER BY id DESC")->fetchAll();
$certificates = $pdo->query("SELECT * FROM certificates ORDER BY id DESC")->fetchAll();
$skills = $pdo->query("SELECT * FROM skills ORDER BY id ASC")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners ORDER BY id ASC")->fetchAll();
$testimonials_db = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll();
$knowledge_raw = $pdo->query("SELECT keyword, response FROM chatbot_knowledge")->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<script>
    const BOT_KNOWLEDGE = <?php echo json_encode($knowledge_raw); ?>;
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stormbreaker Portfolio | Modern Showcase</title>
    <meta name="description" content="A premium showcase of designs and website projects by Stormbreaker.">

    <!-- Open Graph / Social Media -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://localhost:8000">
    <meta property="og:title" content="Stormbreaker Portfolio | Modern Showcase">
    <meta property="og:description" content="A premium showcase of designs and website projects by Stormbreaker.">
    <meta property="og:image"
        content="https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&q=80&w=1200">

    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false,
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@300;400;700&display=swap"
        rel="stylesheet">
</head>

<body class="dark">
    <!-- UI/UX Enhancement Layers -->
    <div class="scroll-progress-container">
        <div class="scroll-progress-bar"></div>
    </div>
    <div class="tech-snippet-layer"></div>
    <div class="noise-overlay"></div>

    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <!-- Technical Preloader -->
    <div id="preloader">
        <div class="preloader-terminal">
            <div class="terminal-line">> Initializing Stormbreaker System...</div>
            <div class="terminal-line">> Loading Design Assets... [OK]</div>
            <div class="terminal-line">> Establishing Neural Link... [OK]</div>
            <div class="terminal-line">> Booting Whobee 3D Engine... [OK]</div>
            <div class="terminal-line">> Welcome, Admin.</div>
            <div class="terminal-cursor">_</div>
        </div>
    </div>

    <!-- Navigation -->
    <header class="glass-nav">
        <div class="container nav-container">
            <a href="index.php" class="logo text-scramble-wrapper">
                <span class="scramble-text" data-scramble>STORM BREAKER</span>
                <div class="scramble-underline">
                    <div class="scramble-underline-fill"></div>
                </div>
            </a>
            <div class="nav-links">
                <a href="#projects">Works</a>
                <a href="#about">About</a>
                <a href="admin/login.php" class="admin-link">Login Admin</a>
            </div>
            <div class="menu-toggle" id="mobile-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>

    <!-- Hero section removed as per user request -->


    <main class="container">
        <!-- Filter Section -->
        <section id="projects" class="section" style="padding-bottom: 0; margin-top: 8rem;">
            <div class="outline-title">
                WORKS
                <svg class="dot-pattern">
                    <defs>
                        <pattern id="dots-works" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots-works)" />
                </svg>
            </div>
            <div class="filter-container reveal">
                <button class="filter-btn active" data-filter="all">All Projects</button>
                <button class="filter-btn" data-filter="design">Designs</button>
                <button class="filter-btn" data-filter="website">Websites</button>
                <button class="filter-btn" data-filter="certificate">Certificates</button>
            </div>
        </section>

        <!-- Design Preview Slider -->
        <?php if (!empty($designs)): ?>
            <section class="design-slider-section reveal" data-category="design">
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem 1rem;">
                    <span
                        style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--primary); letter-spacing: 3px; text-transform: uppercase;">//
                        Design Projects</span>
                </div>
                <div class="design-slider-container">
                    <div class="design-infinite-scroll">
                        <?php
                        for ($i = 0; $i < 2; $i++):
                            foreach ($designs as $d):
                                ?>
                                <div class="design-slider-item">
                                    <img src="<?php echo h($d['image_path']); ?>" alt="<?php echo h($d['title']); ?>"
                                        loading="lazy">
                                </div>
                                <?php
                            endforeach;
                        endfor;
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Website Preview Slider -->
        <?php if (!empty($websites)): ?>
            <section class="design-slider-section reveal" data-category="website"
                style="border-top: none; padding-top: 0; padding-bottom: 5rem;">
                <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem 1rem;">
                    <span
                        style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--primary); letter-spacing: 3px; text-transform: uppercase;">//
                        Website Projects</span>
                </div>
                <div class="design-slider-container">
                    <div class="design-infinite-scroll">
                        <?php
                        for ($i = 0; $i < 2; $i++):
                            foreach ($websites as $w):
                                ?>
                                <div class="design-slider-item">
                                    <img src="<?php echo h($w['image_path']); ?>" alt="<?php echo h($w['title']); ?>"
                                        loading="lazy">
                                </div>
                                <?php
                            endforeach;
                        endfor;
                        ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Certificates Preview Slider -->
        <?php if (!empty($certificates)): ?>
            <section class="design-slider-section reveal" data-category="certificate"
                style="border-top: none; padding-top: 0; padding-bottom: 5rem;">
                <div
                    style="max-width: 1200px; margin: 0 auto; padding: 0 2rem 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <span
                        style="font-family: var(--font-mono); font-size: 0.7rem; color: var(--primary); letter-spacing: 3px; text-transform: uppercase;">//
                        Certificates</span>
                    <input type="text" id="certSearch" placeholder="Search certificates..."
                        style="background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; outline: none; font-family: var(--font-mono); font-size: 0.8rem; width: 100%; max-width: 300px;">
                </div>

                <?php
                // Calculate certificate counts for specified platforms
                $camy_count = 10;
                $dicoding_count = 90;
                $redhat_count = 0;
                $mbkm_count = 2;
                $ieee_count = 0;
                $corisindo_count = 0;

                foreach ($certificates as $c) {
                    $p = trim(strtolower($c['platform']));
                    if (strpos($p, 'camy') !== false)
                        $camy_count++;
                    if (strpos($p, 'dicoding') !== false)
                        $dicoding_count++;
                    if (strpos($p, 'redhat') !== false || strpos($p, 'red hat') !== false)
                        $redhat_count++;
                    if (strpos($p, 'mbkm') !== false || strpos($p, 'kampus merdeka') !== false)
                        $mbkm_count++;
                    if (strpos($p, 'ieee') !== false)
                        $ieee_count++;
                    if (strpos($p, 'corisindo') !== false)
                        $corisindo_count++;
                }

                $stat_platforms = [
                    [
                        'name' => 'Camy.id',
                        'count' => $camy_count,
                        'url' => 'https://camy.id/',
                        'logo' => '<h2 style="margin:0;font-size:2rem;color:#fff;">Camy.id</h2>'
                    ],
                    [
                        'name' => 'Dicoding',
                        'count' => $dicoding_count,
                        'url' => 'https://www.dicoding.com/',
                        'logo' => '<h2 style="margin:0;font-size:2rem;color:#fff;">Dicoding</h2>'
                    ],
                    [
                        'name' => 'Red Hat',
                        'count' => $redhat_count,
                        'url' => 'https://www.redhat.com/',
                        'logo' => '<h2 style="margin:0;font-size:2rem;color:#fff;">Red Hat</h2>'
                    ],
                    [
                        'name' => 'MBKM',
                        'count' => $mbkm_count,
                        'url' => 'https://kampusmerdeka.kemdikbud.go.id/',
                        'logo' => '<h2 style="margin:0;font-size:2rem;color:#fff;">MBKM</h2>'
                    ],
                    [
                        'name' => 'IEEE',
                        'count' => $ieee_count,
                        'url' => 'https://www.ieee.org/',
                        'logo' => '<h2 style="margin:0;font-size:2rem;color:#fff;">IEEE</h2>'
                    ],
                    [
                        'name' => 'Corisindo',
                        'count' => $corisindo_count,
                        'url' => 'https://coris.or.id/',
                        'logo' => '<h2 style="margin:0;font-size:1.5rem;color:#fff;">Corisindo</h2>'
                    ]
                ];
                ?>

                <div
                    style="display: flex; gap: 2rem; justify-content: center; margin-bottom: 4rem; flex-wrap: wrap; padding: 0 2rem;">
                    <?php foreach ($stat_platforms as $stat): ?>
                        <div class="cert-stat-card">
                            <div class="cert-stat-content">
                                <!-- BACK (Initially visible) -->
                                <div class="cert-stat-back">
                                    <div class="cert-stat-back-content">
                                        <?php echo $stat['logo']; ?>
                                        <a href="<?php echo $stat['url']; ?>" target="_blank"
                                            style="color:var(--primary);font-size:0.8rem;text-decoration:none;z-index:10;font-weight:bold;">Visit
                                            Website</a>
                                    </div>
                                </div>

                                <!-- FRONT (Visible on hover) -->
                                <div class="cert-stat-front">
                                    <div class="img">
                                        <div class="circle"></div>
                                        <div class="circle" id="right"></div>
                                        <div class="circle" id="bottom"></div>
                                    </div>
                                    <div class="cert-stat-front-content">
                                        <small class="badge">Sertifikat</small>
                                        <div class="description">
                                            <div class="title">
                                                <p class="title">
                                                    <strong><?php echo $stat['count']; ?></strong>
                                                </p>
                                            </div>
                                            <p class="card-footer">Total</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="design-slider-container">
                    <div class="design-infinite-scroll" id="certScroll">
                        <?php
                        for ($i = 0; $i < 2; $i++):
                            foreach ($certificates as $c):
                                ?>
                                <div class="design-slider-item cert-item" data-title="<?php echo strtolower(h($c['title'])); ?>"
                                    style="position: relative;">
                                    <?php if (!empty($c['platform'])): ?>
                                        <div
                                            style="position: absolute; top: 10px; left: 10px; background: var(--primary); color: #000; padding: 2px 8px; font-size: 0.65rem; font-weight: bold; border-radius: 4px; z-index: 10; text-transform: uppercase; letter-spacing: 1px;">
                                            <?php echo h($c['platform']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (pathinfo($c['image_path'], PATHINFO_EXTENSION) === 'pdf'): ?>
                                        <div
                                            style="width: 100%; height: 100%; background: #1a1a1a; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 1rem;">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#f43f5e"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            <a href="<?php echo h($c['image_path']); ?>" target="_blank"
                                                style="color: #f43f5e; font-size: 0.8rem; text-decoration: underline;">View PDF</a>
                                        </div>
                                    <?php else: ?>
                                        <img src="<?php echo h($c['image_path']); ?>" alt="<?php echo h($c['title']); ?>"
                                            loading="lazy">
                                    <?php endif; ?>
                                    <div
                                        style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 1rem; background: linear-gradient(transparent, rgba(0,0,0,0.8)); text-align: center; font-size: 0.85rem; font-weight: bold;">
                                        <?php echo h($c['title']); ?>
                                    </div>
                                </div>
                                <?php
                            endforeach;
                        endfor;
                        ?>
                    </div>
                </div>
            </section>

            <script>
                document.getElementById('certSearch').addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase();
                    const items = document.querySelectorAll('.cert-item');
                    items.forEach(item => {
                        const title = item.getAttribute('data-title');
                        if (title.includes(term)) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            </script>
        <?php endif; ?>


        <!-- About & Skills Section -->
        <section id="about" class="section">
            <div class="outline-title">
                ABOUT
                <svg class="dot-pattern">
                    <defs>
                        <pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots)" />
                </svg>
            </div>

            <div class="about-grid">
                <div class="about-img reveal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 614 390" height="390" width="614">
                        <g id="Frame">
                            <g id="box-figma">
                                <path fill="#F9F9F9"
                                    d="M76.2 106.08C72.792 106.08 69.864 105.6 67.416 104.64C64.968 103.632 62.928 102.24 61.296 100.464C59.712 98.688 58.536 96.6 57.768 94.2C57 91.8 56.616 89.16 56.616 86.28V55.104H65.76V85.416C65.76 87.672 66 89.616 66.48 91.248C67.008 92.832 67.728 94.128 68.64 95.136C69.6 96.144 70.704 96.888 71.952 97.368C73.248 97.848 74.688 98.088 76.272 98.088C77.856 98.088 79.296 97.848 80.592 97.368C81.888 96.888 82.992 96.144 83.904 95.136C84.864 94.128 85.584 92.832 86.064 91.248C86.592 89.616 86.856 87.672 86.856 85.416V55.104H96V86.28C96 89.16 95.592 91.8 94.776 94.2C94.008 96.6 92.808 98.688 91.176 100.464C89.592 102.24 87.552 103.632 85.056 104.64C82.56 105.6 79.608 106.08 76.2 106.08ZM137.193 105C135.801 102.168 134.049 99.048 131.937 95.64C129.825 92.232 127.617 88.944 125.313 85.776C124.353 87.024 123.297 88.512 122.145 90.24C121.041 91.968 119.913 93.744 118.761 95.568C117.657 97.344 116.625 99.072 115.665 100.752C114.705 102.432 113.937 103.848 113.361 105H103.065C105.225 100.92 107.721 96.744 110.553 92.472C113.385 88.152 116.457 83.64 119.769 78.936L103.785 55.104H114.585L125.601 72.168L136.473 55.104H146.841L131.073 78.864C134.721 83.808 137.937 88.488 140.721 92.904C143.553 97.32 145.929 101.352 147.849 105H137.193ZM156.562 118.32H147.634L171.97 48.624H180.754L156.562 118.32ZM204.731 106.08C201.323 106.08 198.395 105.6 195.947 104.64C193.499 103.632 191.459 102.24 189.827 100.464C188.243 98.688 187.067 96.6 186.299 94.2C185.531 91.8 185.147 89.16 185.147 86.28V55.104H194.291V85.416C194.291 87.672 194.531 89.616 195.011 91.248C195.539 92.832 196.259 94.128 197.171 95.136C198.131 96.144 199.235 96.888 200.483 97.368C201.779 97.848 203.219 98.088 204.803 98.088C206.387 98.088 207.827 97.848 209.123 97.368C210.419 96.888 211.523 96.144 212.435 95.136C213.395 94.128 214.115 92.832 214.595 91.248C215.123 89.616 215.387 87.672 215.387 85.416V55.104H224.531V86.28C224.531 89.16 224.123 91.8 223.307 94.2C222.539 96.6 221.339 98.688 219.707 100.464C218.123 102.24 216.083 103.632 213.587 104.64C211.091 105.6 208.139 106.08 204.731 106.08ZM236.132 55.104H245.204V105H236.132V55.104ZM283.032 97.512C283.56 97.56 284.256 97.608 285.12 97.656C285.984 97.656 287.16 97.656 288.648 97.656C294.744 97.656 299.28 96.12 302.256 93.048C305.28 89.928 306.792 85.584 306.792 80.016C306.792 74.352 305.328 70.008 302.4 66.984C299.472 63.96 294.936 62.448 288.792 62.448C286.104 62.448 284.184 62.52 283.032 62.664V97.512ZM316.296 80.016C316.296 84.336 315.624 88.104 314.28 91.32C312.936 94.488 311.016 97.152 308.52 99.312C306.072 101.424 303.096 103.008 299.592 104.064C296.136 105.072 292.296 105.576 288.072 105.576C286.056 105.576 283.776 105.48 281.232 105.288C278.688 105.144 276.264 104.808 273.96 104.28V55.824C276.264 55.296 278.712 54.96 281.304 54.816C283.896 54.672 286.2 54.6 288.216 54.6C292.392 54.6 296.208 55.104 299.664 56.112C303.12 57.072 306.072 58.608 308.52 60.72C311.016 62.784 312.936 65.424 314.28 68.64C315.624 71.808 316.296 75.6 316.296 80.016ZM323.683 86.352C323.683 83.04 324.163 80.136 325.123 77.64C326.131 75.144 327.451 73.08 329.083 71.448C330.715 69.768 332.587 68.52 334.699 67.704C336.811 66.84 338.971 66.408 341.179 66.408C346.363 66.408 350.395 68.016 353.275 71.232C356.203 74.448 357.667 79.248 357.667 85.632C357.667 86.112 357.643 86.664 357.595 87.288C357.595 87.864 357.571 88.392 357.523 88.872H332.683C332.923 91.896 333.979 94.248 335.851 95.928C337.771 97.56 340.531 98.376 344.131 98.376C346.243 98.376 348.163 98.184 349.891 97.8C351.667 97.416 353.059 97.008 354.067 96.576L355.219 103.704C354.739 103.944 354.067 104.208 353.203 104.496C352.387 104.736 351.427 104.952 350.323 105.144C349.267 105.384 348.115 105.576 346.867 105.72C345.619 105.864 344.347 105.936 343.051 105.936C339.739 105.936 336.859 105.456 334.411 104.496C331.963 103.488 329.947 102.12 328.363 100.392C326.779 98.616 325.603 96.552 324.835 94.2C324.067 91.8 323.683 89.184 323.683 86.352ZM348.955 82.464C348.955 81.264 348.787 80.136 348.451 79.08C348.115 77.976 347.611 77.04 346.939 76.272C346.315 75.456 345.523 74.832 344.563 74.4C343.651 73.92 342.547 73.68 341.251 73.68C339.907 73.68 338.731 73.944 337.723 74.472C336.715 74.952 335.851 75.6 335.131 76.416C334.459 77.232 333.931 78.168 333.547 79.224C333.163 80.28 332.899 81.36 332.755 82.464H348.955ZM376.335 98.736C378.639 98.736 380.319 98.472 381.375 97.944C382.431 97.368 382.959 96.408 382.959 95.064C382.959 93.816 382.383 92.784 381.231 91.968C380.127 91.152 378.279 90.264 375.687 89.304C374.103 88.728 372.639 88.128 371.295 87.504C369.999 86.832 368.871 86.064 367.911 85.2C366.951 84.336 366.183 83.304 365.607 82.104C365.079 80.856 364.815 79.344 364.815 77.568C364.815 74.112 366.087 71.4 368.631 69.432C371.175 67.416 374.631 66.408 378.999 66.408C381.207 66.408 383.319 66.624 385.335 67.056C387.351 67.44 388.863 67.824 389.871 68.208L388.287 75.264C387.327 74.832 386.103 74.448 384.615 74.112C383.127 73.728 381.399 73.536 379.431 73.536C377.655 73.536 376.215 73.848 375.111 74.472C374.007 75.048 373.455 75.96 373.455 77.208C373.455 77.832 373.551 78.384 373.743 78.864C373.983 79.344 374.367 79.8 374.895 80.232C375.423 80.616 376.119 81.024 376.983 81.456C377.847 81.84 378.903 82.248 380.151 82.68C382.215 83.448 383.967 84.216 385.407 84.984C386.847 85.704 388.023 86.544 388.935 87.504C389.895 88.416 390.591 89.472 391.023 90.672C391.455 91.872 391.671 93.312 391.671 94.992C391.671 98.592 390.327 101.328 387.639 103.2C384.999 105.024 381.207 105.936 376.263 105.936C372.951 105.936 370.287 105.648 368.271 105.072C366.255 104.544 364.839 104.112 364.023 103.776L365.535 96.504C366.831 97.032 368.367 97.536 370.143 98.016C371.967 98.496 374.031 98.736 376.335 98.736ZM408.594 105H399.882V67.344H408.594V105ZM409.53 56.328C409.53 57.96 409.002 59.256 407.946 60.216C406.89 61.176 405.642 61.656 404.202 61.656C402.714 61.656 401.442 61.176 400.386 60.216C399.33 59.256 398.802 57.96 398.802 56.328C398.802 54.648 399.33 53.328 400.386 52.368C401.442 51.408 402.714 50.928 404.202 50.928C405.642 50.928 406.89 51.408 407.946 52.368C409.002 53.328 409.53 54.648 409.53 56.328ZM426.548 85.2C426.548 88.896 427.34 91.608 428.924 93.336C430.556 95.016 432.644 95.856 435.188 95.856C436.58 95.856 437.876 95.664 439.076 95.28C440.324 94.896 441.332 94.44 442.1 93.912V74.4C441.476 74.256 440.708 74.136 439.796 74.04C438.884 73.896 437.732 73.824 436.34 73.824C433.172 73.824 430.748 74.88 429.068 76.992C427.388 79.056 426.548 81.792 426.548 85.2ZM450.812 101.184C450.812 107.184 449.276 111.576 446.204 114.36C443.18 117.144 438.524 118.536 432.236 118.536C429.932 118.536 427.676 118.344 425.468 117.96C423.308 117.576 421.34 117.072 419.564 116.448L421.148 109.032C422.636 109.656 424.316 110.16 426.188 110.544C428.108 110.928 430.172 111.12 432.38 111.12C435.884 111.12 438.38 110.4 439.868 108.96C441.356 107.52 442.1 105.384 442.1 102.552V101.112C441.236 101.544 440.084 101.976 438.644 102.408C437.252 102.84 435.644 103.056 433.82 103.056C431.42 103.056 429.212 102.672 427.196 101.904C425.228 101.136 423.524 100.008 422.084 98.52C420.692 97.032 419.588 95.184 418.772 92.976C418.004 90.72 417.62 88.128 417.62 85.2C417.62 82.464 418.028 79.944 418.844 77.64C419.708 75.336 420.932 73.368 422.516 71.736C424.148 70.104 426.116 68.832 428.42 67.92C430.724 67.008 433.34 66.552 436.268 66.552C439.1 66.552 441.788 66.768 444.332 67.2C446.876 67.632 449.036 68.088 450.812 68.568V101.184ZM461.896 68.568C463.576 68.088 465.76 67.632 468.448 67.2C471.136 66.768 474.112 66.552 477.376 66.552C480.448 66.552 483.016 66.984 485.08 67.848C487.144 68.664 488.776 69.84 489.976 71.376C491.224 72.864 492.088 74.688 492.568 76.848C493.096 78.96 493.36 81.288 493.36 83.832V105H484.648V85.2C484.648 83.184 484.504 81.48 484.216 80.088C483.976 78.648 483.544 77.496 482.92 76.632C482.344 75.72 481.528 75.072 480.472 74.688C479.464 74.256 478.216 74.04 476.728 74.04C475.624 74.04 474.472 74.112 473.272 74.256C472.072 74.4 471.184 74.52 470.608 74.616V105H461.896V68.568ZM513.013 95.424C513.109 96.24 513.157 96.84 513.157 97.224C513.157 97.608 513.157 97.992 513.157 98.376C513.157 101.352 512.677 104.424 511.717 107.592C510.805 110.76 509.509 113.76 507.829 116.592L500.989 114.72C502.141 111.936 502.909 109.2 503.293 106.512C503.725 103.872 503.941 101.544 503.941 99.528C503.941 98.952 503.917 98.232 503.869 97.368C503.869 96.504 503.845 95.856 503.797 95.424H513.013ZM56.976 191V141.104H88.512V148.808H66.048V161.552H85.992V169.256H66.048V191H56.976ZM115.828 161.192C115.108 160.952 114.1 160.712 112.804 160.472C111.556 160.184 110.092 160.04 108.412 160.04C107.452 160.04 106.42 160.136 105.316 160.328C104.26 160.52 103.516 160.688 103.084 160.832V191H94.3721V155.144C96.0521 154.52 98.1401 153.944 100.636 153.416C103.18 152.84 105.988 152.552 109.06 152.552C109.636 152.552 110.308 152.6 111.076 152.696C111.844 152.744 112.612 152.84 113.38 152.984C114.148 153.08 114.892 153.224 115.612 153.416C116.332 153.56 116.908 153.704 117.34 153.848L115.828 161.192ZM157.467 172.136C157.467 175.112 157.035 177.824 156.171 180.272C155.307 182.72 154.083 184.808 152.499 186.536C150.915 188.264 148.995 189.608 146.739 190.568C144.531 191.528 142.083 192.008 139.395 192.008C136.707 192.008 134.259 191.528 132.051 190.568C129.843 189.608 127.947 188.264 126.363 186.536C124.779 184.808 123.531 182.72 122.619 180.272C121.755 177.824 121.323 175.112 121.323 172.136C121.323 169.16 121.755 166.472 122.619 164.072C123.531 161.624 124.779 159.536 126.363 157.808C127.995 156.08 129.915 154.76 132.123 153.848C134.331 152.888 136.755 152.408 139.395 152.408C142.035 152.408 144.459 152.888 146.667 153.848C148.923 154.76 150.843 156.08 152.427 157.808C154.011 159.536 155.235 161.624 156.099 164.072C157.011 166.472 157.467 169.16 157.467 172.136ZM148.539 172.136C148.539 168.392 147.723 165.44 146.091 163.28C144.507 161.072 142.275 159.968 139.395 159.968C136.515 159.968 134.259 161.072 132.627 163.28C131.043 165.44 130.251 168.392 130.251 172.136C130.251 175.928 131.043 178.928 132.627 181.136C134.259 183.344 136.515 184.448 139.395 184.448C142.275 184.448 144.507 183.344 146.091 181.136C147.723 178.928 148.539 175.928 148.539 172.136ZM166.442 154.568C168.122 154.088 170.306 153.632 172.994 153.2C175.682 152.768 178.658 152.552 181.922 152.552C184.994 152.552 187.562 152.984 189.626 153.848C191.69 154.664 193.322 155.84 194.522 157.376C195.77 158.864 196.634 160.688 197.114 162.848C197.642 164.96 197.906 167.288 197.906 169.832V191H189.194V171.2C189.194 169.184 189.05 167.48 188.762 166.088C188.522 164.648 188.09 163.496 187.466 162.632C186.89 161.72 186.074 161.072 185.018 160.688C184.01 160.256 182.762 160.04 181.274 160.04C180.17 160.04 179.018 160.112 177.818 160.256C176.618 160.4 175.73 160.52 175.154 160.616V191H166.442V154.568ZM208.128 143.408L216.84 141.968V153.344H230.232V160.616H216.84V175.952C216.84 178.976 217.32 181.136 218.28 182.432C219.24 183.728 220.872 184.376 223.176 184.376C224.76 184.376 226.152 184.208 227.352 183.872C228.6 183.536 229.584 183.224 230.304 182.936L231.744 189.848C230.736 190.28 229.416 190.712 227.784 191.144C226.152 191.624 224.232 191.864 222.024 191.864C219.336 191.864 217.08 191.504 215.256 190.784C213.48 190.064 212.064 189.032 211.008 187.688C209.952 186.296 209.208 184.64 208.776 182.72C208.344 180.752 208.128 178.52 208.128 176.024V143.408ZM232.234 166.448H251.602V174.44H232.234V166.448ZM259.265 191V141.104H291.305V148.808H268.337V161.12H288.785V168.68H268.337V183.296H293.033V191H259.265ZM301.021 154.568C302.701 154.088 304.885 153.632 307.573 153.2C310.261 152.768 313.237 152.552 316.501 152.552C319.573 152.552 322.141 152.984 324.205 153.848C326.269 154.664 327.901 155.84 329.101 157.376C330.349 158.864 331.213 160.688 331.693 162.848C332.221 164.96 332.485 167.288 332.485 169.832V191H323.773V171.2C323.773 169.184 323.629 167.48 323.341 166.088C323.101 164.648 322.669 163.496 322.045 162.632C321.469 161.72 320.653 161.072 319.597 160.688C318.589 160.256 317.341 160.04 315.853 160.04C314.749 160.04 313.597 160.112 312.397 160.256C311.197 160.4 310.309 160.52 309.733 160.616V191H301.021V154.568ZM349.978 172.064C349.978 175.904 350.89 178.928 352.714 181.136C354.538 183.296 357.058 184.376 360.274 184.376C361.666 184.376 362.842 184.328 363.802 184.232C364.81 184.088 365.626 183.944 366.25 183.8V162.2C365.482 161.672 364.45 161.192 363.154 160.76C361.906 160.28 360.562 160.04 359.122 160.04C355.954 160.04 353.626 161.12 352.138 163.28C350.698 165.44 349.978 168.368 349.978 172.064ZM374.962 189.848C373.234 190.376 371.05 190.856 368.41 191.288C365.818 191.72 363.082 191.936 360.202 191.936C357.226 191.936 354.562 191.48 352.21 190.568C349.858 189.656 347.842 188.36 346.162 186.68C344.53 184.952 343.258 182.888 342.346 180.488C341.482 178.04 341.05 175.304 341.05 172.28C341.05 169.304 341.41 166.616 342.13 164.216C342.898 161.768 344.002 159.68 345.442 157.952C346.882 156.224 348.634 154.904 350.698 153.992C352.762 153.032 355.138 152.552 357.826 152.552C359.65 152.552 361.258 152.768 362.65 153.2C364.042 153.632 365.242 154.112 366.25 154.64V136.568L374.962 135.128V189.848ZM395.028 181.424C395.124 182.24 395.172 182.84 395.172 183.224C395.172 183.608 395.172 183.992 395.172 184.376C395.172 187.352 394.692 190.424 393.732 193.592C392.82 196.76 391.524 199.76 389.844 202.592L383.004 200.72C384.156 197.936 384.924 195.2 385.308 192.512C385.74 189.872 385.956 187.544 385.956 185.528C385.956 184.952 385.932 184.232 385.884 183.368C385.884 182.504 385.86 181.856 385.812 181.424H395.028Z"
                                    id="text"></path>
                                <g id="box">
                                    <path stroke-width="2" stroke="#2563EB" fill-opacity="0.05" fill="#2563EB"
                                        d="M587 20H28V306H587V20Z" id="figny9-box"></path>
                                    <path stroke-width="2" stroke="#2563EB" fill="white" d="M33 15H23V25H33V15Z"
                                        id="figny9-adjust-1"></path>
                                    <path stroke-width="2" stroke="#2563EB" fill="white" d="M33 301H23V311H33V301Z"
                                        id="figny9-adjust-3"></path>
                                    <path stroke-width="2" stroke="#2563EB" fill="white" d="M592 301H582V311H592V301Z"
                                        id="figny9-adjust-4"></path>
                                    <path stroke-width="2" stroke="#2563EB" fill="white" d="M592 15H582V25H592V15Z"
                                        id="figny9-adjust-2"></path>
                                </g>
                                <g id="cursor">
                                    <path stroke-width="2" stroke="white" fill="#2563EB"
                                        d="M453.383 343L448 317L471 331L459.745 333.5L453.383 343Z" id="Vector 273">
                                    </path>
                                    <path fill="#2563EB" d="M587 343H469.932V376H587V343Z" id="Rectangle 786"></path>
                                    <g id="Darlley Brito">
                                        <path fill="white"
                                            d="M479.592 364.208C479.197 364.208 479 364.011 479 363.616V354.128C479 353.733 479.197 353.536 479.592 353.536H483.448C484.819 353.536 485.824 353.859 486.464 354.504C487.104 355.144 487.424 356.149 487.424 357.52V360.224C487.424 361.595 487.104 362.603 486.464 363.248C485.829 363.888 484.824 364.208 483.448 364.208H479.592ZM480.176 363.032H483.448C484.141 363.032 484.693 362.944 485.104 362.768C485.515 362.592 485.808 362.299 485.984 361.888C486.16 361.477 486.248 360.923 486.248 360.224V357.52C486.248 356.827 486.16 356.275 485.984 355.864C485.808 355.453 485.515 355.16 485.104 354.984C484.693 354.803 484.141 354.712 483.448 354.712H480.176V363.032Z">
                                        </path>
                                        <path fill="white"
                                            d="M492.729 364.208C491.854 364.208 491.206 363.997 490.785 363.576C490.363 363.155 490.153 362.507 490.153 361.632C490.153 360.757 490.36 360.109 490.776 359.688C491.198 359.267 491.849 359.056 492.729 359.056H496.193C496.171 358.448 496.022 358.029 495.745 357.8C495.467 357.571 494.995 357.456 494.328 357.456H493.401C492.819 357.456 492.387 357.504 492.104 357.6C491.827 357.696 491.641 357.864 491.545 358.104C491.47 358.296 491.387 358.432 491.297 358.512C491.211 358.587 491.078 358.624 490.896 358.624C490.699 358.624 490.544 358.571 490.432 358.464C490.326 358.352 490.294 358.205 490.337 358.024C490.465 357.421 490.779 356.981 491.281 356.704C491.782 356.421 492.489 356.28 493.401 356.28H494.328C495.369 356.28 496.136 356.528 496.632 357.024C497.128 357.52 497.377 358.288 497.377 359.328V363.616C497.377 364.011 497.182 364.208 496.792 364.208C496.398 364.208 496.201 364.011 496.201 363.616V363.112C495.651 363.843 494.793 364.208 493.625 364.208H492.729ZM492.729 363.032H493.625C494.057 363.032 494.454 362.989 494.817 362.904C495.179 362.819 495.483 362.669 495.729 362.456C495.974 362.243 496.131 361.944 496.201 361.56V360.232H492.729C492.179 360.232 491.808 360.331 491.616 360.528C491.424 360.72 491.328 361.088 491.328 361.632C491.328 362.181 491.424 362.552 491.616 362.744C491.808 362.936 492.179 363.032 492.729 363.032Z">
                                        </path>
                                        <path fill="white"
                                            d="M501.029 364.208C500.635 364.208 500.438 364.011 500.438 363.616V356.864C500.438 356.475 500.635 356.28 501.029 356.28C501.419 356.28 501.614 356.475 501.614 356.864V357.696C501.918 357.232 502.317 356.88 502.813 356.64C503.315 356.4 503.896 356.28 504.558 356.28C504.952 356.28 505.149 356.475 505.149 356.864C505.149 357.259 504.952 357.456 504.558 357.456C503.624 357.456 502.909 357.643 502.413 358.016C501.917 358.384 501.651 358.888 501.614 359.528V363.616C501.614 364.011 501.419 364.208 501.029 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M509.344 364.208C508.549 364.208 507.96 364.016 507.576 363.632C507.192 363.243 507 362.651 507 361.856V353.584C507 353.195 507.197 353 507.592 353C507.981 353 508.176 353.195 508.176 353.584V361.856C508.176 362.32 508.253 362.632 508.408 362.792C508.568 362.952 508.88 363.032 509.344 363.032C509.744 363.032 509.944 363.227 509.944 363.616C509.955 364.011 509.755 364.208 509.344 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M514.563 364.208C513.768 364.208 513.179 364.016 512.795 363.632C512.411 363.243 512.219 362.651 512.219 361.856V353.584C512.219 353.195 512.416 353 512.811 353C513.2 353 513.395 353.195 513.395 353.584V361.856C513.395 362.32 513.472 362.632 513.627 362.792C513.787 362.952 514.099 363.032 514.563 363.032C514.963 363.032 515.163 363.227 515.163 363.616C515.173 364.011 514.973 364.208 514.563 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M517.973 360.72V361.168C517.973 361.877 518.106 362.365 518.373 362.632C518.64 362.899 519.133 363.032 519.853 363.032H521.165C521.752 363.032 522.181 362.971 522.453 362.848C522.73 362.72 522.909 362.499 522.989 362.184C523.032 362.008 523.098 361.872 523.189 361.776C523.285 361.68 523.426 361.632 523.613 361.632C523.81 361.632 523.96 361.685 524.061 361.792C524.162 361.893 524.197 362.043 524.165 362.24C524.064 362.907 523.762 363.403 523.261 363.728C522.765 364.048 522.066 364.208 521.165 364.208H519.853C518.813 364.208 518.042 363.96 517.541 363.464C517.045 362.968 516.797 362.203 516.797 361.168V359.328C516.797 358.272 517.045 357.499 517.541 357.008C518.042 356.512 518.813 356.269 519.853 356.28H521.165C522.205 356.28 522.973 356.528 523.469 357.024C523.965 357.515 524.213 358.283 524.213 359.328V360.128C524.213 360.523 524.018 360.72 523.629 360.72H517.973ZM519.853 357.456C519.133 357.445 518.64 357.573 518.373 357.84C518.106 358.107 517.973 358.603 517.973 359.328V359.544H523.037V359.328C523.037 358.608 522.904 358.117 522.637 357.856C522.376 357.589 521.885 357.456 521.165 357.456H519.853Z">
                                        </path>
                                        <path fill="white"
                                            d="M529.158 367.408C528.411 367.408 527.854 367.221 527.486 366.848C527.123 366.48 526.942 365.92 526.942 365.168C526.942 364.779 527.136 364.584 527.526 364.584C527.92 364.584 528.118 364.779 528.118 365.168C528.118 365.595 528.184 365.877 528.318 366.016C528.456 366.16 528.736 366.232 529.158 366.232H532.15C532.571 366.232 532.851 366.16 532.99 366.016C533.128 366.16 533.198 365.595 533.198 365.168V363.08C532.883 363.533 532.507 363.835 532.07 363.984C531.632 364.133 531.147 364.208 530.614 364.208H529.942C528.912 364.208 528.155 363.965 527.67 363.48C527.184 362.995 526.942 362.243 526.942 361.224V356.864C526.942 356.469 527.136 356.272 527.526 356.272C527.92 356.272 528.118 356.469 528.118 356.864V361.224C528.118 361.917 528.246 362.392 528.502 362.648C528.763 362.904 529.243 363.032 529.942 363.032H530.614C531.51 363.032 532.163 362.883 532.574 362.584C532.99 362.285 533.198 361.832 533.198 361.224V356.864C533.198 356.469 533.392 356.272 533.782 356.272C534.176 356.272 534.374 356.469 534.374 356.864V365.168C534.374 365.92 534.19 366.48 533.822 366.848C533.454 367.221 532.896 367.408 532.15 367.408H529.158Z">
                                        </path>
                                        <path fill="white"
                                            d="M542.873 364.208C542.479 364.208 542.281 364.011 542.281 363.616V354.128C542.281 353.733 542.479 353.536 542.873 353.536H547.049C547.876 353.536 548.508 353.752 548.945 354.184C549.383 354.616 549.601 355.237 549.601 356.048V356.48C549.601 357.237 549.361 357.805 548.881 358.184C549.756 358.632 550.193 359.488 550.193 360.752V361.28C550.193 362.24 549.943 362.968 549.441 363.464C548.94 363.96 548.212 364.208 547.257 364.208H542.873ZM543.457 363.032H547.257C547.881 363.032 548.329 362.896 548.601 362.624C548.879 362.347 549.017 361.899 549.017 361.28V360.752C549.017 360.133 548.884 359.691 548.617 359.424C548.351 359.152 547.897 359.016 547.257 359.016H543.457V363.032ZM543.457 357.84H547.281C547.703 357.84 547.999 357.72 548.169 357.48C548.34 357.235 548.425 356.901 548.425 356.48V356.048C548.425 355.563 548.321 355.219 548.113 355.016C547.905 354.813 547.551 354.712 547.049 354.712H543.457V357.84Z">
                                        </path>
                                        <path fill="white"
                                            d="M553.358 364.208C552.963 364.208 552.766 364.011 552.766 363.616V356.864C552.766 356.475 552.963 356.28 553.358 356.28C553.747 356.28 553.942 356.475 553.942 356.864V357.696C554.246 357.232 554.646 356.88 555.142 356.64C555.643 356.4 556.224 356.28 556.886 356.28C557.28 356.28 557.478 356.475 557.478 356.864C557.478 357.259 557.28 357.456 556.886 357.456C555.952 357.456 555.238 357.643 554.742 358.016C554.246 358.384 553.979 358.888 553.942 359.528V363.616C553.942 364.011 553.747 364.208 553.358 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M559.704 354.92C559.245 354.92 559.016 354.685 559.016 354.216V353.784C559.016 353.325 559.245 353.096 559.704 353.096H560.136C560.579 353.096 560.8 353.325 560.8 353.784V354.216C560.8 354.685 560.579 354.92 560.136 354.92H559.704ZM559.92 364.208C559.525 364.208 559.328 364.011 559.328 363.616V356.864C559.328 356.475 559.525 356.28 559.92 356.28C560.309 356.28 560.504 356.475 560.504 356.864V363.616C560.504 364.011 560.309 364.208 559.92 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M567.031 364.208C566.001 364.208 565.244 363.965 564.759 363.48C564.273 362.995 564.031 362.24 564.031 361.216V357.456H563.191C562.796 357.456 562.599 357.259 562.599 356.864C562.599 356.475 562.796 356.28 563.191 356.28H564.031V354.928C564.031 354.533 564.225 354.336 564.615 354.336C565.009 354.336 565.207 354.533 565.207 354.928V356.28H567.103C567.492 356.28 567.687 356.475 567.687 356.864C567.687 357.259 567.492 357.456 567.103 357.456H565.207V361.216C565.207 361.92 565.335 362.4 565.591 362.656C565.852 362.907 566.332 363.032 567.031 363.032C567.223 363.032 567.369 363.08 567.471 363.176C567.572 363.267 567.623 363.413 567.623 363.616C567.623 364.011 567.425 364.208 567.031 364.208Z">
                                        </path>
                                        <path fill="white"
                                            d="M572.197 364.208C571.157 364.208 570.386 363.96 569.885 363.464C569.389 362.968 569.141 362.203 569.141 361.168V359.328C569.141 358.277 569.389 357.507 569.885 357.016C570.386 356.52 571.157 356.275 572.197 356.28H573.509C574.549 356.28 575.317 356.528 575.813 357.024C576.309 357.52 576.557 358.288 576.557 359.328V361.152C576.557 362.192 576.309 362.963 575.813 363.464C575.317 363.96 574.549 364.208 573.509 364.208H572.197ZM570.317 361.168C570.317 361.877 570.45 362.365 570.717 362.632C570.983 362.899 571.477 363.032 572.197 363.032H573.509C574.229 363.032 574.719 362.899 574.981 362.632C575.247 362.365 575.381 361.872 575.381 361.152V359.328C575.381 358.608 575.247 358.117 574.981 357.856C574.719 357.589 574.229 357.456 573.509 357.456H572.197C571.717 357.456 571.341 357.512 571.069 357.624C570.797 357.736 570.602 357.928 570.485 358.2C570.373 358.472 570.317 358.848 570.317 359.328V361.168Z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div class="about-text">
                    <h3 class="reveal"><span class="glitch-text" data-text="Building">Building</span> Digital <span
                            class="amber-text">Masterpieces</span></h3>
                    <p class="reveal staggered-text">I am a passionate designer and developer focused on creating
                        <span>premium</span> digital experiences that blend <span>aesthetics</span> with
                        <span>functionality</span>.
                    </p>

                    <div class="reveal" style="margin-top: 2rem;">
                        <p
                            style="text-shadow: none; font-size: 0.6rem; letter-spacing: 0.4em; color: var(--primary); margin-bottom: 0.5rem; border: none; padding: 0;">
                            HOVER TO DECODE</p>
                        <div class="text-scramble-wrapper">
                            <span class="scramble-text" data-scramble>EXPLORE PROJECTS</span>
                            <div class="scramble-underline">
                                <div class="scramble-underline-fill"></div>
                            </div>
                            <div class="scramble-glow"></div>
                        </div>
                    </div>

                    <!-- Skills Marquee -->
                    <?php if (!empty($skills)): ?>
                        <div class="about-skills">
                            <div class="marquee">
                                <div class="marquee-content">
                                    <?php
                                    // Render skills multiple times for smooth loop in smaller container
                                    for ($i = 0; $i < 3; $i++):
                                        foreach ($skills as $s):
                                            ?>
                                            <div class="skill-item" title="<?php echo h($s['name']); ?>">
                                                <img src="<?php echo h($s['icon_path']); ?>" alt="<?php echo h($s['name']); ?>">
                                            </div>
                                            <?php
                                        endforeach;
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>


        <!-- Contact Section -->
        <!-- Contact Section -->
        <section id="contact" class="section reveal">
            <div class="outline-title">
                CONTACT
                <svg class="dot-pattern">
                    <defs>
                        <pattern id="dots-contact" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#dots-contact)" />
                </svg>
            </div>

            <div class="contact-flex-layout">
                <!-- Left: Vertical Cut Reveal Text -->
                <div class="contact-left-text">
                    <div class="reveal-container reveal" id="contactReveal">
                        <?php
                        if (!function_exists('renderReveal')) {
                            function renderReveal($text, $baseDelay = 0)
                            {
                                $chars = mb_str_split($text);
                                echo '<div class="reveal-line">';
                                foreach ($chars as $i => $char) {
                                    $delay = $baseDelay + ($i * 0.03);
                                    if ($char === " ") {
                                        echo '<span style="width: 1rem;"></span>';
                                    } else {
                                        echo '<span class="reveal-char" style="transition-delay: ' . $delay . 's;">' . h($char) . '</span>';
                                    }
                                }
                                echo '</div>';
                            }
                        }

                        renderReveal("STORM BREAKER", 0.1);
                        renderReveal("DIGITAL ARTISAN", 0.5);
                        renderReveal("READY TO SCALE", 0.9);
                        ?>
                        <p class="reveal-subtext">Let's build the future of your digital presence with cutting-edge
                            solutions and premium craftsmanship.</p>
                    </div>
                </div>

                <!-- Right: Social Media & CPU -->
                <div class="contact-right-socials"
                    style="position: relative; overflow: visible; display: flex; flex-direction: column; align-items: flex-end; gap: 2rem;">

                    <!-- CPU Architecture SVG Background -->
                    <div class="cpu-architecture-bg">
                        <svg width="100%" height="100%" viewBox="0 0 200 100" class="text-muted">
                            <defs>
                                <radialGradient id="cpu-blue-grad" fx="1">
                                    <stop offset="0%" stop-color="#00E8ED" />
                                    <stop offset="50%" stop-color="#08F" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-yellow-grad" fx="1">
                                    <stop offset="0%" stop-color="#FFD800" />
                                    <stop offset="50%" stop-color="#FFD800" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-pinkish-grad" fx="1">
                                    <stop offset="0%" stop-color="#830CD1" />
                                    <stop offset="50%" stop-color="#FF008B" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-white-grad" fx="1">
                                    <stop offset="0%" stop-color="white" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-green-grad" fx="1">
                                    <stop offset="0%" stop-color="#22c55e" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-orange-grad" fx="1">
                                    <stop offset="0%" stop-color="#f97316" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-cyan-grad" fx="1">
                                    <stop offset="0%" stop-color="#06b6d4" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>
                                <radialGradient id="cpu-rose-grad" fx="1">
                                    <stop offset="0%" stop-color="#f43f5e" />
                                    <stop offset="100%" stop-color="transparent" />
                                </radialGradient>

                                <mask id="cpu-mask-1">
                                    <path d="M 10 20 h 79.5 q 5 0 5 5 v 24" stroke-width="0.5" stroke="white"
                                        fill="none" />
                                </mask>
                                <mask id="cpu-mask-2">
                                    <path d="M 180 10 h -69.7 q -5 0 -5 5 v 24" stroke-width="0.5" stroke="white"
                                        fill="none" />
                                </mask>
                                <mask id="cpu-mask-3">
                                    <path d="M 130 20 v 21.8 q 0 5 -5 5 h -10" stroke-width="0.5" stroke="white"
                                        fill="none" />
                                </mask>
                                <mask id="cpu-mask-4">
                                    <path d="M 170 80 v -21.8 q 0 -5 -5 -5 h -50" stroke-width="0.5" stroke="white"
                                        fill="none" />
                                </mask>
                                <mask id="cpu-mask-5">
                                    <path d="M 135 65 h 15 q 5 0 5 5 v 10 q 0 5 -5 5 h -39.8 q -5 0 -5 -5 v -20"
                                        stroke-width="0.5" stroke="white" fill="none" />
                                </mask>
                                <mask id="cpu-mask-6">
                                    <path d="M 94.8 95 v -36" stroke-width="0.5" stroke="white" fill="none" />
                                </mask>
                                <mask id="cpu-mask-7">
                                    <path d="M 88 88 v -15 q 0 -5 -5 -5 h -10 q -5 0 -5 -5 v -5 q 0 -5 5 -5 h 14"
                                        stroke-width="0.5" stroke="white" fill="none" />
                                </mask>
                                <mask id="cpu-mask-8">
                                    <path d="M 30 30 h 25 q 5 0 5 5 v 6.5 q 0 5 5 5 h 20" stroke-width="0.5"
                                        stroke="white" fill="none" />
                                </mask>
                            </defs>

                            <!-- Data Paths -->
                            <g stroke="var(--border)" fill="none" stroke-width="0.1" opacity="0.5">
                                <path d="M 10 20 h 79.5 q 5 0 5 5 v 30" />
                                <path d="M 180 10 h -69.7 q -5 0 -5 5 v 40" />
                                <path d="M 130 20 v 21.8 q 0 5 -5 5 h -10" />
                                <path d="M 170 80 v -21.8 q 0 -5 -5 -5 h -65" />
                                <path d="M 135 65 h 15 q 5 0 5 5 v 10 q 0 5 -5 5 h -39.8 q -5 0 -5 -5 v -35" />
                                <path d="M 94.8 95 v -46" />
                                <path d="M 88 88 v -15 q 0 -5 -5 -5 h -10 q -5 0 -5 -5 v -5 q 0 -5 5 -5 h 28" />
                                <path d="M 30 30 h 25 q 5 0 5 5 v 6.5 q 0 5 5 5 h 35" />
                            </g>

                            <!-- Animated Lights -->
                            <g mask="url(#cpu-mask-1)">
                                <circle class="cpu-architecture cpu-line-1" r="5" fill="url(#cpu-blue-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-2)">
                                <circle class="cpu-architecture cpu-line-2" r="5" fill="url(#cpu-yellow-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-3)">
                                <circle class="cpu-architecture cpu-line-3" r="5" fill="url(#cpu-pinkish-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-4)">
                                <circle class="cpu-architecture cpu-line-4" r="5" fill="url(#cpu-white-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-5)">
                                <circle class="cpu-architecture cpu-line-5" r="5" fill="url(#cpu-green-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-6)">
                                <circle class="cpu-architecture cpu-line-6" r="5" fill="url(#cpu-orange-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-7)">
                                <circle class="cpu-architecture cpu-line-7" r="5" fill="url(#cpu-cyan-grad)" />
                            </g>
                            <g mask="url(#cpu-mask-8)">
                                <circle class="cpu-architecture cpu-line-8" r="5" fill="url(#cpu-rose-grad)" />
                            </g>

                            <!-- Core Unit -->
                            <rect x="85" y="40" width="30" height="20" rx="2" fill="var(--bg)" stroke="var(--primary)"
                                stroke-width="0.5" />
                            <text x="92" y="52.5" font-size="5" fill="var(--primary)" font-family="var(--font-mono)"
                                font-weight="bold">CORE</text>
                        </svg>
                    </div>

                    <!-- Social Media (Tailwind) -->
                    <div class="social-container">
                        <svg width="0" height="0" style="position: absolute;">
                            <defs>
                                <clipPath id="squircleClip" clipPathUnits="objectBoundingBox">
                                    <path d="M 0,0.5 C 0,0 0,0 0.5,0 S 1,0 1,0.5 1,1 0.5,1 0,1 0,0.5"></path>
                                </clipPath>
                            </defs>
                        </svg>

                        <div class="relative">
                            <div
                                class="absolute inset-0 bg-black/20 backdrop-blur-xl rounded-2xl border border-white/10 shadow-2xl">
                            </div>

                            <div class="relative flex items-end gap-x-2 p-2">
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/in/lalu-arif/" target="_blank" class="relative block">
                                    <div style="clip-path: url(#squircleClip)"
                                        class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center shadow-lg border border-blue-500/50 cursor-pointer transform transition-all duration-300 ease-out hover:scale-110 hover:-translate-y-2 hover:shadow-2xl">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    </div>
                                </a>

                                <!-- Instagram -->
                                <a href="https://www.instagram.com/ashari.ll/" target="_blank" class="relative block">
                                    <div style="clip-path: url(#squircleClip)"
                                        class="w-14 h-14 bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg border border-pink-400/50 cursor-pointer transform transition-all duration-300 ease-out hover:scale-110 hover:-translate-y-2 hover:shadow-2xl">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                                            <path
                                                d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.245 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.332 2.633-1.308 3.608-.975.975-2.242 1.245-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.332-3.608-1.308-.975-.975-1.245-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.245 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.358-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.28-.058-1.689-.072-4.948-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                        </svg>
                                    </div>
                                </a>

                                <!-- WhatsApp -->
                                <a href="https://wa.me/6288987004237" target="_blank" class="relative block">
                                    <div style="clip-path: url(#squircleClip)"
                                        class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-700 rounded-xl flex items-center justify-center shadow-lg border border-green-400/50 cursor-pointer transform transition-all duration-300 ease-out hover:scale-110 hover:-translate-y-2 hover:shadow-2xl">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72 1.954 3.792 2.98 5.715 2.983h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                        </svg>
                                    </div>
                                </a>

                                <!-- Discord -->
                                <a href="https://discord.com/users/702005211572600912" target="_blank"
                                    class="relative block">
                                    <div style="clip-path: url(#squircleClip)"
                                        class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-xl flex items-center justify-center shadow-lg border border-indigo-500/50 cursor-pointer transform transition-all duration-300 ease-out hover:scale-110 hover:-translate-y-2 hover:shadow-2xl">
                                        <svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8 text-white">
                                            <path
                                                d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419-.0189 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1568 2.4189Z" />
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>
        </section>

        <!-- Payment Methods UI -->
        <div class="payment-section reveal">
            <h3>Payment Gateway Integration</h3>

            <div class="payment-flex-container">
                <!-- Marketing Text -->
                <div class="payment-text-block">
                    <h2>Terintegrasi<span> Ekosistem</span></h2>
                    <p>Hubungkan website Anda dengan berbagai vendor dan payment gateway terpercaya di Indonesia.
                    </p>

                    <?php if (!empty($partners)): ?>
                        <div class="partners-container" style="margin-top: 2rem; width: 100%;">
                            <h4
                                style="color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 1rem;">
                                Our Trusted Partners</h4>

                            <div class="partners-marquee-container">
                                <div class="partners-marquee-inner">
                                    <?php
                                    // Repeat logos 3 times for seamless loop
                                    for ($i = 0; $i < 3; $i++):
                                        foreach ($partners as $p):
                                            ?>
                                            <div class="partner-item" title="<?php echo h($p['name']); ?>">
                                                <img src="<?php echo h($p['logo_path']); ?>" alt="<?php echo h($p['name']); ?>"
                                                    style="height: 25px; filter: grayscale(1) brightness(2); opacity: 0.5; transition: 0.3s;"
                                                    onmouseover="this.style.opacity='1'; this.style.filter='grayscale(0) brightness(1)';"
                                                    onmouseout="this.style.opacity='0.5'; this.style.filter='grayscale(1) brightness(2)';">
                                            </div>
                                            <?php
                                        endforeach;
                                    endfor;
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Wallet UI -->
                <div class="wallet">
                    <div class="wallet-back"></div>

                    <div class="card stripe">
                        <div class="card-inner">
                            <div class="card-top">
                                <span>Stripe</span>
                                <div class="chip"></div>
                            </div>
                            <div class="card-bottom">
                                <div class="card-info">
                                    <span class="label">Holder</span>
                                    <span class="value">STORM BREAKER</span>
                                </div>
                                <div class="card-number-wrapper">
                                    <span class="hidden-stars">**** 4242</span>
                                    <span class="card-number">5524 9910 4242</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card wise">
                        <div class="card-inner">
                            <div class="card-top">
                                <span>Wise</span>
                                <div class="chip"></div>
                            </div>
                            <div class="card-bottom">
                                <div class="card-info">
                                    <span class="label">Business</span>
                                    <span class="value">STORMBREAKER LLC</span>
                                </div>
                                <div class="card-number-wrapper">
                                    <span class="hidden-stars">**** 8810</span>
                                    <span class="card-number">9012 4432 8810</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card paypal">
                        <div class="card-inner">
                            <div class="card-top">
                                <span>Pay<b style="color:#0079C1">Pal</b></span>
                                <div class="chip"></div>
                            </div>
                            <div class="card-bottom">
                                <div class="card-info">
                                    <span class="label">Email</span>
                                    <span class="value">hello@stormbreaker.com</span>
                                </div>
                                <div class="card-number-wrapper">
                                    <span class="hidden-stars">**** 0094</span>
                                    <span class="card-number">3312 0045 0094</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pocket">
                        <svg class="pocket-svg" viewBox="0 0 280 160" fill="none">
                            <path
                                d="M 0 20 C 0 10, 5 10, 10 10 C 20 10, 25 25, 40 25 L 240 25 C 255 25, 260 10, 270 10 C 275 10, 280 10, 280 20 L 280 120 C 280 155, 260 160, 240 160 L 40 160 C 20 160, 0 155, 0 120 Z"
                                fill="#1e341e"></path>
                            <path
                                d="M 8 22 C 8 16, 12 16, 15 16 C 23 16, 27 29, 40 29 L 240 29 C 253 29, 257 16, 265 16 C 268 16, 272 16, 272 22 L 272 120 C 272 150, 255 152, 240 152 L 40 152 C 25 152, 8 152, 8 120 Z"
                                stroke="#3d5635" stroke-width="1.5" stroke-dasharray="6 4"></path>
                        </svg>
                        <div class="pocket-content">
                            <div style="position: relative; height: 24px; width: 100%;">
                                <div class="balance-stars">******</div>
                                <div class="balance-real">$99,999.00</div>
                            </div>
                            <div style="color: #698263; font-size: 12px; font-weight: 500;">
                                Total Balance
                            </div>
                            <div class="eye-icon-wrapper">
                                <svg class="eye-icon eye-slash" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <line x1="3" y1="3" x2="21" y2="21"></line>
                                </svg>
                                <svg class="eye-icon eye-open" style="opacity: 0;" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of payment-flex-container -->
            </div>
            </section>

            <!-- Testimonials Section -->
            <section id="feedback" class="testimonials-section section reveal">
                <div class="outline-title">
                    FEEDBACK
                    <svg class="dot-pattern">
                        <defs>
                            <pattern id="dots-feedback" width="20" height="20" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="1" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#dots-feedback)" />
                    </svg>
                </div>

                <div class="container">
                    <div class="section-header" style="text-align: center;">
                        <div
                            style="display: inline-block; border: 1px solid var(--border); padding: 0.25rem 1rem; margin-bottom: 1.5rem; font-size: 0.8rem;">
                            Testimonials</div>
                        <h2 style="font-size: 2.5rem; font-weight: 800;">What our users <span>say</span></h2>
                        <p style="margin-top: 1rem; opacity: 0.75;">See what our customers have to say about us.</p>
                    </div>

                    <?php
                    // Use database testimonials, fallback to defaults if empty
                    if (!empty($testimonials_db)) {
                        $testimonials_list = [];
                        foreach ($testimonials_db as $tdb) {
                            $testimonials_list[] = [
                                "text" => $tdb['text'],
                                "image" => $tdb['image_path'],
                                "name" => $tdb['name'],
                                "role" => $tdb['role']
                            ];
                        }
                    } else {
                        $testimonials_list = [
                            ["text" => "This ERP revolutionized our operations, streamlining finance and inventory.", "image" => "https://randomuser.me/api/portraits/women/1.jpg", "name" => "Briana Patton", "role" => "Operations Manager"],
                            ["text" => "Implementing this ERP was smooth and quick. The customizable interface made training effortless.", "image" => "https://randomuser.me/api/portraits/men/2.jpg", "name" => "Bilal Ahmed", "role" => "IT Manager"],
                            ["text" => "The support team is exceptional, guiding us through setup and providing ongoing assistance.", "image" => "https://randomuser.me/api/portraits/women/3.jpg", "name" => "Saman Malik", "role" => "Customer Support Lead"],
                            ["text" => "Seamless integration enhanced our business operations and efficiency.", "image" => "https://randomuser.me/api/portraits/men/4.jpg", "name" => "Omar Raza", "role" => "CEO"],
                            ["text" => "Robust features and quick support have transformed our workflow.", "image" => "https://randomuser.me/api/portraits/women/5.jpg", "name" => "Zainab Hussain", "role" => "Project Manager"],
                            ["text" => "The smooth implementation exceeded expectations and improved performance.", "image" => "https://randomuser.me/api/portraits/women/6.jpg", "name" => "Aliza Khan", "role" => "Business Analyst"],
                        ];
                    }

                    // Split into 3 columns
                    $per_col = max(1, ceil(count($testimonials_list) / 3));
                    $cols = array_chunk($testimonials_list, $per_col);
                    $durations = [15, 19, 17];
                    ?>

                    <div class="testimonials-mask">
                        <?php foreach ($cols as $idx => $col): ?>
                            <div class="testimonial-column"
                                style="animation-duration: <?php echo $durations[$idx % 3]; ?>s;">
                                <?php
                                for ($i = 0; $i < 2; $i++):
                                    foreach ($col as $t):
                                        ?>
                                        <div class="testimonial-card">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--primary)" opacity="0.3">
                                                    <path
                                                        d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12M10.017 21L10.017 18C10.017 16.8954 9.12157 16 8.017 16H5.017C4.46472 16 4.017 15.5523 4.017 15V9C4.017 8.44772 4.46472 8 5.017 8H9.017C9.56928 8 10.017 8.44772 10.017 9V12" />
                                                </svg>
                                                <div
                                                    style="font-size: 0.6rem; color: var(--primary); border: 1px solid var(--primary); padding: 2px 6px; border-radius: 2px;">
                                                    VERIFIED</div>
                                            </div>
                                            <p><?php echo h($t['text']); ?></p>
                                            <div class="testimonial-user">
                                                <img src="<?php echo h($t['image']); ?>" alt="<?php echo h($t['name']); ?>">
                                                <div class="testimonial-user-info">
                                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                        <h4><?php echo h($t['name']); ?></h4>
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="var(--primary)">
                                                            <path
                                                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                                        </svg>
                                                    </div>
                                                    <span><?php echo h($t['role']); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                endfor;
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
    </main>

    <!-- Lightbox Container -->
    <div class="lightbox" id="lightbox">
        <img src="" alt="Full View">
    </div>

    <footer class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Stormbreaker Portfolio. All rights reserved.</p>
        </div>
    </footer>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6" />
        </svg>
    </button>

    <!-- Chatbot Widget -->
    <div class="chatbot-container">
        <button class="chat-bubble" id="chatBubble">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 21 1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z" />
            </svg>
        </button>
        <div class="chat-window" id="chatWindow">
            <div class="chat-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 10px; height: 10px; background: #4ade80; border-radius: 50%;"></div>
                    <strong>Stormbreaker AI</strong>
                </div>
                <button id="closeChat"
                    style="background: none; border: none; color: white; cursor: pointer; font-size: 1.2rem;">&times;</button>
            </div>
            <div class="chat-messages" id="chatMessages">
                <div class="message bot">Halo! Saya Stormbreaker AI. Ada yang bisa saya bantu terkait layanan atau
                    portfolio ini?</div>
            </div>
            <form class="chat-input" id="chatForm">
                <input type="text" id="chatInput" placeholder="Tanyakan sesuatu..." autocomplete="off">
                <button type="submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <?php echo display_toasts(); ?>

    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.9.5/build/spline-viewer.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>