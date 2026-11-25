<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f3efe6">
    <title>IT35 | Sensor Dashboard</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f3efe6;
            --paper: #fffdf8;
            --ink: #1c2420;
            --muted: #6b7570;
            --line: #d9d2c2;
            --line-strong: #1c2420;
            --green: #2e7d5b;
            --green-soft: #dcefe4;
            --blue: #2d5fb8;
            --blue-soft: #dde7f8;
            --amber: #c07a12;
            --amber-soft: #faecd0;
            --red: #c23b3b;
            --red-soft: #f8dcdc;
            --grey-soft: #ebe6d9;
        }

        * { box-sizing: border-box; }
        html { min-width: 320px; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "Segoe UI", system-ui, -apple-system, Roboto, sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }
        svg { display: block; }
        .wrap { width: min(100% - 48px, 1100px); margin: 0 auto; }

        /* ---------- Header ---------- */
        .topbar { border-bottom: 2px solid var(--line-strong); background: var(--paper); }
        .topbar-inner { min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-mark {
            width: 44px; height: 44px; border-radius: 10px;
            background: var(--ink); color: var(--bg);
            display: grid; place-items: center;
            font-weight: 800; font-size: 15px; letter-spacing: -.02em;
        }
        .brand-name { font-size: 20px; font-weight: 800; letter-spacing: -.02em; line-height: 1; }
        .brand-sub { font-size: 11px; color: var(--muted); letter-spacing: .12em; text-transform: uppercase; margin-top: 5px; }
        .status-pill {
            display: inline-flex; align-items: center; gap: 9px;
            padding: 9px 16px; border: 2px solid var(--line-strong); border-radius: 99px;
            background: var(--paper); font-size: 13px; font-weight: 700;
        }
        .dot { width: 10px; height: 10px; border-radius: 50%; flex: 0 0 auto; }
        .dot.online { background: var(--green); }
        .dot.offline { background: var(--red); }
        .dot.stale { background: var(--amber); }

        /* ---------- Main ---------- */
        main { padding: 40px 0 36px; }
        .page-title { margin: 0 0 6px; font-size: clamp(28px, 4vw, 40px); font-weight: 800; letter-spacing: -.03em; }
        .page-sub { margin: 0 0 30px; color: var(--muted); font-size: 14px; }

        .section-head { display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 14px; }
        .section-head h2 { margin: 0; font-size: 13px; text-transform: uppercase; letter-spacing: .14em; font-weight: 800; }

        .grid { display: none; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .card {
            background: var(--paper);
            border: 2px solid var(--line-strong);
            border-radius: 16px;
            padding: 22px 24px 24px;
            box-shadow: 5px 5px 0 var(--line-strong);
            min-width: 0;
        }
        .card-wide { grid-column: 1 / -1; }
        .card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 20px; }
        .card-title { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .icon-box { width: 40px; height: 40px; border-radius: 10px; display: grid; place-items: center; flex: 0 0 auto; }
        .icon-box.green { background: var(--green-soft); color: var(--green); }
        .icon-box.blue { background: var(--blue-soft); color: var(--blue); }
        .icon-box.amber, .icon-box.sun { background: var(--amber-soft); color: var(--amber); }
        .card-title strong { display: block; font-size: 16px; font-weight: 800; letter-spacing: -.01em; }
        .card-title span.sub { display: block; font-size: 12px; color: var(--muted); margin-top: 2px; }
        .chip { font-size: 11px; font-weight: 800; letter-spacing: .06em; padding: 5px 10px; border: 1.5px solid var(--line-strong); border-radius: 6px; white-space: nowrap; }

        .label { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; color: var(--muted); margin-bottom: 8px; }
        .big { font-size: clamp(34px, 5vw, 52px); font-weight: 800; letter-spacing: -.04em; line-height: 1.05; overflow-wrap: anywhere; }

        .climate { display: grid; grid-template-columns: 1fr 1fr; gap: 32px; }
        .meter { height: 14px; margin-top: 16px; background: var(--grey-soft); border: 1.5px solid var(--line-strong); border-radius: 99px; overflow: hidden; }
        .meter-fill { display: block; height: 100%; width: 0; background: var(--green); transition: width .5s ease; }
        .meter-fill.blue { background: var(--blue); }
        .meter-axis { display: flex; justify-content: space-between; font-size: 11px; color: var(--muted); margin-top: 7px; }

        .state-box { display: flex; align-items: center; gap: 18px; }
        .state-badge {
            width: 74px; height: 74px; border-radius: 18px; flex: 0 0 auto;
            display: grid; place-items: center;
            background: var(--grey-soft); color: var(--muted);
            border: 2px solid var(--line-strong);
            transition: background .25s, color .25s;
        }
        .state-badge.light { background: var(--amber-soft); color: var(--amber); }
        .state-badge.dark { background: #dcdfe8; color: #3b4263; }
        .state-badge.motion { background: var(--blue-soft); color: var(--blue); }
        .state-badge.still { background: var(--grey-soft); color: var(--muted); }
        .state-note { margin: 14px 0 0; padding-top: 14px; border-top: 1.5px dashed var(--line); font-size: 12px; color: var(--muted); }

        /* ---------- Warning / waiting ---------- */
        .warning {
            display: none; gap: 14px; align-items: flex-start;
            background: var(--red-soft); border: 2px solid var(--red); border-radius: 14px;
            padding: 18px 20px; margin-bottom: 24px; color: #6e1f1f;
        }
        .warning svg { color: var(--red); flex: 0 0 auto; margin-top: 2px; }
        .warning h2 { margin: 0 0 6px; font-size: 16px; font-weight: 800; }
        .warning p { margin: 0; font-size: 13px; line-height: 1.6; }

        .waiting {
            border: 2px dashed var(--line); border-radius: 16px; background: var(--paper);
            min-height: 220px; display: grid; place-content: center; justify-items: center;
            text-align: center; padding: 26px; color: var(--muted);
        }
        .waiting svg { color: var(--blue); margin-bottom: 14px; }
        .waiting strong { display: block; color: var(--ink); font-size: 16px; margin-bottom: 6px; }
        .waiting p { margin: 0; font-size: 13px; }

        /* ---------- Health ---------- */
        .health { margin-top: 32px; }
        .health-note { font-size: 12px; color: var(--muted); margin: 0 0 14px; }
        .health-row { display: none; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
        .health-row span {
            display: flex; align-items: center; gap: 10px;
            background: var(--paper); border: 2px solid var(--line-strong); border-radius: 12px;
            padding: 14px 16px; font-size: 13px; font-weight: 700;
        }
        .health-row span::before { content: ""; width: 10px; height: 10px; border-radius: 50%; background: currentColor; flex: 0 0 auto; }
        .health-row span::after { content: attr(data-state); margin-left: auto; font-size: 11px; font-weight: 600; color: var(--muted); }
        .health-row span.ok::before { color: var(--green); }
        .health-row span.fail::before { color: var(--red); }
        .health-row span.fail::after { color: var(--red); }
        .health-row span.unknown::before { color: var(--amber); }
        .health-row span.stale::before { color: #a8a392; }

        .footer { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 34px; padding-top: 18px; border-top: 2px solid var(--line-strong); font-size: 12px; color: var(--muted); }
        .footer strong { color: var(--ink); }

        @media (max-width: 760px) {
            .wrap { width: min(100% - 28px, 1100px); }
            .grid { grid-template-columns: 1fr; }
            .climate { grid-template-columns: 1fr; gap: 26px; }
            .health-row { grid-template-columns: 1fr; }
            .status-pill { font-size: 12px; padding: 8px 12px; max-width: 190px; }
            .card { padding: 18px 18px 20px; box-shadow: 4px 4px 0 var(--line-strong); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="wrap topbar-inner">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">35</div>
                <div>
                    <div class="brand-name">IT35</div>
                    <div class="brand-sub">Sensor Dashboard</div>
                </div>
            </div>
            <div class="status-pill" role="status" aria-live="polite">
                <span class="dot offline" id="status-dot" aria-hidden="true"></span>
                <span id="status-text">Loading...</span>
            </div>
        </div>
    </header>

    <main class="wrap">
        <h1 class="page-title">Sensor overview</h1>
        <p class="page-sub">Live readings from the ESP32: temperature, humidity, light and motion.</p>

        <div id="warning-banner" class="warning" role="alert">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.3 3.6 2.2 17.5A2 2 0 0 0 4 20.5h16a2 2 0 0 0 1.8-3L13.7 3.6a2 2 0 0 0-3.4 0Z"/><path d="M12 9v4M12 17h.01"/></svg>
            <div>
                <h2>System halted: sensor check failed</h2>
                <p>Missing: <strong id="missing-list"></strong><br>A failed check may mean a missing sensor, a wiring issue, or an invalid reading.</p>
            </div>
        </div>

        <section aria-label="Current sensor readings">
            <div class="section-head"><h2>Live readings</h2></div>

            <div id="cards-wrap" class="grid">
                <article class="card card-wide" aria-label="DHT11 temperature and humidity sensor">
                    <div class="card-head">
                        <div class="card-title">
                            <span class="icon-box green" aria-hidden="true">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 14.8V5a3 3 0 0 0-6 0v9.8a5 5 0 1 0 6 0Z"/><path d="M11 11v7"/></svg>
                            </span>
                            <div><strong>Climate</strong><span class="sub">Temperature &amp; humidity</span></div>
                        </div>
                        <span class="chip">DHT11</span>
                    </div>
                    <div class="climate">
                        <div>
                            <span class="label">Temperature</span>
                            <div class="big" id="v-temp">--</div>
                            <div class="meter" id="temp-track" role="progressbar" aria-label="Temperature" aria-valuemin="0" aria-valuemax="50" aria-valuenow="0"><span class="meter-fill" id="temp-bar"></span></div>
                            <div class="meter-axis"><span>0°C</span><span>50°C</span></div>
                        </div>
                        <div>
                            <span class="label">Relative humidity</span>
                            <div class="big" id="v-hum">--</div>
                            <div class="meter" id="humidity-track" role="progressbar" aria-label="Humidity" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><span class="meter-fill blue" id="humidity-bar"></span></div>
                            <div class="meter-axis"><span>0%</span><span>100%</span></div>
                        </div>
                    </div>
                </article>

                <article class="card" aria-label="LM393 light sensor">
                    <div class="card-head">
                        <div class="card-title">
                            <span class="icon-box sun" aria-hidden="true">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"/></svg>
                            </span>
                            <div><strong>Light detection</strong><span class="sub">Ambient light state</span></div>
                        </div>
                    </div>
                    <div class="state-box">
                        <div class="state-badge" id="light-badge" aria-hidden="true">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.42 1.42M17.65 17.65l1.42 1.42M2 12h2M20 12h2M4.93 19.07l1.42-1.42M17.65 6.35l1.42-1.42"/></svg>
                        </div>
                        <div>
                            <span class="label">Current state</span>
                            <div class="big" id="v-light" style="font-size:clamp(28px,4vw,40px)">--</div>
                        </div>
                    </div>
                    <p class="state-note">Digital sensor output</p>
                </article>

                <article class="card" aria-label="PIR motion sensor">
                    <div class="card-head">
                        <div class="card-title">
                            <span class="icon-box blue" aria-hidden="true">
                                <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"/><path d="M7.7 7.7a6 6 0 0 0 0 8.6M16.3 7.7a6 6 0 0 1 0 8.6M4.9 4.9a10 10 0 0 0 0 14.2M19.1 4.9a10 10 0 0 1 0 14.2"/></svg>
                            </span>
                            <div><strong>Motion</strong><span class="sub">PIR sensor</span></div>
                        </div>
                    </div>
                    <div class="state-box">
                        <div class="state-badge" id="motion-badge" aria-hidden="true">
                            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="2"/><path d="M7.7 7.7a6 6 0 0 0 0 8.6M16.3 7.7a6 6 0 0 1 0 8.6"/></svg>
                        </div>
                        <div>
                            <span class="label">Current state</span>
                            <div class="big" id="v-motion" style="font-size:clamp(28px,4vw,40px)">--</div>
                        </div>
                    </div>
                    <p class="state-note">Digital sensor output</p>
                </article>
            </div>

            <div id="waiting-msg" class="waiting" role="status">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 9a14 14 0 0 1 20 0M5 12a10 10 0 0 1 14 0M8.5 15.5a5 5 0 0 1 7 0"/><circle cx="12" cy="19" r="1" fill="currentColor" stroke="none"/></svg>
                <strong>Waiting for ESP32 data</strong>
                <p>Sensor readings will appear here once your device sends data.</p>
            </div>
        </section>

        <section class="health" aria-label="Sensor connection health">
            <div class="section-head"><h2>Module health</h2></div>
            <p class="health-note">ESP32-reported sensor status. Digital sensors (LM393, PIR) cannot confirm physical presence.</p>
            <div class="health-row" id="sensor-status-row">
                <span id="s-dht"></span>
                <span id="s-light"></span>
                <span id="s-pir"></span>
            </div>
            <p id="health-waiting" class="health-note" style="margin:0;">Awaiting sensor diagnostics...</p>
        </section>

        <footer class="footer">
            <div>IT35 <strong>Sensor Monitor</strong></div>
            <div>Polling every <strong>3 seconds</strong> &middot; Last refreshed: <strong id="refresh-time">Not yet</strong></div>
        </footer>
    </main>

    <script>
        const POLL_INTERVAL = 3000;
        const ACTIVE_WINDOW_SECONDS = 30;
        const OFFLINE_WINDOW_SECONDS = 90;
        const READING_STORAGE_KEY = 'it35-esp32-last-readings-v1';
        const READING_FIELDS = {
            temperature: ['v-temp', ' °C'],
            humidity: ['v-hum', ' %'],
            light: ['v-light', ''],
            motion: ['v-motion', '']
        };

        let lastReadings = {};
        let lastReadingModified = 0;
        let polling = false;

        // Keep the previous readings through a refresh.
        try {
            const saved = JSON.parse(localStorage.getItem(READING_STORAGE_KEY) || 'null');
            if (saved && saved.readings && typeof saved.readings === 'object') {
                for (const key of Object.keys(READING_FIELDS)) {
                    if (saved.readings[key] !== null && saved.readings[key] !== undefined) {
                        lastReadings[key] = saved.readings[key];
                    }
                }
                lastReadingModified = Number(saved.lastModified) || 0;
            }
        } catch (err) {}

        function hasReadings() { return Object.keys(lastReadings).length > 0; }

        function setStatus(dotClass, text) {
            document.getElementById('status-dot').className = 'dot ' + dotClass;
            document.getElementById('status-text').textContent = text;
        }

        function setSensorBadge(id, label, state) {
            const el = document.getElementById(id);
            const classes = { Ready: 'ok', Offline: 'fail', Unverified: 'unknown', Stale: 'stale' };
            el.textContent = label;
            el.className = classes[state] || 'unknown';
            el.setAttribute('data-state', state);
            el.setAttribute('aria-label', label + ': ' + state);
        }

        function sensorState(ok, deviceFresh) {
            if (!deviceFresh) return 'Stale';
            if (ok === true) return 'Ready';
            if (ok === false) return 'Offline';
            return 'Unverified';
        }

        function showSensorStates(data, fresh) {
            const sensors = [
                ['s-dht', 'DHT11', data.dht_ok],
                ['s-light', 'LM393', data.light_ok],
                ['s-pir', 'PIR', data.pir_ok]
            ];
            for (const [id, label, ok] of sensors) setSensorBadge(id, label, sensorState(ok, fresh));
            document.getElementById('sensor-status-row').style.display = 'grid';
            document.getElementById('health-waiting').style.display = 'none';
        }

        function setMeter(barId, trackId, raw, max, unitText) {
            const n = Number(raw);
            const valid = raw !== null && raw !== undefined && Number.isFinite(n);
            const v = valid ? Math.max(0, Math.min(max, n)) : 0;
            document.getElementById(barId).style.width = (v / max * 100) + '%';
            const track = document.getElementById(trackId);
            track.setAttribute('aria-valuenow', String(v));
            track.setAttribute('aria-valuetext', valid ? raw + ' ' + unitText : 'Unavailable');
        }

        function updateVisualization(data) {
            setMeter('temp-bar', 'temp-track', data.temperature, 50, 'degrees Celsius');
            setMeter('humidity-bar', 'humidity-track', data.humidity, 100, 'percent');
            document.getElementById('light-badge').className =
                'state-badge ' + (data.light === 'Light' ? 'light' : data.light === 'Dark' ? 'dark' : '');
            document.getElementById('motion-badge').className =
                'state-badge ' + (data.motion === 'Detected' ? 'motion' : data.motion === 'None' ? 'still' : '');
        }

        function renderReadings() {
            if (!hasReadings()) return;
            for (const [key, [id, unit]] of Object.entries(READING_FIELDS)) {
                const value = lastReadings[key];
                document.getElementById(id).textContent = value === undefined ? 'N/A' : String(value) + unit;
            }
            updateVisualization(lastReadings);
        }

        function showReadingArea() {
            const available = hasReadings();
            document.getElementById('cards-wrap').style.display = available ? 'grid' : 'none';
            document.getElementById('waiting-msg').style.display = available ? 'none' : 'grid';
        }

        function storeNewReadings(data) {
            const modified = Number(data._last_modified) || Math.floor(Date.now() / 1000);
            if (modified < lastReadingModified) return;
            let changed = false;
            for (const key of Object.keys(READING_FIELDS)) {
                if (data[key] !== null && data[key] !== undefined) {
                    lastReadings[key] = data[key];
                    changed = true;
                }
            }
            if (!changed) return; // Warning-only POSTs have no reading fields.
            lastReadingModified = modified;
            try {
                localStorage.setItem(READING_STORAGE_KEY, JSON.stringify({
                    readings: lastReadings, lastModified: lastReadingModified
                }));
            } catch (err) {}
            renderReadings();
        }

        function showNoReadingMessage(title, detail) {
            document.querySelector('#waiting-msg strong').textContent = title;
            document.querySelector('#waiting-msg p').textContent = detail;
            showReadingArea();
        }

        async function poll() {
            if (polling) return;
            polling = true;
            try {
                const res = await fetch('ESP32_data.php', { cache: 'no-store' });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const data = await res.json();
                document.getElementById('refresh-time').textContent = new Date().toLocaleString();

                if (data.error) {
                    setStatus('offline', data.error === 'No data yet' ? 'No Data Received' : data.error);
                    document.getElementById('warning-banner').style.display = 'none';
                    if (hasReadings()) showSensorStates({}, false);
                    else {
                        document.getElementById('sensor-status-row').style.display = 'none';
                        document.getElementById('health-waiting').style.display = 'block';
                    }
                    showNoReadingMessage('Waiting for ESP32 data', 'Sensor readings will appear here once your device sends data.');
                    return;
                }

                const modified = Number(data._last_modified) || 0;
                const secondsAgo = modified > 0 ? Math.max(0, Math.floor(Date.now() / 1000) - modified) : Infinity;
                const fresh = secondsAgo <= ACTIVE_WINDOW_SECONDS;
                if (fresh) setStatus('online', 'ESP32 Connected');
                else if (secondsAgo <= OFFLINE_WINDOW_SECONDS) setStatus('stale', 'ESP32 Possibly Disconnected (' + secondsAgo + 's ago)');
                else setStatus('offline', 'ESP32 Offline (' + secondsAgo + 's ago)');

                showSensorStates(data, fresh);
                storeNewReadings(data);

                const failedCheck = fresh && data.system_ok === false;
                document.getElementById('warning-banner').style.display = failedCheck ? 'flex' : 'none';
                if (failedCheck) document.getElementById('missing-list').textContent = data.missing || 'Unknown sensor';

                if (fresh) showNoReadingMessage('Waiting for valid sensor readings', 'The ESP32 is reporting data, but no complete readings are available yet.');
                else showNoReadingMessage('ESP32 telemetry is stale', 'No recent device update. Waiting for sensor readings.');
            } catch (err) {
                setStatus('offline', 'Connection error');
                document.getElementById('warning-banner').style.display = 'none';
                if (hasReadings()) showSensorStates({}, false);
                showNoReadingMessage('Waiting for ESP32 data', 'Unable to retrieve new readings from the server.');
            } finally {
                polling = false;
            }
        }

        renderReadings();
        showReadingArea();
        poll();
        setInterval(poll, POLL_INTERVAL);
    </script>
</body>
</html>