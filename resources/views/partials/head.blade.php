<meta name="theme-color" content="#0E1B3D">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%230E1B3D'/%3E%3Cpath d='M7 17l6-6m0 0l1.5-3.5 3-1-1 3-3.5 1.5z' stroke='%23FFC933' stroke-width='1.8' fill='none' stroke-linecap='round'/%3E%3C/svg%3E">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { theme: { extend: {
    fontFamily: { sans: ['Figtree', 'system-ui', 'sans-serif'], display: ['"Bricolage Grotesque"', 'Figtree', 'sans-serif'] },
    colors: {
        brand: { 50:'#F1F4FC', 100:'#E1E8F8', 200:'#C3D1F1', 300:'#94ABE4', 400:'#6280D2', 500:'#3F5FBF', 600:'#2E4AA3', 700:'#243B84', 800:'#1B2D66', 900:'#0E1B3D' },
        sun:   { 300:'#FFE08A', 400:'#FFD257', 500:'#FFC933', 600:'#E9AE0C' },
        paper: '#F6F2E9'
    }
} } }
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500..800&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
<style>
    :root { --ink: #0E1B3D; --ink-2: #1B2D66; --sun: #FFC933; --sun-d: #E9AE0C; --paper: #F6F2E9; --line: #E6DFCF; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Figtree', system-ui, sans-serif; font-size: 16px; color: #1e293b; }
    h1, h2, h3, .font-display { font-family: 'Bricolage Grotesque', 'Figtree', sans-serif; letter-spacing: -.015em; }
    [x-cloak] { display: none !important; }
    ::selection { background: var(--sun); color: var(--ink); }
    :focus-visible { outline: 3px solid var(--sun); outline-offset: 2px; border-radius: 6px; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb { background: #cfc7b3; border-radius: 99px; }

    /* ===== Keyframes ===== */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(.96) translateY(6px); } to { opacity: 1; transform: none; } }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
    @keyframes drawSeg { from { stroke-dasharray: 0 100; } }
    @keyframes drawLine { from { stroke-dashoffset: var(--len, 300); } to { stroke-dashoffset: 0; } }
    @keyframes grow { to { width: var(--w); } }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes ripple { to { transform: scale(3); opacity: 0; } }
    @keyframes bob { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
    @keyframes mark { to { background-size: 100% 100%; } }
    @keyframes pulseRing { 0% { box-shadow: 0 0 0 0 rgba(255,201,51,.7); } 70% { box-shadow: 0 0 0 10px rgba(255,201,51,0); } 100% { box-shadow: 0 0 0 0 rgba(255,201,51,0); } }

    /* ===== Entry animations ===== */
    main > * { animation: fadeUp .5s ease backwards; }
    main > *:nth-child(2) { animation-delay: .05s; }
    main > *:nth-child(3) { animation-delay: .1s; }
    main > *:nth-child(4) { animation-delay: .15s; }
    .reveal { animation: fadeUp .5s ease var(--d, 0s) backwards; }
    .pop { animation: scaleIn .35s ease var(--d, 0s) backwards; }
    tbody tr { animation: fadeIn .3s ease backwards; }
    tbody tr:nth-child(1) { animation-delay: .02s; } tbody tr:nth-child(2) { animation-delay: .04s; }
    tbody tr:nth-child(3) { animation-delay: .06s; } tbody tr:nth-child(4) { animation-delay: .08s; }
    tbody tr:nth-child(5) { animation-delay: .1s; }  tbody tr:nth-child(6) { animation-delay: .12s; }
    tbody tr:nth-child(7) { animation-delay: .14s; } tbody tr:nth-child(8) { animation-delay: .16s; }
    .toast { animation: slideInRight .35s ease backwards; }
    .modal-overlay { animation: fadeIn .15s ease backwards; }
    .modal-panel { animation: scaleIn .22s ease backwards; }
    .donut-seg { animation: drawSeg 1s ease backwards; }
    .draw-line { stroke-dasharray: var(--len, 300); animation: drawLine 1s ease forwards; }
    .bar { width: 0; animation: grow .8s ease .2s forwards; }
    .bob { animation: bob 5s ease-in-out infinite; }
    .pulse-ring { animation: pulseRing 2s infinite; }

    /* ===== Komponen ===== */
    .card { background: #fff; border: 1px solid var(--line); border-radius: 1.1rem;
            box-shadow: 0 1px 0 rgba(14,27,61,.04), 0 14px 26px -18px rgba(14,27,61,.2); }
    .card-hover { transition: transform .22s ease, box-shadow .22s ease; }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 4px 0 0 var(--sun), 0 20px 30px -20px rgba(14,27,61,.4); }

    .btn-primary { position: relative; overflow: hidden; color: #fff; font-weight: 600; background: var(--ink);
        box-shadow: 0 3px 0 0 var(--sun-d); transition: transform .12s ease, box-shadow .12s ease, background-color .15s ease; }
    .btn-primary:hover { background: var(--ink-2); transform: translateY(-1px); box-shadow: 0 5px 0 0 var(--sun-d); }
    .btn-primary:active { transform: translateY(2px); box-shadow: 0 1px 0 0 var(--sun-d); }
    .btn-sun { position: relative; overflow: hidden; background: var(--sun); color: var(--ink); font-weight: 700;
        box-shadow: 0 3px 0 0 #B88A00; transition: transform .12s ease, box-shadow .12s ease; }
    .btn-sun:hover { transform: translateY(-1px); box-shadow: 0 5px 0 0 #B88A00; }
    .btn-sun:active { transform: translateY(2px); box-shadow: 0 1px 0 0 #B88A00; }
    .btn-glass { background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.28); color: #fff; transition: background-color .15s ease, transform .12s ease; }
    .btn-glass:hover { background: rgba(255,255,255,.22); transform: translateY(-1px); }
    .ripple-dot { position: absolute; border-radius: 9999px; background: rgba(255,255,255,.45); transform: scale(0); pointer-events: none; animation: ripple .5s ease-out forwards; }
    .btn-sun .ripple-dot { background: rgba(14,27,61,.25); }
    .is-loading { pointer-events: none; opacity: .85; }
    .is-loading::before { content: ''; display: inline-block; width: 1em; height: 1em; margin-right: .5em; vertical-align: -.15em;
        border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }

    .input-fx { transition: box-shadow .15s ease, border-color .15s ease; background: #fff; }
    .input-fx:focus { outline: none; border-color: var(--ink); box-shadow: 0 0 0 4px rgba(255,201,51,.45); }

    /* Kolom dengan label yang naik saat diisi */
    .field { position: relative; }
    .field > input { width: 100%; padding: 1.45rem 1rem .5rem; border: 1.5px solid #d6cfbd; border-radius: .85rem; background: #fff; font-size: 1rem; transition: box-shadow .15s ease, border-color .15s ease; }
    .field > input:focus { outline: none; border-color: var(--ink); box-shadow: 0 0 0 4px rgba(255,201,51,.45); }
    .field > label { position: absolute; left: 1rem; top: 1rem; font-size: 1rem; color: #7b7566; pointer-events: none; transition: all .15s ease; transform-origin: left; }
    .field > input:focus + label, .field > input:not(:placeholder-shown) + label { top: .42rem; font-size: .7rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--ink-2); }
    .field.has-error > input { border-color: #dc2626; }

    .sidebar-bg { background-color: var(--ink);
        background-image: linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
        background-size: 28px 28px; }
    .brand-badge { background: var(--sun); color: var(--ink); box-shadow: 0 3px 0 0 #B88A00; }
    .nav-link { position: relative; display: flex; align-items: center; gap: .75rem; padding: .62rem .8rem; border-radius: .8rem;
        font-size: .9rem; font-weight: 500; color: #a9b8dc; transition: background-color .15s ease, color .15s ease, transform .15s ease; }
    .nav-link:hover { background: rgba(255,255,255,.08); color: #fff; transform: translateX(3px); }
    .nav-link svg { transition: transform .18s ease; }
    .nav-link:hover svg { transform: scale(1.12) rotate(-4deg); }
    .nav-link-active { color: var(--ink); background: var(--sun); font-weight: 700; box-shadow: 0 3px 0 0 #B88A00; }
    .nav-link-active:hover { background: var(--sun); color: var(--ink); transform: none; }

    .hero { position: relative; overflow: hidden; color: #fff; border-radius: 1.25rem; background-color: var(--ink);
        background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 30px 30px; box-shadow: 0 5px 0 0 var(--sun), 0 24px 34px -22px rgba(14,27,61,.6);
        animation: fadeUp .5s ease backwards; }
    .hero::after { content: ''; position: absolute; right: -40px; top: -40px; width: 210px; height: 210px; border-radius: 28px; transform: rotate(14deg);
        background: repeating-linear-gradient(135deg, var(--sun) 0 14px, transparent 14px 30px); opacity: .22; pointer-events: none; }
    .marker { background-image: linear-gradient(transparent 62%, var(--sun) 62%); background-repeat: no-repeat; background-size: 0% 100%; animation: mark .8s ease .5s forwards; color: inherit; }
    .hero .marker { background-image: linear-gradient(transparent 62%, rgba(255,201,51,.85) 62%); }

    .ico { display: flex; align-items: center; justify-content: center; color: #fff; }
    .ico-ink    { background: var(--ink); color: var(--sun); }
    .ico-blue   { background: #3b82f6; }
    .ico-sky    { background: #0ea5e9; }
    .ico-amber  { background: #f59e0b; }
    .ico-green  { background: #10b981; }
    .ico-red    { background: #ef4444; }
    .ico-violet { background: #8b5cf6; }
    .ico-slate  { background: #64748b; }
    .avatar { background: var(--ink); color: var(--sun); }

    .kat-row { transition: background-color .15s ease; border-radius: .6rem; }
    .kat-row:hover { background: #faf7ef; }
    .copy-btn { transition: background-color .15s ease, transform .1s ease; }
    .copy-btn:active { transform: scale(.92); }
    .copy-check { animation: scaleIn .25s ease backwards; }

    #topbar-progress { position: fixed; top: 0; left: 0; height: 3px; width: 0; z-index: 9999; opacity: 0;
        background: var(--sun); transition: width .8s ease, opacity .3s ease; }
    body.page-app { background-color: var(--paper);
        background-image: radial-gradient(rgba(14,27,61,.075) 1px, transparent 1px); background-size: 22px 22px; background-attachment: fixed; }

    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; scroll-behavior: auto !important; }
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('[data-count]').forEach(function (el) {
        var end = parseInt(el.dataset.count, 10) || 0;
        if (reduce || end === 0) { el.textContent = end; return; }
        var t0 = performance.now(), dur = 800;
        (function tick(t) {
            var p = Math.min((t - t0) / dur, 1), e = 1 - Math.pow(1 - p, 3);
            el.textContent = Math.round(end * e);
            if (p < 1) requestAnimationFrame(tick); else el.textContent = end;
        })(t0);
    });

    var bar = document.createElement('div');
    bar.id = 'topbar-progress';
    document.body.appendChild(bar);
    window.addEventListener('beforeunload', function () { bar.style.opacity = 1; bar.style.width = '85%'; });

    document.addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        var btn = e.target.querySelector('button[type=submit]');
        if (btn) { setTimeout(function () { btn.classList.add('is-loading'); }, 0); }
    });

    if (reduce) return;

    document.querySelectorAll('.btn-primary, .btn-sun').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var r = btn.getBoundingClientRect(), d = Math.max(r.width, r.height);
            var dot = document.createElement('span');
            dot.className = 'ripple-dot';
            dot.style.width = dot.style.height = d + 'px';
            dot.style.left = (e.clientX - r.left - d / 2) + 'px';
            dot.style.top = (e.clientY - r.top - d / 2) + 'px';
            btn.appendChild(dot);
            setTimeout(function () { dot.remove(); }, 550);
        });
    });

    // Parallax lembut: elemen ber-atribut data-depth bergeser mengikuti kursor di dalam [data-parallax]
    if (window.matchMedia('(pointer: fine)').matches) {
        document.querySelectorAll('[data-parallax]').forEach(function (box) {
            box.addEventListener('mousemove', function (e) {
                var r = box.getBoundingClientRect();
                var x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5;
                box.querySelectorAll('[data-depth]').forEach(function (el) {
                    var d = parseFloat(el.dataset.depth) || 0;
                    el.style.translate = (-x * d) + 'px ' + (-y * d) + 'px';
                });
            });
            box.addEventListener('mouseleave', function () {
                box.querySelectorAll('[data-depth]').forEach(function (el) { el.style.translate = ''; });
            });
        });
    }
});
</script>
