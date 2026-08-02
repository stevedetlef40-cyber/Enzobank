<!-- ====== BANK BUILDING SECTION ====== -->
<section class="enzo-building-section" id="building">
    <!-- Ambient background orbs -->
    <div class="build-orb build-orb-1"></div>
    <div class="build-orb build-orb-2"></div>
    <div class="build-orb build-orb-3"></div>

    <div class="enzo-container">
        <div class="enzo-building-grid">
            <!-- Left: Text Content -->
            <div class="enzo-building-text-col">
                <!-- Each child uses staggered data-delay for independent scroll entrance -->
                <div class="build-reveal" data-delay="0">
                    <span class="enzo-badge">OUR INSTITUTION</span>
                </div>
                <div class="build-reveal" data-delay="150">
                    <h2 class="enzo-section-title" style="text-align:left">
                        Built on <span class="enzo-text-grad">Trust</span> &amp; <span class="enzo-text-grad">Innovation</span>
                    </h2>
                </div>
                <div class="build-reveal" data-delay="300">
                    <p class="enzo-building-desc">
                        EnzoBank's headquarters stand as a symbol of our commitment to financial excellence. 
                        From this building, we power digital banking for over 2 million customers across 
                        the United States and beyond — combining cutting-edge technology with the stability 
                        of a regulated financial institution.
                    </p>
                </div>
                <div class="build-reveal" data-delay="450">
                    <div class="enzo-building-stats">
                        <div class="enzo-building-stat">
                            <span class="enzo-building-stat-num">$50B+</span>
                            <span class="enzo-building-stat-label">Assets Managed</span>
                        </div>
                        <div class="enzo-building-stat">
                            <span class="enzo-building-stat-num">99.9%</span>
                            <span class="enzo-building-stat-label">Uptime SLA</span>
                        </div>
                        <div class="enzo-building-stat">
                            <span class="enzo-building-stat-num">150+</span>
                            <span class="enzo-building-stat-label">Countries</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right: Building Visual -->
            <div class="enzo-building-visual-col">
                <div class="build-reveal" data-delay="250">
                    <div class="enzo-building-frame">
                        <!-- Animated scan line -->
                        <div class="build-scan-line"></div>
                        <!-- Ambient glow behind image -->
                        <div class="build-image-glow"></div>
                        <!-- The image -->
                        <div class="build-image-wrapper">
                            <img src="{{ asset('frontend/images/bank-building-block.jpg') }}" 
                                 alt="EnzoBank Headquarters" 
                                 class="enzo-building-img"
                                 loading="lazy">
                        </div>
                        <!-- Gradient overlay at bottom -->
                        <div class="enzo-building-overlay-grad"></div>
                    </div>
                </div>
                <!-- Floating badges with independent reveals -->
                <div class="build-badge build-badge-1 build-reveal" data-delay="600">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>FDIC Insured</span>
                </div>
                <div class="build-badge build-badge-2 build-reveal" data-delay="750">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>256-bit AES</span>
                </div>
                <div class="build-badge build-badge-3 build-reveal" data-delay="900">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#A78BFA" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    <span>24/7 Support</span>
                </div>
            </div>
        </div>
    </div>
</section>

@push("script")
<script>
(function() {
    const buildingSection = document.querySelector('.enzo-building-section');
    if (!buildingSection || !('IntersectionObserver' in window)) return;

    // --- Scroll-Triggered Reveal (stagger by data-delay) ---
    const revealEls = buildingSection.querySelectorAll('.build-reveal');
    const revealObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const el = entry.target;
                const delay = parseInt(el.getAttribute('data-delay') || '0', 10);
                setTimeout(function() {
                    el.classList.add('build-visible');
                }, delay);
                revealObserver.unobserve(el);
            }
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

    revealEls.forEach(function(el) { revealObserver.observe(el); });

    // --- Image Parallax on Scroll ---
    const frame = buildingSection.querySelector('.enzo-building-frame');
    const img = buildingSection.querySelector('.enzo-building-img');
    if (frame && img && window.innerWidth > 991) {
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const rect = frame.getBoundingClientRect();
                    const centerY = rect.top + rect.height / 2;
                    const viewportCenter = window.innerHeight / 2;
                    const offset = (centerY - viewportCenter) / (window.innerHeight * 0.5);
                    const translateY = Math.max(-20, Math.min(20, offset * 8));
                    img.style.transform = 'translateY(' + translateY + 'px) scale(1.03)';
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }
})();
</script>
@endpush
