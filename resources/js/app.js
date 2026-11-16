import './bootstrap';

/* ── Portfolio Vanilla JS ── */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    /* ── Preloader ── */
    const preloader = document.getElementById('portfolio-preloader');
    const counter = preloader ? preloader.querySelector('.portfolio-preloader-counter') : null;
    const curtain = preloader ? preloader.querySelector('.portfolio-preloader-curtain') : null;

    if (preloader && counter && curtain) {
        const begin = performance.now();
        const duration = 900;
        const ease = (p) => 1 - Math.pow(1 - p, 3);
        let finished = false;

        const loop = (now) => {
            if (finished) return;
            const p = ease(Math.min(1, (now - begin) / duration));
            counter.textContent = Math.round(p * 100).toString().padStart(2, '0');

            if (p < 1) {
                requestAnimationFrame(loop);
            } else {
                finished = true;
                counter.textContent = '100';
                gsap.to(curtain, {
                    clipPath: 'inset(100% 0 0 0)',
                    duration: 0.8,
                    ease: 'power2.out',
                    delay: 0.35,
                    onComplete: () => {
                        preloader.style.display = 'none';
                        initAll();
                    },
                });
            }
        };
        requestAnimationFrame(loop);

        // Fallback: ensure preloader never gets stuck
        setTimeout(() => {
          if (!finished) {
            finished = true;
            counter.textContent = '100';
            gsap.to(curtain, {
                clipPath: 'inset(100% 0 0 0)',
                duration: 0.4,
                ease: 'power2.out',
                onComplete: () => {
                    preloader.style.display = 'none';
                    initAll();
                },
            });
          }
        }, 3000);
    } else {
        initAll();
    }

    /* ── Inisialisasi semua animasi setelah preloader ── */
    function initAll() {
        initLenis();
        initReveals();
        initCursor();
        initMarquee();
        initCounters();
        initProjectCards();
        initExperienceStack();
        initProgressBar();
    }

    /* ── Lenis + ScrollTrigger sync ── */
    function initLenis() {
        const lenis = new Lenis({
            wrapper: window,
            content: document.body,
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            orientation: 'vertical',
            gestureOrientation: 'vertical',
            smoothWheel: true,
            touchMultiplier: 2,
            autoResize: true,
            lerp: 0.1,
        });

        gsap.ticker.add((time) => {
            lenis.raf(time * 0.001);
            if (window.ScrollTrigger) {
                ScrollTrigger.update();
            }
        });
    }

    /* ── Section reveal animations (clip-path only) ── */
    function initReveals() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        gsap.utils.toArray('.reveal').forEach((el) => {
            if (reduced) {
                gsap.set(el, { opacity: 1, clipPath: 'inset(0)' });
                return;
            }
            gsap.set(el, { opacity: 0, clipPath: 'inset(100% 0 0 0)' });
            ScrollTrigger.create({
                trigger: el,
                scroller: document.body,
                start: 'top 85%',
                toggleActions: 'play none none none',
                onEnter: () => {
                    gsap.to(el, {
                        opacity: 1,
                        clipPath: 'inset(0)',
                        duration: 0.8,
                        ease: 'power2.out',
                    });
                },
            });
        });
    }

    /* ── Custom cursor ── */
    function initCursor() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isTouch = window.matchMedia('(pointer: coarse)').matches;

        if (reduced || isTouch) {
            document.body.classList.add('cursor-hidden');
            return;
        }

        const cursor = document.createElement('div');
        cursor.className = 'portfolio-cursor';
        cursor.innerHTML = '<div class="portfolio-cursor-dot"></div><div class="portfolio-cursor-label"></div>';
        document.body.appendChild(cursor);

        const dot = cursor.querySelector('.portfolio-cursor-dot');
        const label = cursor.querySelector('.portfolio-cursor-label');

        let mx = 0, my = 0;
        let cx = 0, cy = 0;
        let active = false;
        let currentLabel = '';

        const animate = () => {
            cx += (mx - cx) * 0.18;
            cy += (my - cy) * 0.18;
            cursor.style.transform = `translate3d(${cx}px, ${cy}px, 0)`;
            requestAnimationFrame(animate);
        };
        animate();

        document.addEventListener('pointermove', (e) => {
            mx = e.clientX;
            my = e.clientY;
        });

        const setActive = (isActive, lbl = '') => {
            if (active === isActive && currentLabel === lbl) return;
            active = isActive;
            currentLabel = lbl;
            label.textContent = lbl;
            dot.style.transform = active ? 'scale(2.5)' : 'scale(1)';
            cursor.style.padding = active ? '8px 12px' : '0';
            cursor.style.background = active ? 'rgba(17,17,16,0.1)' : 'transparent';
            cursor.style.backdropFilter = active ? 'blur(4px)' : 'none';
            label.style.opacity = active ? '1' : '0';
        };

        document.querySelectorAll('a, button, [role="button"], .portfolio-project-card').forEach((el) => {
            const lbl = el.dataset.cursorLabel || el.getAttribute('aria-label') || '';
            el.addEventListener('pointerenter', () => setActive(true, lbl));
            el.addEventListener('pointerleave', () => setActive(false, ''));
        });
    }

    /* ── Marquee (skill names) ── */
    function initMarquee() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced) return;

        const tracks = document.querySelectorAll('.portfolio-marquee-track');
        tracks.forEach((track) => {
            const items = track.innerHTML;
            track.innerHTML = items + items;

            const ctx = gsap.context(() => {
                const tl = gsap.to(track, {
                    x: '-50%',
                    duration: 20,
                    ease: 'none',
                    repeat: -1,
                });
                return () => tl.kill();
            });
        });
    }

    /* ── Counters (allowed real numbers only) ── */
    function initCounters() {
        const counters = document.querySelectorAll('[data-counter]');
        counters.forEach((el) => {
            const target = parseInt(el.dataset.counter, 10);
            const ctx = gsap.context(() => {
                ScrollTrigger.create({
                    trigger: el,
                    scroller: document.body,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                    onEnter: () => {
                        gsap.fromTo(
                            el,
                            { innerText: '0' },
                            {
                                innerText: target,
                                duration: 1,
                                ease: 'power2.out',
                                snap: { innerText: 1 },
                                onUpdate: function () {
                                    el.textContent = Math.round(this.targets()[0].innerText)
                                        .toString()
                                        .padStart(2, '0');
                                },
                            }
                        );
                    },
                });
            });
        });
    }

    /* ── Project card tilt ── */
    function initProjectCards() {
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduced) return;

        document.querySelectorAll('.portfolio-project-card').forEach((card) => {
            const tiltContainer = card.querySelector('.portfolio-project-tilt-container');
            const tiltCard = card.querySelector('.portfolio-project-tilt-card');
            if (!tiltContainer || !tiltCard) return;

            card.addEventListener('pointermove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -5;
                const rotateY = ((x - centerX) / centerX) * 5;
                gsap.to(tiltCard, {
                    rotateX,
                    rotateY,
                    duration: 0.5,
                    ease: 'power2.out',
                });
            });

            card.addEventListener('pointerleave', () => {
                gsap.to(tiltCard, {
                    rotateX: 0,
                    rotateY: 0,
                    duration: 0.5,
                    ease: 'power2.out',
                });
            });
        });
    }

    /* ── Sticky stacking cards for Experience ── */
    function initExperienceStack() {
        const cards = document.querySelectorAll('.portfolio-timeline-list-item');
        if (cards.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const ctx = gsap.context(() => {
            gsap.set(cards.slice(1), { y: 40, opacity: 0.4 });
            gsap.to(cards.slice(1), {
                y: 0,
                opacity: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: cards[0],
                    scroller: document.body,
                    start: 'top top',
                    end: `+=${cards.length * 100}`,
                    scrub: 1,
                    anticipatePin: 1,
                },
            });
        });
    }

    /* ── Progress bar ── */
    function initProgressBar() {
        const bar = document.createElement('div');
        bar.className = 'portfolio-progress-bar';
        bar.style.cssText = 'position:fixed;top:0;left:0;height:1px;background:var(--fg);z-index:50;width:0%;transform-origin:left';
        document.body.appendChild(bar);

        const update = () => {
            const max = Math.max(1, document.body.scrollHeight - window.innerHeight);
            const p = (window.scrollY / max) * 100;
            bar.style.width = `${Math.min(100, p)}%`;
        };
        window.addEventListener('scroll', update, { passive: true });
        update();
    }
});
