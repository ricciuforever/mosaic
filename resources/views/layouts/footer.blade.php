    </main>

    <!-- Floating AI Concierge Assistant Widget -->
    <button type="button" class="ai-fab" id="ai-fab-toggle" aria-expanded="false" aria-controls="ai-concierge-drawer">
        <span style="font-size:16px;">✦</span>
        <span>Concierge AI</span>
    </button>

    <aside class="ai-drawer" id="ai-concierge-drawer" aria-label="Assistente Privato AI Mosaic">
        <div style="padding:16px 18px;border-bottom:1px solid var(--border-subtle);display:flex;align-items:center;justify-content:space-between;background:rgba(200,155,82,0.08);">
            <div>
                <div class="serif" style="font-size:19px;font-weight:600;color:var(--gold-bright);">Concierge Mosaic AI</div>
                <div style="font-size:11px;color:var(--text-sand);">Orchestrazione Ville, Supercar, Yacht e Jet Privati</div>
            </div>
            <button type="button" id="ai-drawer-close" style="color:var(--text-sand);font-size:18px;padding:4px 8px;">✕</button>
        </div>
        <div class="ai-drawer-messages" id="ai-drawer-messages">
            <div class="ai-msg assistant">
                Benvenuto in <strong>MOSAIC Private Concierge</strong>. Descrivi il soggiorno che desideri (es. <em>"5 notti in Costa Smeralda per 6 ospiti con villa sul mare, Rolls-Royce, yacht Riva ed elicottero VIP"</em>) e comporrò subito il tuo dossier.
            </div>
        </div>
        <form id="ai-drawer-form" style="padding:12px 14px;border-top:1px solid var(--border-subtle);display:flex;gap:8px;background:var(--bg-card);">
            <input type="text" id="ai-drawer-input" class="form-input" placeholder="Descrivi il tuo viaggio ideale..." required>
            <button type="submit" class="btn btn-gold btn-sm">Componi</button>
        </form>
    </aside>

    <!-- Luxury Footer -->
    <footer style="background:#05070b;border-top:1px solid var(--border-subtle);padding:64px 0 40px;margin-top:80px;">
        <div class="container">
            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:40px;margin-bottom:48px;">
                <div>
                    <div class="brand-logo" style="margin-bottom:14px;">
                        <img src="/images/logo-mosaic.png" alt="MOSAIC">
                        <span>MOSAIC</span>
                    </div>
                    <p style="font-size:13px;color:var(--text-muted);line-height:1.65;">
                        Architettura di viaggi su misura in Italia. Un'unica regia riservata per combinare dimore storiche, supercar, superyacht e aviazione privata.
                    </p>
                </div>
                <div>
                    <h4 style="font-size:12px;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold-bright);margin-bottom:14px;">Destinazioni</h4>
                    <ul style="list-style:none;display:grid;gap:8px;font-size:13px;color:var(--text-sand);">
                        <li><a href="/compose?dest=costa-smeralda">Costa Smeralda · Sardegna</a></li>
                        <li><a href="/compose?dest=costiera-amalfitana">Costiera Amalfitana · Campania</a></li>
                        <li><a href="/compose?dest=lago-di-como">Lago di Como · Lombardia</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size:12px;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold-bright);margin-bottom:14px;">Collezioni</h4>
                    <ul style="list-style:none;display:grid;gap:8px;font-size:13px;color:var(--text-sand);">
                        <li><a href="/compose?cats=villa">Ville & Dimore Storiche</a></li>
                        <li><a href="/compose?cats=auto">Supercar & Chauffeur</a></li>
                        <li><a href="/compose?cats=yacht">Yacht & Motoscafi d'Epoca</a></li>
                        <li><a href="/compose?cats=jet">Jet Privati & Elicotteri VIP</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size:12px;text-transform:uppercase;letter-spacing:0.1em;color:var(--gold-bright);margin-bottom:14px;">Portali & Governance</h4>
                    <ul style="list-style:none;display:grid;gap:8px;font-size:13px;color:var(--text-sand);">
                        <li><a href="/area-clienti">Area Clienti VIP</a></li>
                        <li><a href="/portale-fornitore">Portale Fornitore & Partner</a></li>
                        <li><a href="/admin">Console Admin & Spatie RBAC</a></li>
                        <li><a href="/login">Switch Ruolo Demo</a></li>
                    </ul>
                </div>
            </div>
            <div style="border-top:1px solid var(--border-subtle);padding-top:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;font-size:12px;color:var(--text-muted);">
                <span>© <?= date('Y') ?> MOSAIC Digital Luxury Concierge S.r.l. · P.IVA 12948100964 · Cloud1 Plesk Enterprise Ready</span>
                <span>Desk Privato 24/7: +39 02 8900 4120 · concierge@mosaic.digital</span>
            </div>
        </div>
    </footer>

    <script>
        // Modern Web Guidance: <dialog closedby="any"> fallback for browsers without native closedBy support
        if (typeof HTMLDialogElement !== 'undefined' && !('closedBy' in HTMLDialogElement.prototype)) {
            document.querySelectorAll('dialog[closedby="any"]').forEach((dialog) => {
                dialog.addEventListener('click', (event) => {
                    if (event.target !== dialog) return;
                    const rect = dialog.getBoundingClientRect();
                    const isDialogContent = (
                        rect.top <= event.clientY &&
                        event.clientY <= rect.top + rect.height &&
                        rect.left <= event.clientX &&
                        event.clientX <= rect.left + rect.width
                    );
                    if (isDialogContent) return;
                    dialog.close();
                });
            });
        }

        // Update Dossier Badge Counter from localStorage
        function updateDossierCounter() {
            try {
                const raw = localStorage.getItem('mosaic_dossier_v2');
                if (!raw) return;
                const state = JSON.parse(raw);
                let count = 0;
                if (state && Array.isArray(state.legs)) {
                    state.legs.forEach((leg) => {
                        if (leg.selected && typeof leg.selected === 'object') {
                            count += Object.values(leg.selected).filter(Boolean).length;
                        }
                    });
                }
                const badge = document.getElementById('header-dossier-count');
                if (badge) badge.textContent = String(count);
            } catch (e) {}
        }
        updateDossierCounter();
        window.addEventListener('storage', updateDossierCounter);

        // Floating AI Concierge Widget Logic
        (function() {
            const fab = document.getElementById('ai-fab-toggle');
            const drawer = document.getElementById('ai-concierge-drawer');
            const closeBtn = document.getElementById('ai-drawer-close');
            const form = document.getElementById('ai-drawer-form');
            const input = document.getElementById('ai-drawer-input');
            const messages = document.getElementById('ai-drawer-messages');

            if (!fab || !drawer) return;

            fab.addEventListener('click', () => {
                const isOpen = drawer.classList.toggle('open');
                fab.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                if (isOpen && input) input.focus();
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    drawer.classList.remove('open');
                    fab.setAttribute('aria-expanded', 'false');
                });
            }

            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const prompt = input.value.trim();
                    if (!prompt) return;

                    const userBubble = document.createElement('div');
                    userBubble.className = 'ai-msg user';
                    userBubble.textContent = prompt;
                    messages.appendChild(userBubble);
                    input.value = '';
                    messages.scrollTop = messages.scrollHeight;

                    const loadingBubble = document.createElement('div');
                    loadingBubble.className = 'ai-msg assistant';
                    loadingBubble.textContent = 'Orchestrazione itinerario su misura in corso...';
                    messages.appendChild(loadingBubble);
                    messages.scrollTop = messages.scrollHeight;

                    try {
                        const res = await fetch('/api/ai/concierge', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ prompt })
                        });
                        const data = await res.json();
                        loadingBubble.innerHTML = '';

                        const replyP = document.createElement('div');
                        replyP.textContent = data.reply || 'Ecco la composizione consigliata:';
                        loadingBubble.appendChild(replyP);

                        if (Array.isArray(data.recommendations) && data.recommendations.length > 0) {
                            const list = document.createElement('div');
                            list.style.marginTop = '10px';
                            list.style.display = 'grid';
                            list.style.gap = '6px';
                            data.recommendations.forEach((rec) => {
                                const card = document.createElement('div');
                                card.style.padding = '8px 10px';
                                card.style.borderRadius = '8px';
                                card.style.background = 'rgba(200,155,82,0.1)';
                                card.style.border = '1px solid rgba(200,155,82,0.28)';
                                card.style.fontSize = '12px';
                                card.innerHTML = `<strong style="color:var(--gold-bright);text-transform:uppercase;">[${rec.category}]</strong> ${rec.title} · €${Number(rec.price).toLocaleString('it-IT')}/${rec.unit}`;
                                list.appendChild(card);
                            });
                            loadingBubble.appendChild(list);
                        }

                        if (data.compose_url) {
                            const link = document.createElement('a');
                            link.href = data.compose_url;
                            link.className = 'btn btn-gold btn-sm';
                            link.style.marginTop = '12px';
                            link.style.width = '100%';
                            link.textContent = 'Apri nella Composizione →';
                            loadingBubble.appendChild(link);
                        }
                    } catch (err) {
                        loadingBubble.textContent = 'Servizio momentaneamente occupato. Puoi procedere dal compositore manuale.';
                    }
                    messages.scrollTop = messages.scrollHeight;
                });
            }
        })();
    </script>
</body>
</html>
