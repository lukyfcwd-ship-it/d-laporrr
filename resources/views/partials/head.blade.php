<meta name="theme-color" content="#4f46e5">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Crect width='24' height='24' rx='6' fill='%234f46e5'/%3E%3Cpath d='M7 17l6-6m0 0l1.5-3.5 3-1-1 3-3.5 1.5z' stroke='white' stroke-width='1.6' fill='none' stroke-linecap='round'/%3E%3C/svg%3E">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] } } } }</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>
<style>
    html { scroll-behavior: smooth; }
    body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; font-size: 16px; }
    [x-cloak] { display: none !important; }
    ::selection { background: #e0e7ff; }
    :focus-visible { outline: 2px solid #6366f1; outline-offset: 2px; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    /* ===== Keyframes (kept minimal & purposeful) ===== */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: none; } }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleIn { from { opacity: 0; transform: scale(.96) translateY(6px); } to { opacity: 1; transform: none; } }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(24px); } to { opacity: 1; transform: none; } }
    @keyframes drawSeg { from { stroke-dasharray: 0 100; } }
    @keyframes drawLine { from { stroke-dashoffset: var(--len, 300); } to { stroke-dashoffset: 0; } }
    @keyframes grow { to { width: var(--w); } }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes ripple { to { transform: scale(3); opacity: 0; } }

    /* ===== Entry animations (subtle) ===== */
    main > * { animation: fadeUp .45s ease backwards; }
    main > *:nth-child(2) { animation-delay: .05s; }
    main > *:nth-child(3) { animation-delay: .1s; }
    main > *:nth-child(4) { animation-delay: .15s; }
    .reveal { animation: fadeUp .45s ease var(--d, 0s) backwards; }
    .pop { animation: scaleIn .3s ease var(--d, 0s) backwards; }
    tbody tr { animation: fadeIn .3s ease backwards; }
    tbody tr:nth-child(1) { animation-delay: .02s; } tbody tr:nth-child(2) { animation-delay: .04s; }
    tbody tr:nth-child(3) { animation-delay: .06s; } tbody tr:nth-child(4) { animation-delay: .08s; }
    tbody tr:nth-child(5) { animation-delay: .1s; }  tbody tr:nth-child(6) { animation-delay: .12s; }
    tbody tr:nth-child(7) { animation-delay: .14s; } tbody tr:nth-child(8) { animation-delay: .16s; }
    .toast { animation: slideInRight .3s ease backwards; }
    .modal-overlay { animation: fadeIn .15s ease backwards; }
    .modal-panel { animation: scaleIn .2s ease backwards; }
    .donut-seg { animation: drawSeg 1s ease backwards; }
    .draw-line { stroke-dasharray: var(--len, 300); animation: drawLine 1s ease forwards; }
    .bar { width: 0; animation: grow .8s ease .2s forwards; }

    /* ===== Components (clean, single accent) ===== */
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: .875rem;
            box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 6px 16px -10px rgba(15,23,42,.08); }
    .card-hover { transition: box-shadow .2s ease, border-color .2s ease, transform .2s ease; }
    .card-hover:hover { border-color: #e0e7ff; transform: translateY(-3px); box-shadow: 0 1px 2px rgba(15,23,42,.04), 0 14px 26px -14px rgba(79,70,229,.22); }

    .btn-primary { color: #fff; font-weight: 600; background: #4f46e5;
        box-shadow: 0 1px 2px rgba(79,70,229,.12); transition: background-color .15s ease, transform .1s ease; position: relative; overflow: hidden; }
    .btn-primary:hover { background: #4338ca; }
    .btn-primary:active { transform: scale(.98); }
    .btn-glass { background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); color: #fff; transition: background-color .15s ease; }
    .btn-glass:hover { background: rgba(255,255,255,.25); }
    .btn-white { position: relative; overflow: hidden; background: #fff; color: #4f46e5; font-weight: 700; box-shadow: 0 1px 2px rgba(0,0,0,.06); transition: background-color .15s ease; }
    .btn-white:hover { background: #f5f5ff; }
    .ripple-dot { position: absolute; border-radius: 9999px; background: rgba(255,255,255,.5); transform: scale(0); pointer-events: none; animation: ripple .5s ease-out forwards; }
    .is-loading { pointer-events: none; opacity: .85; }
    .is-loading::before { content: ''; display: inline-block; width: 1em; height: 1em; margin-right: .5em; vertical-align: -.15em;
        border: 2px solid rgba(255,255,255,.4); border-top-color: #fff; border-radius: 50%; animation: spin .7s linear infinite; }

    .input-fx { transition: box-shadow .15s ease, border-color .15s ease; }
    .input-fx:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.14); }

    .sidebar-bg { background: #0f172a; }
    .brand-badge { background: #4f46e5; }
    .nav-link { position: relative; display: flex; align-items: center; gap: .75rem; padding: .625rem .75rem; border-radius: .65rem;
        font-size: .875rem; font-weight: 500; color: #94a3b8; transition: background-color .15s ease, color .15s ease; }
    .nav-link:hover { background: rgba(255,255,255,.06); color: #fff; }
    .nav-link svg { transition: transform .15s ease; }
    .nav-link:hover svg { transform: scale(1.12); }
    .nav-link-active { color: #fff; background: #4f46e5; }
    .nav-link-active:hover { background: #4f46e5; }

    .kat-row { transition: background-color .15s ease, padding .15s ease; border-radius: .6rem; }
    .kat-row:hover { background: #f8fafc; }

    .copy-btn { transition: background-color .15s ease, transform .1s ease; }
    .copy-btn:active { transform: scale(.92); }
    .copy-check { animation: scaleIn .25s cubic-bezier(.3,1.5,.5,1) backwards; }

    .hero { position: relative; overflow: hidden; color: #fff; border-radius: 1rem;
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        box-shadow: 0 12px 26px -14px rgba(67,56,202,.5); }

    .ico { display: flex; align-items: center; justify-content: center; color: #fff; }
    .ico-blue   { background: #3b82f6; }
    .ico-amber  { background: #f59e0b; }
    .ico-green  { background: #10b981; }
    .ico-red    { background: #ef4444; }
    .ico-violet { background: #6366f1; }
    .ico-slate  { background: #64748b; }
    .avatar { background: #4f46e5; color: #fff; }

    .auth-bg { background: #4338ca; }
    #topbar-progress { position: fixed; top: 0; left: 0; height: 3px; width: 0; z-index: 9999; opacity: 0;
        background: #4f46e5; transition: width .8s ease, opacity .3s ease; }
    body.page-app { background: #f8fafc; }

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
        var t0 = performance.now(), dur = 700;
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

    document.querySelectorAll('.btn-primary, .btn-white').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            var r = btn.getBoundingClientRect();
            var d = Math.max(r.width, r.height);
            var dot = document.createElement('span');
            dot.className = 'ripple-dot';
            dot.style.width = dot.style.height = d + 'px';
            dot.style.left = (e.clientX - r.left - d / 2) + 'px';
            dot.style.top = (e.clientY - r.top - d / 2) + 'px';
            btn.appendChild(dot);
            setTimeout(function () { dot.remove(); }, 550);
        });
    });
});
</script>
