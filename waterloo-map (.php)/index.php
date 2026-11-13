<?php
$pageTitle = "Waterloo 1815: Imperial Tactical Command Engine";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold: #d4af37;
            --gold-light: #f3e5ab;
            --gold-dark: #aa7c11;
            --french-blue: #1d4ed8;
            --allied-red: #dc2626;
            --prussian-black: #1f2937;
            --bg-dark: #0a0c10;
            --panel-bg: rgba(18, 22, 31, 0.95);
            --border-brass: rgba(212, 175, 55, 0.4);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: var(--bg-dark);
            color: #e2e8f0;
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* CINEMATIC INTRO */
        #cinematic-overlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: #000; z-index: 1000; display: flex; flex-direction: column;
            justify-content: center; align-items: center; padding: 40px;
            transition: opacity 1.2s ease;
        }
        .cinematic-card {
            max-width: 850px; text-align: center;
            background: radial-gradient(circle, rgba(20, 24, 35, 0.95) 0%, rgba(10, 12, 16, 0.99) 100%);
            border: 2px solid var(--gold); box-shadow: 0 0 50px rgba(212, 175, 55, 0.25);
            padding: 45px; border-radius: 8px;
        }
        .cinematic-title { font-family: 'Cinzel', serif; font-size: 2.8rem; color: var(--gold); margin-bottom: 20px; text-transform: uppercase; letter-spacing: 3px; }
        .cinematic-subtitle { font-family: 'Cinzel', serif; font-size: 1.2rem; color: #94a3b8; margin-bottom: 30px; letter-spacing: 2px; }
        .cinematic-body { font-size: 1.05rem; line-height: 1.8; color: #cbd5e1; margin-bottom: 35px; min-height: 120px; display: flex; align-items: center; justify-content: center; }
        .cinematic-nav { display: flex; justify-content: space-between; align-items: center; }

        .btn-gold {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            color: #000; font-family: 'Cinzel', serif; font-weight: 700; font-size: 0.95rem;
            padding: 12px 28px; border: none; border-radius: 4px; cursor: pointer;
            letter-spacing: 1.5px; text-transform: uppercase; transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        }
        .btn-gold:hover { background: linear-gradient(135deg, var(--gold-light) 0%, var(--gold) 100%); }

        /* HEADER */
        header {
            background: linear-gradient(to right, #0a0c10, #141824, #0a0c10);
            padding: 12px 25px; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid var(--border-brass); z-index: 10;
        }
        header h1 { font-family: 'Cinzel', serif; font-size: 1.3rem; color: var(--gold); letter-spacing: 2px; text-transform: uppercase; }
        .header-actions { display: flex; align-items: center; gap: 12px; }
        .badge {
            background: rgba(212, 175, 55, 0.1); border: 1px solid var(--border-brass);
            color: var(--gold-light); padding: 5px 12px; border-radius: 4px; font-size: 0.75rem; font-family: 'Cinzel', serif;
            cursor: pointer; transition: background 0.2s ease, border-color 0.2s ease;
        }
        .badge:hover, .badge:focus-visible { background: rgba(212, 175, 55, 0.22); border-color: var(--gold); outline: none; }
        .context-modal {
            position: fixed; inset: 0; z-index: 2500; display: none;
            align-items: center; justify-content: center; padding: 24px;
            background: rgba(0, 0, 0, 0.78);
        }
        .context-modal.open { display: flex; }
        .context-modal-card {
            width: min(560px, 100%); padding: 28px; border: 1px solid var(--gold);
            border-left: 5px solid var(--gold); border-radius: 7px;
            background: radial-gradient(circle at top, #222a38, #0d1119 75%);
            box-shadow: 0 18px 60px rgba(0,0,0,.65);
        }
        .context-modal-card h2 { color: var(--gold); font: 700 1.15rem 'Cinzel', serif; margin-bottom: 14px; }
        .context-modal-card p { color: #d5dce7; font-size: .92rem; line-height: 1.75; margin-bottom: 20px; }
        .context-modal-card button { float: right; }
        .context-modal-card::after { content: ''; display: block; clear: both; }
        .lang-btn { background: var(--gold); color: #000; border: none; padding: 6px 14px; font-family: 'Cinzel', serif; font-weight: bold; font-size: 0.8rem; border-radius: 4px; cursor: pointer; }

        /* MAIN WORKSPACE */
        main { display: flex; flex: 1; position: relative; overflow: hidden; }

        #map-container {
            flex: 2.8; position: relative; background-color: #0d0f14;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
            border-right: 2px solid var(--border-brass);
        }

        #battle-canvas {
            width: 96%; height: 96%; position: relative;
            border: 2px solid var(--gold); border-radius: 6px;
            box-shadow: 0 0 40px rgba(0,0,0,0.9); overflow: hidden;
            background: #172014;
        }

        /* DIRECT MAP IMAGE FILL */
        .map-img-element {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            opacity: 0.78;
            filter: sepia(12%) contrast(0.92) brightness(0.88);
            z-index: 1;
            pointer-events: none;
        }

        /* A subtle dark wash keeps the existing tactical markers readable. */
        #battle-canvas::after {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(10, 12, 16, 0.16);
            z-index: 2;
            pointer-events: none;
        }

        #map-svg-layer { width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 3; pointer-events: none; }

        .stronghold-pin {
            position: absolute; z-index: 5; background: rgba(10, 12, 16, 0.9);
            border: 1px solid var(--gold); color: var(--gold-light);
            font-family: 'Cinzel', serif; font-size: 0.7rem; padding: 3px 8px; border-radius: 3px;
            pointer-events: none; text-transform: uppercase;
        }

        .unit-pin {
            position: absolute; z-index: 10; width: 28px; height: 28px; border-radius: 50%;
            border: 2px solid #fff; box-shadow: 0 0 15px rgba(0,0,0,0.9);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cinzel', serif; font-weight: 700; font-size: 0.75rem; color: #fff;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer;
        }
        .unit-pin.french { background: var(--french-blue); box-shadow: 0 0 15px rgba(29, 78, 216, 0.9); }
        .unit-pin.coalition { background: var(--allied-red); box-shadow: 0 0 15px rgba(220, 38, 38, 0.9); }
        .unit-pin.prussian { background: var(--prussian-black); border-color: #94a3b8; box-shadow: 0 0 15px rgba(148, 163, 184, 0.8); }

        /* SIDEBAR */
        #info-panel {
            flex: 1.2; min-width: 380px; background-color: var(--panel-bg);
            padding: 20px; display: flex; flex-direction: column; gap: 16px; overflow-y: auto; z-index: 20;
        }
        .card {
            background: rgba(26, 32, 44, 0.9); border: 1px solid var(--border-brass);
            border-left: 4px solid var(--gold); padding: 16px; border-radius: 6px;
        }
        .card h2 { font-family: 'Cinzel', serif; font-size: 0.95rem; color: var(--gold); margin-bottom: 10px; text-transform: uppercase; }
        .card p { font-size: 0.85rem; line-height: 1.6; color: #cbd5e1; }

        .stat-group { margin-top: 12px; }
        .stat-header { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; }
        .bar-bg { width: 100%; height: 8px; background: #0a0c10; border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); }
        .bar-fill { height: 100%; transition: width 0.6s ease; }
        #bar-french { background: var(--french-blue); }
        #bar-coalition { background: var(--allied-red); }
        #bar-prussian { background: #94a3b8; }

        .quiz-box {
            background: rgba(26, 32, 44, 0.9); border: 1px solid rgba(59, 130, 246, 0.4);
            border-left: 4px solid #3b82f6; padding: 16px; border-radius: 6px;
        }
        .quiz-box h3 { font-family: 'Cinzel', serif; font-size: 0.9rem; color: #60a5fa; margin-bottom: 8px; text-transform: uppercase; }
        .quiz-options { display: flex; flex-direction: column; gap: 8px; margin-top: 10px; }
        .quiz-btn {
            background: #0a0c10; border: 1px solid var(--border-brass); color: #e2e8f0;
            padding: 8px 12px; border-radius: 4px; text-align: left; font-size: 0.78rem; cursor: pointer;
        }
        .quiz-btn:hover { background: rgba(212, 175, 55, 0.15); border-color: var(--gold); }
        .quiz-feedback { font-size: 0.8rem; margin-top: 8px; font-style: italic; }

        /* TIMELINE BAR */
        #timeline-bar {
            background: linear-gradient(to right, #0a0c10, #141824, #0a0c10);
            padding: 16px 30px; border-top: 2px solid var(--border-brass);
            display: flex; align-items: center; gap: 25px; z-index: 30;
        }
        .time-display { font-family: 'Cinzel', serif; font-size: 1.4rem; font-weight: 700; color: var(--gold); min-width: 110px; }
        .slider-wrapper { flex: 1; display: flex; flex-direction: column; gap: 8px; }
        input[type=range] { width: 100%; accent-color: var(--gold); cursor: pointer; }
        .slider-ticks { display: flex; justify-content: space-between; font-size: 0.7rem; color: #94a3b8; font-family: 'Cinzel', serif; }

        /* EPILOGUE */
        #epilogue-modal {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.92); z-index: 2000; display: none;
            justify-content: center; align-items: center; padding: 30px;
        }
        .epilogue-card {
            max-width: 700px; background: radial-gradient(circle, #1a202c 0%, #0a0c10 100%);
            border: 2px solid var(--gold); padding: 40px; border-radius: 8px; text-align: center;
        }
    </style>
</head>
<body>

    <!-- CINEMATIC INTRO -->
    <div id="cinematic-overlay">
        <div class="cinematic-card">
            <h1 class="cinematic-title" id="intro-title">The Corsican Eagle</h1>
            <h2 class="cinematic-subtitle" id="intro-subtitle">Part I: Origins & Destiny</h2>
            <div class="cinematic-body" id="intro-body">
                Born in Ajaccio, Corsica in 1769, Napoleon Bonaparte rose from an obscure artillery officer through the crucible of the French Revolution. His genius at Toulon and Italian campaigns reshaped European warfare forever.
            </div>
            <div class="cinematic-nav">
                <span style="font-size: 0.8rem; color: #64748b; font-family: 'Cinzel', serif;" id="intro-step-count">Step 1 of 4</span>
                <div>
                    <button class="btn-gold" id="btn-next-intro" onclick="nextIntroStep()">Continue Journey →</button>
                    <button class="btn-gold" style="background: transparent; border: 1px solid var(--gold); color: var(--gold); margin-left: 10px;" id="btn-skip-intro" onclick="skipIntro()">Skip to Battle Map</button>
                </div>
            </div>
        </div>
    </div>

    <!-- HEADER -->
    <header>
        <h1 id="hdr-title">Bataille de Waterloo — 18 Juin 1815</h1>
        <div class="header-actions">
            <button type="button" class="badge" id="badge-weather" onclick="showContext('weather')" aria-haspopup="dialog">Weather: Torrential Mud</button>
            <button type="button" class="badge" id="badge-grouchy" onclick="showContext('grouchy')" aria-haspopup="dialog">Grouchy Detached (-33k)</button>
            <button class="lang-btn" onclick="toggleLanguage()" id="lang-toggle-btn">FR 🇫🇷</button>
        </div>
    </header>

    <!-- MAIN WORKSPACE -->
    <main>
        <div id="map-container">
            <div id="battle-canvas">
                <!-- DIRECT HTML MAP IMAGE -->
                <img
                    src="https://thumb.wikimedia.org/wikipedia/commons/thumb/7/75/PLAN_DU_CHAMP_DE_BATAILLE_DE_WATERLOO_%28W.B._CRAAN%2C_1816%29.jpg/1280px-PLAN_DU_CHAMP_DE_BATAILLE_DE_WATERLOO_%28W.B._CRAAN%2C_1816%29.jpg"
                    class="map-img-element"
                    alt="Plan du champ de bataille de Waterloo, Willem Benjamin Craan, 1816"
                    loading="eager"
                >

                <svg id="map-svg-layer" viewBox="0 0 1000 700" preserveAspectRatio="none">
                    <g id="svg-tactical-arrows"></g>
                </svg>

                <div class="stronghold-pin" style="top: 56%; left: 22%; z-index: 5;">Château Hougoumont</div>
                <div class="stronghold-pin" style="top: 42%; left: 47%; z-index: 5;">La Haye Sainte</div>
                <div class="stronghold-pin" style="top: 66%; left: 74%; z-index: 5;">Plancenoit Village</div>
                <div class="stronghold-pin" style="top: 25%; left: 44%; z-index: 5;">Mont-Saint-Jean Ridge</div>

                <div id="french-pin" class="unit-pin french" style="top: 65%; left: 47%;">F</div>
                <div id="coalition-pin" class="unit-pin coalition" style="top: 32%; left: 47%;">A</div>
                <div id="prussian-pin" class="unit-pin prussian" style="top: 55%; left: 88%; opacity: 0.15;">P</div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <aside id="info-panel">
            <div class="card">
                <h2 id="lbl-log-title">Operational Log & Context</h2>
                <p id="narrative-text">Drag the Imperial Timeline below to inspect the 8 historical tactical phases of June 18, 1815.</p>
            </div>

            <div class="card" style="border-left-color: #3b82f6;">
                <h2 id="lbl-stat-title">Force Strength & Attrition</h2>
                <div class="stat-group">
                    <div class="stat-header"><span id="lbl-french-army">Armée Impériale (Napoleon)</span><strong id="val-french">72,000</strong></div>
                    <div class="bar-bg"><div id="bar-french" class="bar-fill" style="width: 100%;"></div></div>
                </div>
                <div class="stat-group">
                    <div class="stat-header"><span id="lbl-allied-army">Anglo-Allied Forces (Wellington)</span><strong id="val-coalition">68,000</strong></div>
                    <div class="bar-bg"><div id="bar-coalition" class="bar-fill" style="width: 95%;"></div></div>
                </div>
                <div class="stat-group">
                    <div class="stat-header"><span id="lbl-prussian-army">Prussian Army (Blücher)</span><strong id="val-prussian">0 (En Route)</strong></div>
                    <div class="bar-bg"><div id="bar-prussian" class="bar-fill" style="width: 5%;"></div></div>
                </div>
            </div>

            <div class="quiz-box">
                <h3 id="lbl-quiz-title">Tactical Knowledge Check</h3>
                <p id="quiz-question">Why did Napoleon delay his opening artillery bombardment until 11:30 AM?</p>
                <div class="quiz-options">
                    <button class="quiz-btn" id="q-opt-1" onclick="checkAnswer(1)">A) Awaiting Imperial Guard muster.</button>
                    <button class="quiz-btn" id="q-opt-2" onclick="checkAnswer(2)">B) Heavy rain made mud too thick for artillery movement and cannonball ricochets.</button>
                    <button class="quiz-btn" id="q-opt-3" onclick="checkAnswer(3)">C) Waiting for Marshal Grouchy's wing.</button>
                </div>
                <div id="quiz-feedback" class="quiz-feedback"></div>
            </div>
        </aside>
    </main>

    <!-- 8 TIMINGS CONTROLLER BAR -->
    <footer id="timeline-bar">
        <div class="time-display" id="clock-display">11:30 AM</div>
        <div class="slider-wrapper">
            <input type="range" id="timeline-slider" min="1" max="8" step="1" value="1">
            <div class="slider-ticks">
                <span id="t-1">11:30 (Opening)</span>
                <span id="t-2">13:00 (Battery)</span>
                <span id="t-3">14:00 (d'Erlon)</span>
                <span id="t-4">15:30 (Hougoumont)</span>
                <span id="t-5">16:30 (Cavalry)</span>
                <span id="t-6">18:00 (Plancenoit)</span>
                <span id="t-7">19:30 (Guard)</span>
                <span id="t-8">21:00 (Rout)</span>
            </div>
        </div>
    </footer>

    <!-- EPILOGUE -->
    <div id="epilogue-modal">
        <div class="epilogue-card">
            <h1 style="font-family: 'Cinzel', serif; color: var(--gold); margin-bottom: 15px;" id="epi-title">The Sunset of the Eagle</h1>
            <p style="font-size: 0.95rem; line-height: 1.8; color: #cbd5e1; margin-bottom: 25px;" id="epi-body">
                With the collapse of the Imperial Guard at 19:30 and the arrival of Prussians on the flank, Napoleon's dream was shattered. Exiled to Saint Helena, he passed away in 1821.
            </p>
            <button class="btn-gold" id="btn-close-epilogue" onclick="closeEpilogue()">Return to Tactical Command</button>
        </div>
    </div>

    <!-- HISTORICAL CONTEXT POPUP -->
    <div class="context-modal" id="context-modal" role="dialog" aria-modal="true" aria-labelledby="context-modal-title" onclick="if(event.target === this) closeContext()">
        <div class="context-modal-card">
            <h2 id="context-modal-title">Historical Context</h2>
            <p id="context-modal-body"></p>
            <button type="button" class="btn-gold" onclick="closeContext()" id="context-modal-close">Close</button>
        </div>
    </div>

    <!-- SCRIPT ENGINE -->
    <script>
        let currentLang = 'EN';

        const contextContent = {
            weather: {
                EN: { title: 'The Weather: Torrential Mud', body: 'Heavy rain fell over the Waterloo battlefield on the night of 17–18 June 1815. The saturated ground made movement difficult for infantry, cavalry and artillery. Napoleon delayed the opening attack until the ground had partly dried. The exact importance of the delay remains debated by historians.' },
                FR: { title: 'La météo : une boue profonde', body: 'De fortes pluies tombèrent sur le champ de bataille de Waterloo dans la nuit du 17 au 18 juin 1815. Le terrain détrempé compliquait les mouvements de l’infanterie, de la cavalerie et de l’artillerie. Napoléon retarda l’attaque initiale jusqu’à ce que le terrain ait partiellement séché. L’importance exacte de ce délai reste discutée par les historiens.' }
            },
            grouchy: {
                EN: { title: 'Marshal Grouchy: Detached Wing', body: 'Marshal Emmanuel de Grouchy commanded a substantial French force pursuing the Prussian army after the fighting at Ligny. He was not present with Napoleon at Waterloo. Grouchy continued operating east of the main battlefield, while Prussian forces under Blücher moved to support Wellington. The label is a simplified summary: Grouchy’s command was not simply a single 33,000-man unit detached at Waterloo.' },
                FR: { title: 'Le maréchal Grouchy : aile détachée', body: 'Le maréchal Emmanuel de Grouchy commandait une importante force française chargée de poursuivre l’armée prussienne après les combats de Ligny. Il n’était pas présent aux côtés de Napoléon à Waterloo. Grouchy poursuivit ses opérations à l’est du champ de bataille principal, tandis que les forces prussiennes de Blücher marchaient au secours de Wellington. Cette étiquette est un résumé simplifié : son commandement ne se réduisait pas à une seule unité de 33 000 hommes détachée à Waterloo.' }
            }
        };

        function showContext(topic) {
            const entry = contextContent[topic] && contextContent[topic][currentLang];
            if (!entry) return;
            document.getElementById('context-modal-title').textContent = entry.title;
            document.getElementById('context-modal-body').textContent = entry.body;
            document.getElementById('context-modal-close').textContent = currentLang === 'FR' ? 'Fermer' : 'Close';
            document.getElementById('context-modal').classList.add('open');
        }

        function closeContext() {
            document.getElementById('context-modal').classList.remove('open');
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeContext();
        });

        const i18n = {
            EN: {
                hdrTitle: "Bataille de Waterloo — 18 Juin 1815",
                badgeWeather: "Weather: Torrential Mud",
                badgeGrouchy: "Grouchy Detached (-33k)",
                langBtn: "FR 🇫🇷",
                logTitle: "Operational Log & Context",
                statTitle: "Force Strength & Attrition",
                frenchArmy: "Armée Impériale (Napoleon)",
                alliedArmy: "Anglo-Allied Forces (Wellington)",
                prussianArmy: "Prussian Army (Blücher)",
                quizTitle: "Tactical Knowledge Check",
                quizQ: "Why did Napoleon delay his opening artillery bombardment until 11:30 AM?",
                opt1: "A) Awaiting Imperial Guard muster.",
                opt2: "B) Heavy rain made mud too thick for artillery movement and cannonball ricochets.",
                opt3: "C) Waiting for Marshal Grouchy's wing.",
                epiTitle: "The Sunset of the Eagle",
                epiBody: "With the collapse of the Imperial Guard at 19:30 and the arrival of Prussians on the flank, Napoleon's dream was shattered. Exiled to Saint Helena, he passed away in 1821.",
                btnCloseEpi: "Return to Tactical Command",
                correctMsg: "Correct! Saturated soil prevented cannonballs from skipping into enemy lines.",
                incorrectMsg: "Incorrect. Consider the overnight weather on June 17."
            },
            FR: {
                hdrTitle: "Bataille de Waterloo — 18 Juin 1815",
                badgeWeather: "Météo : Boue Torrentielle",
                badgeGrouchy: "Grouchy Détaché (-33k)",
                langBtn: "EN 🇬🇧",
                logTitle: "Journal Opérationnel & Contexte",
                statTitle: "Effectifs & Attrition des Forces",
                frenchArmy: "Armée Impériale (Napoléon)",
                alliedArmy: "Forces Anglo-Alliées (Wellington)",
                prussianArmy: "Armée Prussienne (Blücher)",
                quizTitle: "Contrôle des Connaissances Tactiques",
                quizQ: "Pourquoi Napoléon a-t-il retardé son bombardement d'artillerie à 11h30 ?",
                opt1: "A) En attente du rassemblement de la Garde Impériale.",
                opt2: "B) La pluie battante a rendu la boue trop épaisse pour l'artillerie.",
                opt3: "C) En attente de l'aile du maréchal Grouchy.",
                epiTitle: "Le Crépuscule de l'Aigle",
                epiBody: "Avec l'effondrement de la Garde Impériale à 19h30 et l'arrivée des Prussiens sur le flanc, le rêve de Napoléon est brisé. Exilé à Sainte-Hélène, il s'éteint en 1821.",
                btnCloseEpi: "Retour au Commandement Tactique",
                correctMsg: "Correct ! Le sol détrempé empêchait les boulets de canon de ricocher.",
                incorrectMsg: "Incorrect. Pensez aux conditions météo de la nuit du 17 juin."
            }
        };

        const timelineData = {
            1: {
                time: "11:30 AM",
                EN: "Opening Deployment: Heavy overnight rain softens the fields. Napoleon waits for the ground to dry so artillery cannonballs can skip effectively.",
                FR: "Déploiement d'ouverture : La pluie nocturne détrempe les champs. Napoléon attend que le sol sèche pour que les boulets ricochent.",
                frenchPos: { top: "65%", left: "47%" }, coalitionPos: { top: "32%", left: "47%" }, prussianPos: { top: "55%", left: "88%", opacity: "0.15" },
                frenchNum: "72,000", frenchPct: "100", coalitionNum: "68,000", coalitionPct: "95", prussianNum: "Distant (En Route)", prussianPct: "5",
                arrow: ''
            },
            2: {
                time: "01:00 PM",
                EN: "The Grand Battery: Napoleon unleashes 80 heavy guns onto Wellington's center ridge to soften Allied formations.",
                FR: "La Grande Batterie : Napoléon déploie 80 canons lourds sur le centre de Wellington pour briser ses lignes.",
                frenchPos: { top: "60%", left: "47%" }, coalitionPos: { top: "32%", left: "47%" }, prussianPos: { top: "55%", left: "85%", opacity: "0.2" },
                frenchNum: "72,000", frenchPct: "100", coalitionNum: "65,000", coalitionPct: "90", prussianNum: "Approaching", prussianPct: "10",
                arrow: '<line x1="470" y1="500" x2="470" y2="380" stroke="#d4af37" stroke-width="3"/>'
            },
            3: {
                time: "02:00 PM",
                EN: "d'Erlon's Assault: 16,000 French infantry attack the Allied left-center. Heavy British cavalry counter-attacks, inflicting severe casualties.",
                FR: "L'Assaut de d'Erlon : 16 000 fantassins attaquent le centre-gauche. La cavalerie britannique contre-attaque lourdement.",
                frenchPos: { top: "48%", left: "47%" }, coalitionPos: { top: "30%", left: "47%" }, prussianPos: { top: "55%", left: "75%", opacity: "0.4" },
                frenchNum: "66,000", frenchPct: "90", coalitionNum: "61,000", coalitionPct: "85", prussianNum: "Flank Guard", prussianPct: "30",
                arrow: '<line x1="470" y1="450" x2="470" y2="320" stroke="#1d4ed8" stroke-width="4"/>'
            },
            4: {
                time: "03:30 PM",
                EN: "Hougoumont Meatgrinder: Continuous French assaults on the chateau draw in elite divisions, locking up critical reserves.",
                FR: "Boucherie à Hougoumont : Les assauts continus sur le château absorbent les réserves françaises.",
                frenchPos: { top: "55%", left: "35%" }, coalitionPos: { top: "30%", left: "47%" }, prussianPos: { top: "55%", left: "68%", opacity: "0.5" },
                frenchNum: "62,000", frenchPct: "85", coalitionNum: "58,000", coalitionPct: "80", prussianNum: "Encountering Vanguard", prussianPct: "45",
                arrow: '<line x1="470" y1="520" x2="280" y2="550" stroke="#1d4ed8" stroke-width="4"/>'
            },
            5: {
                time: "04:30 PM",
                EN: "Ney's Cavalry Charges: Marshal Ney launches mass cavalry charges against Allied infantry squares without infantry support.",
                FR: "Charges de Cavalerie de Ney : Le maréchal Ney lance des charges massives de cuirassiers sans soutien d'infanterie.",
                frenchPos: { top: "40%", left: "47%" }, coalitionPos: { top: "28%", left: "47%" }, prussianPos: { top: "55%", left: "60%", opacity: "0.7" },
                frenchNum: "55,000", frenchPct: "75", coalitionNum: "52,000", coalitionPct: "72", prussianNum: "Engaging Plancenoit", prussianPct: "60",
                arrow: '<line x1="470" y1="400" x2="470" y2="300" stroke="#1d4ed8" stroke-width="5" stroke-dasharray="5,5"/>'
            },
            6: {
                time: "06:00 PM",
                EN: "Fall of La Haye Sainte: French troops capture the strategic farmhouse, threatening the core of Wellington's center.",
                FR: "Chute de La Haye Sainte : Les troupes françaises prennent la ferme stratégique, menaçant le centre de Wellington.",
                frenchPos: { top: "38%", left: "47%" }, coalitionPos: { top: "26%", left: "47%" }, prussianPos: { top: "52%", left: "55%", opacity: "0.85" },
                frenchNum: "50,000", frenchPct: "68", coalitionNum: "50,000", coalitionPct: "69", prussianNum: "Pressing Flank", prussianPct: "80",
                arrow: '<line x1="470" y1="350" x2="470" y2="280" stroke="#1d4ed8" stroke-width="5"/>'
            },
            7: {
                time: "07:30 PM",
                EN: "The Imperial Guard: Napoleon commits the veteran Imperial Guard up the ridge. British guardsmen hold firm and repulse the elite attack.",
                FR: "La Garde Impériale : Napoléon engage la Garde. Les gardes britanniques tiennent bon et repoussent l'élite.",
                frenchPos: { top: "34%", left: "47%" }, coalitionPos: { top: "26%", left: "47%" }, prussianPos: { top: "50%", left: "50%", opacity: "1" },
                frenchNum: "42,000", frenchPct: "58", coalitionNum: "48,000", coalitionPct: "66", prussianNum: "Fully Integrated", prussianPct: "100",
                arrow: '<line x1="470" y1="320" x2="470" y2="260" stroke="#d4af37" stroke-width="6"/>'
            },
            8: {
                time: "09:00 PM",
                EN: "The Rout: Word spreads that the Guard is retreating. Panic consumes the French army as Wellington and Blücher unite.",
                FR: "La Déroute : La panique s'empare de l'armée française suite au repli de la Garde. Jonction de Wellington et Blücher.",
                frenchPos: { top: "78%", left: "35%" }, coalitionPos: { top: "35%", left: "47%" }, prussianPos: { top: "45%", left: "48%", opacity: "1" },
                frenchNum: "25,000 (Routing)", frenchPct: "35", coalitionNum: "47,000", coalitionPct: "65", prussianNum: "Pursuing Rout", prussianPct: "100",
                arrow: ''
            }
        };

        const slider = document.getElementById('timeline-slider');
        const clockDisplay = document.getElementById('clock-display');
        const narrativeText = document.getElementById('narrative-text');
        
        const valFrench = document.getElementById('val-french');
        const barFrench = document.getElementById('bar-french');
        const valCoalition = document.getElementById('val-coalition');
        const barCoalition = document.getElementById('bar-coalition');
        const valPrussian = document.getElementById('val-prussian');
        const barPrussian = document.getElementById('bar-prussian');

        const frenchPin = document.getElementById('french-pin');
        const coalitionPin = document.getElementById('coalition-pin');
        const prussianPin = document.getElementById('prussian-pin');
        const tacticalArrows = document.getElementById('svg-tactical-arrows');

        function updateTimeline() {
            const step = slider.value;
            const data = timelineData[step];

            clockDisplay.textContent = data.time;
            narrativeText.textContent = data[currentLang];

            valFrench.textContent = data.frenchNum;
            barFrench.style.width = data.frenchPct + '%';

            valCoalition.textContent = data.coalitionNum;
            barCoalition.style.width = data.coalitionPct + '%';

            valPrussian.textContent = data.prussianNum;
            barPrussian.style.width = data.prussianPct + '%';

            frenchPin.style.top = data.frenchPos.top;
            frenchPin.style.left = data.frenchPos.left;

            coalitionPin.style.top = data.coalitionPos.top;
            coalitionPin.style.left = data.coalitionPos.left;

            prussianPin.style.top = data.prussianPos.top;
            prussianPin.style.left = data.prussianPos.left;
            prussianPin.style.opacity = data.prussianPos.opacity;

            tacticalArrows.innerHTML = data.arrow;
        }

        slider.addEventListener('input', () => {
            updateTimeline();
            if (slider.value === "8") {
                setTimeout(() => {
                    document.getElementById('epilogue-modal').style.display = 'flex';
                }, 1000);
            }
        });

        function toggleLanguage() {
            currentLang = (currentLang === 'EN') ? 'FR' : 'EN';
            const dict = i18n[currentLang];

            document.getElementById('hdr-title').textContent = dict.hdrTitle;
            document.getElementById('badge-weather').textContent = dict.badgeWeather;
            document.getElementById('badge-grouchy').textContent = dict.badgeGrouchy;
            document.getElementById('lang-toggle-btn').textContent = dict.langBtn;
            
            document.getElementById('lbl-log-title').textContent = dict.logTitle;
            document.getElementById('lbl-stat-title').textContent = dict.statTitle;
            document.getElementById('lbl-french-army').textContent = dict.frenchArmy;
            document.getElementById('lbl-allied-army').textContent = dict.alliedArmy;
            document.getElementById('lbl-prussian-army').textContent = dict.prussianArmy;
            
            document.getElementById('lbl-quiz-title').textContent = dict.quizTitle;
            document.getElementById('quiz-question').textContent = dict.quizQ;
            document.getElementById('q-opt-1').textContent = dict.opt1;
            document.getElementById('q-opt-2').textContent = dict.opt2;
            document.getElementById('q-opt-3').textContent = dict.opt3;

            document.getElementById('epi-title').textContent = dict.epiTitle;
            document.getElementById('epi-body').textContent = dict.epiBody;
            document.getElementById('btn-close-epilogue').textContent = dict.btnCloseEpi;

            updateTimeline();
        }

        function checkAnswer(option) {
            const feedback = document.getElementById('quiz-feedback');
            const dict = i18n[currentLang];
            if (option === 2) {
                feedback.style.color = "#34d399";
                feedback.textContent = dict.correctMsg;
            } else {
                feedback.style.color = "#f87171";
                feedback.textContent = dict.incorrectMsg;
            }
        }

        function closeEpilogue() {
            document.getElementById('epilogue-modal').style.display = 'none';
        }

        function skipIntro() {
            document.getElementById('cinematic-overlay').style.opacity = '0';
            setTimeout(() => { document.getElementById('cinematic-overlay').style.display = 'none'; }, 1200);
        }

        const introSteps = [
            { title: "The Corsican Eagle", sub: "Part I: Origins", body: "Born in Ajaccio, Corsica in 1769, Napoleon rose from an obscure artillery officer through the French Revolution." },
            { title: "Master of Europe", sub: "Part II: Empire", body: "Crowns himself Emperor in 1805. Sweeps Europe through legendary victories like Austerlitz and Jena." },
            { title: "The Hundred Days", sub: "Part III: Return", body: "Escapes exile from Elba in March 1815, marching to Paris to reclaim his throne and face the Seventh Coalition." },
            { title: "The Final Showdown", sub: "Part IV: June 18, 1815", body: "Marches into Belgium to destroy Wellington and Blücher before they can unite." }
        ];
        let introIdx = 0;
        function nextIntroStep() {
            introIdx++;
            if(introIdx < introSteps.length) {
                document.getElementById('intro-title').textContent = introSteps[introIdx].title;
                document.getElementById('intro-subtitle').textContent = introSteps[introIdx].sub;
                document.getElementById('intro-body').textContent = introSteps[introIdx].body;
                document.getElementById('intro-step-count').textContent = `Step ${introIdx + 1} of 4`;
            } else {
                skipIntro();
            }
        }
    </script>
</body>
</html>
