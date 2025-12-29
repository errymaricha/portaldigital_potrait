<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Signage BSIKD</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@600;700;900&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* Animasi Scroll dengan JEDA (Pause) di Akhir */
        @keyframes scrollWithPause {
            0% { transform: translateY(100%); opacity: 0; }
            5% { transform: translateY(80%); opacity: 1; }
            85% { transform: translateY(-100%); opacity: 1; }
            90%, 100% { transform: translateY(-100%); opacity: 0; }
        }

        body {
            background: #000;
            margin: 0;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            font-family: 'Source Sans 3', sans-serif;
            display: flex;
            justify-content: center;
        }

        .portrait-container {
            width: 56.25vh;
            height: 100vh;
            background: #0f172a;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            z-index: 10;
        }

        .slide.active {
            opacity: 1;
            z-index: 20;
        }

        /* PERUBAHAN DISINI: Agenda Biru menjadi Kanan-Kiri */
        .agenda-static-container {
            margin-top: 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr; /* Membagi menjadi 2 kolom */
            gap: 1rem;
            z-index: 30;
        }

        .info-card {
            background: linear-gradient(to right, rgba(30, 58, 138, 0.95), rgba(30, 41, 59, 0.92));
            border-left: 8px solid #3b82f6;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
        }

        /* Wrapper Scroll */
        .notes-scroll-wrapper {
            flex-grow: 1;
            margin-top: 2rem;
            height: 40vh; 
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to bottom, transparent, black 10%, black 90%, transparent);
        }

        .notes-scroll-content {
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            animation: scrollWithPause 30s linear infinite; 
            will-change: transform;
        }

        .announcement-card {
            background: rgba(251, 191, 36, 0.18);
            backdrop-filter: blur(10px);
            border-left: 12px solid #fbbf24;
            border-radius: 1.25rem;
            padding: 2rem;
        }

        #clock {
            font-size: 6rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.9);
        }

        .marquee-container {
            display: flex;
            width: max-content;
            animation: marquee 50s linear infinite;
        }
    </style>
</head>
<body>

    <div class="portrait-container">
        <main class="flex-grow relative overflow-hidden flex flex-col px-8">
            
            @if($agendas->count() > 0 || $notes->count() > 0)
            <div class="slide active flex flex-col h-full" id="slide-info">
                <div class="absolute inset-0 -mx-8">
                    <img src="{{ asset('images/foto_kampus.jpg') }}" class="w-full h-full object-cover opacity-30">
                    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/40 via-slate-900/80 to-slate-900"></div>
                </div>

                <div class="relative z-10 pt-12 flex flex-col h-full">
                    <div class="text-center">
                        <h2 class="text-3xl font-black text-white uppercase tracking-tighter">
                            Informasi Kampus
                        </h2>
                        <div class="h-1 w-16 bg-yellow-500 mx-auto mt-2 rounded-full"></div>
                    </div>

                    <div class="agenda-static-container">
                        @foreach($agendas as $agenda)
                            <div class="info-card">
                                <h3 class="text-white font-black text-2xl leading-tight mb-2">{{ $agenda->isi_agenda }}</h3>
                                <div class="flex flex-col gap-1 text-sm font-bold text-blue-300 uppercase tracking-wider">
                                    <span>📅 {{ $agenda->tgl }}</span>
                                    <span>⏰ {{ $agenda->jam }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="notes-scroll-wrapper">
                        <div class="notes-scroll-content">
                            @foreach($notes as $note)
                                <div class="announcement-card">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="px-4 py-1 bg-yellow-500 rounded text-black font-black text-sm">PENGUMUMAN</span>
                                        <h4 class="text-yellow-400 font-black text-2xl uppercase tracking-tight">{{ $note->judul_note }}</h4>
                                    </div>
                                    <div class="text-white text-xl font-bold leading-relaxed">{!! $note->isi !!}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @foreach($posters as $p)
                <div class="slide {{ ($agendas->isEmpty() && $notes->isEmpty() && $loop->first) ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $p->path_poster) }}" class="w-full h-full object-cover">
                </div>
            @endforeach
        </main>

        <div class="absolute bottom-[10vh] left-0 right-0 flex justify-center z-50">
            <div class="clock-container text-center">
                <h2 id="clock" class="font-black text-white tracking-tighter tabular-nums leading-none">00:00:00</h2>
                <div class="h-1 w-32 bg-yellow-500 mx-auto my-3 rounded-full opacity-80"></div>
                <p class="text-2xl font-bold text-yellow-500 tracking-[0.2em] uppercase">{{ now()->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        <footer class="h-[5vh] w-full bg-white flex items-center overflow-hidden z-50 border-t-2 border-yellow-500 relative">
            <div class="marquee-container flex items-center h-full">
                <div class="marquee-content">
                    @foreach($runningTexts as $rt)
                        <span class="text-lg font-black text-slate-900 mx-10 uppercase flex items-center whitespace-nowrap">
                            <div class="mr-3 w-3 h-3 bg-red-600 rounded-full animate-pulse shadow-sm"></div> {{ $rt->isitext }}
                        </span>
                    @endforeach
                </div>
                <div class="marquee-content">
                    @foreach($runningTexts as $rt)
                        <span class="text-lg font-black text-slate-900 mx-10 uppercase flex items-center whitespace-nowrap">
                            <div class="mr-3 w-3 h-3 bg-red-600 rounded-full animate-pulse shadow-sm"></div> {{ $rt->isitext }}
                        </span>
                    @endforeach
                </div>
            </div>
        </footer>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${h}:${m}:${s}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        let slideIndex = 0;
        const slides = document.querySelectorAll(".slide");

        function showSlides() {
            if (slides.length <= 1) return;
            slides.forEach(s => s.classList.remove("active"));
            slideIndex = (slideIndex + 1) % slides.length;
            slides[slideIndex].classList.add("active");

            const currentIsInfo = slides[slideIndex].innerHTML.includes('Informasi Kampus');
            let duration = currentIsInfo ? 60000 : 10000; 
            
            setTimeout(showSlides, duration);
        }

        if (slides.length > 1) {
            setTimeout(showSlides, 60000);
        }
        setTimeout(() => { window.location.reload(); }, 600000);
    </script>
</body>
</html>