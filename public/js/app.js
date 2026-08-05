// ═══════════════════════════════════════════════════════════════
// Muhammad Ali — PORTFOLIO
//
// Motion budget: reveal-on-scroll, stat count-up, nav state.
// That's it. No custom cursor, no card tilt, no parallax.
// Everything animated is guarded by prefers-reduced-motion.
// ═══════════════════════════════════════════════════════════════

(function () {
    "use strict";

    var reduceMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    // ── THEME ────────────────────────────────────────────────────
    // The <head> script has already applied the stored theme before
    // first paint. This only wires up the toggle.
    function initTheme() {
        var toggle = document.getElementById("themeToggle");
        if (!toggle) return;

        toggle.addEventListener("click", function () {
            var root = document.documentElement;
            var current = root.getAttribute("data-theme");

            // No explicit choice yet — resolve what's actually on screen.
            if (!current) {
                current = window.matchMedia("(prefers-color-scheme: light)")
                    .matches
                    ? "light"
                    : "dark";
            }

            var next = current === "dark" ? "light" : "dark";
            root.setAttribute("data-theme", next);

            try {
                localStorage.setItem("theme", next);
            } catch (e) {
                /* private mode — the choice just won't persist */
            }

            toggle.setAttribute(
                "aria-label",
                next === "dark"
                    ? "Switch to light theme"
                    : "Switch to dark theme",
            );
        });
    }

    // ── NAV: background on scroll ────────────────────────────────
    function initNavScroll() {
        var navbar = document.getElementById("navbar");
        if (!navbar) return;

        var ticking = false;

        function update() {
            navbar.classList.toggle("scrolled", window.scrollY > 24);
            ticking = false;
        }

        window.addEventListener(
            "scroll",
            function () {
                if (!ticking) {
                    ticking = true;
                    window.requestAnimationFrame(update);
                }
            },
            { passive: true },
        );

        update();
    }

    // ── NAV: active section highlight ────────────────────────────
    function initActiveSection() {
        var sections = document.querySelectorAll("section[id]");
        var links = document.querySelectorAll('.nav-links a[href*="#"]');
        if (!sections.length || !links.length) return;

        var ticking = false;

        function update() {
            var current = "";
            var offset = window.scrollY + 140;

            sections.forEach(function (sec) {
                if (offset >= sec.offsetTop) current = sec.id;
            });

            links.forEach(function (a) {
                var href = a.getAttribute("href") || "";
                a.classList.toggle(
                    "active",
                    current !== "" && href.indexOf("#" + current) !== -1,
                );
            });

            ticking = false;
        }

        window.addEventListener(
            "scroll",
            function () {
                if (!ticking) {
                    ticking = true;
                    window.requestAnimationFrame(update);
                }
            },
            { passive: true },
        );
    }

    // ── MOBILE MENU ──────────────────────────────────────────────
    function initMobileMenu() {
        var hamburger = document.getElementById("hamburger");
        var overlay = document.getElementById("mobileOverlay");
        var closeBtn = document.getElementById("mobileClose");
        if (!hamburger || !overlay) return;

        function open() {
            overlay.classList.add("open");
            overlay.setAttribute("aria-hidden", "false");
            hamburger.setAttribute("aria-expanded", "true");
            document.body.style.overflow = "hidden";
            if (closeBtn) closeBtn.focus();
        }

        function close() {
            overlay.classList.remove("open");
            overlay.setAttribute("aria-hidden", "true");
            hamburger.setAttribute("aria-expanded", "false");
            document.body.style.overflow = "";
        }

        hamburger.addEventListener("click", open);
        if (closeBtn) closeBtn.addEventListener("click", close);

        overlay.querySelectorAll("a").forEach(function (a) {
            a.addEventListener("click", close);
        });

        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape" && overlay.classList.contains("open")) {
                close();
                hamburger.focus();
            }
        });
    }

    // ── REVEAL ON SCROLL ─────────────────────────────────────────
    // Fade + 8px rise, 60ms stagger, fires once per element.
    function initReveal() {
        var items = document.querySelectorAll(".reveal");
        if (!items.length) return;

        if (reduceMotion || !("IntersectionObserver" in window)) {
            items.forEach(function (el) {
                el.classList.add("visible");
            });
            return;
        }

        var observer = new IntersectionObserver(
            function (entries) {
                var shown = 0;

                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    var delay = shown * 60;
                    shown++;
                    window.setTimeout(function () {
                        entry.target.classList.add("visible");
                    }, delay);
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.08, rootMargin: "0px 0px -40px 0px" },
        );

        items.forEach(function (el) {
            observer.observe(el);
        });
    }

    // ── STAT COUNT-UP ────────────────────────────────────────────
    // Preserves any non-digit characters ("3+", "100%") around the number.
    function initCounters() {
        var nums = document.querySelectorAll(".stat-n, .esc-n");
        if (!nums.length) return;

        if (reduceMotion || !("IntersectionObserver" in window)) return;

        var observer = new IntersectionObserver(
            function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;

                    var el = entry.target;
                    var text = el.textContent.trim();
                    var match = text.match(/(\d+)/);
                    observer.unobserve(el);
                    if (!match) return;

                    var target = parseInt(match[1], 10);
                    if (!target) return;

                    var prefix = text.slice(0, match.index);
                    var suffix = text.slice(match.index + match[1].length);
                    var duration = 700;
                    var start = null;

                    function tick(now) {
                        if (start === null) start = now;
                        var progress = Math.min((now - start) / duration, 1);
                        // ease-out cubic
                        var eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent =
                            prefix + Math.round(eased * target) + suffix;
                        if (progress < 1) window.requestAnimationFrame(tick);
                    }

                    window.requestAnimationFrame(tick);
                });
            },
            { threshold: 0.5 },
        );

        nums.forEach(function (el) {
            observer.observe(el);
        });
    }

    // ── SMOOTH SCROLL for in-page anchors ────────────────────────
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
            anchor.addEventListener("click", function (e) {
                var id = this.getAttribute("href");
                if (!id || id === "#") return;

                var target = document.querySelector(id);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({
                    behavior: reduceMotion ? "auto" : "smooth",
                    block: "start",
                });
                // Keep the URL shareable without triggering a second jump.
                if (history.replaceState) history.replaceState(null, "", id);
            });
        });
    }

    // ── CONTACT FORM submit state ────────────────────────────────
    function initContactForm() {
        var form = document.getElementById("contactForm");
        if (!form) return;

        form.addEventListener("submit", function () {
            var btn = form.querySelector(".form-btn");
            if (!btn) return;
            btn.disabled = true;
            btn.textContent = "Sending…";
        });
    }

    // ── CATEGORY FILTERS (projects / blog) ───────────────────────
    function initFilters() {
        var buttons = document.querySelectorAll(".filter-btn");
        if (!buttons.length) return;

        buttons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                var bar = btn.closest(".proj-filters, .blog-filter-bar");
                var scope = bar ? bar.parentNode : document;

                (bar || document)
                    .querySelectorAll(".filter-btn")
                    .forEach(function (b) {
                        b.classList.remove("active");
                        b.setAttribute("aria-pressed", "false");
                    });
                btn.classList.add("active");
                btn.setAttribute("aria-pressed", "true");

                var filter = btn.dataset.filter;
                scope
                    .querySelectorAll("[data-category]")
                    .forEach(function (card) {
                        var match =
                            filter === "all" ||
                            card.dataset.category === filter;
                        card.hidden = !match;
                    });
            });
        });
    }

    // ── BOOT ─────────────────────────────────────────────────────
    function boot() {
        initTheme();
        initNavScroll();
        initActiveSection();
        initMobileMenu();
        initReveal();
        initCounters();
        initSmoothScroll();
        initContactForm();
        initFilters();
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", boot);
    } else {
        boot();
    }
})();
