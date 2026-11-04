<style>
    @keyframes stampIn { from { opacity: 0; scale: 2.4; } 60% { opacity: 1; scale: .9; } to { opacity: 1; scale: 1; } }
    @keyframes slideSwap { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

    .auth-page { background-color: var(--paper);
        background-image: radial-gradient(rgba(14,27,61,.075) 1px, transparent 1px); background-size: 22px 22px; }

    /* Papan laporan (panel kiri) */
    .board { background-color: var(--ink);
        background-image: linear-gradient(rgba(255,255,255,.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px);
        background-size: 34px 34px; }
    .board::after { content: ''; position: absolute; left: -60px; bottom: -60px; width: 260px; height: 260px; border-radius: 34px; rotate: -14deg;
        background: repeating-linear-gradient(135deg, var(--sun) 0 16px, transparent 16px 34px); opacity: .16; pointer-events: none; }
    .board .marker { background-image: linear-gradient(transparent 64%, rgba(255,201,51,.9) 64%); }

    /* Tiket yang "ditempel" di papan */
    .ticket { position: absolute; width: 17rem; background: #FFFDF6; color: var(--ink); border-radius: 14px; padding: 16px 16px 14px;
        rotate: var(--r, 0deg); box-shadow: 0 22px 34px -16px rgba(0,0,0,.6); transition: rotate .3s ease, scale .3s ease; }
    .ticket:hover { rotate: 0deg; scale: 1.05; z-index: 5; }
    .ticket .tape { position: absolute; top: -9px; left: 50%; width: 70px; height: 18px; translate: -50% 0; border-radius: 3px; opacity: .95;
        background: repeating-linear-gradient(135deg, #FFC933 0 8px, #14213D 8px 16px); }
    .ticket .track { display: flex; gap: 4px; }
    .ticket .track i { height: 5px; flex: 1; border-radius: 9px; background: #e6dfcf; transition: background-color .5s ease; }
    .ticket .track i.on { background: var(--ink); }
    .chip { display: inline-flex; align-items: center; gap: 6px; padding: 2px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; transition: background-color .3s ease, color .3s ease; }

    /* Kartu pelapor (halaman buat akun) */
    .idcard { position: relative; width: 20rem; max-width: 100%; background: #FFFDF6; color: var(--ink); border-radius: 18px; overflow: hidden;
        rotate: -3deg; box-shadow: 0 34px 50px -22px rgba(0,0,0,.7); }
    .idcard-head { display: flex; justify-content: space-between; gap: 8px; background: var(--sun); padding: 10px 16px; font-size: 10px; font-weight: 800; letter-spacing: .14em; }
    .idl { font-size: 9px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: #7b7566; }
    .idv { font-weight: 700; font-size: 15px; line-height: 1.2; word-break: break-word; transition: opacity .2s ease; }
    .barcode { height: 34px; background: repeating-linear-gradient(90deg, var(--ink) 0 2px, transparent 2px 4px, var(--ink) 4px 5px, transparent 5px 9px, var(--ink) 9px 12px, transparent 12px 14px); }
    .stamp { position: absolute; right: 14px; bottom: 54px; rotate: -9deg; border: 3px solid #059669; color: #059669; background: rgba(255,255,255,.75);
        border-radius: 8px; padding: 3px 10px; font-size: 12px; font-weight: 800; letter-spacing: .12em; animation: stampIn .45s ease both; }

    .swap { animation: slideSwap .3s ease both; }
</style>
