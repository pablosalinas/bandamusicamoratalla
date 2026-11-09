/**
 * Intérprete y Reproductor Musical Avanzado para Partituras (Banda de Música)
 * 
 * Capacidades:
 * 1. OMR (Optical Music Recognition) avanzado en Canvas:
 *    - Detección de pentagramas y líneas de compás.
 *    - Distinción entre cabezas huecas (blancas, redondas) y sólidas (negras, corcheas, semicorcheas).
 *    - Detección de plicas (stems), corchetes y barras de unión (beams).
 *    - Detección de puntillos (augmentation dots: 1.5x duración).
 *    - Detección de ligaduras de prolongación (ties) y expresión (slurs/legato).
 *    - Detección de silencios y alteraciones (#, b, becuadro).
 *    - Cuantización rítmica métrica normalizada por compases.
 * 2. Motor de Síntesis Instrumental Físico (Web Audio API):
 *    - Modelado acústico independiente para cada instrumento de banda de música.
 *    - Transposición real (Sib, Mib, Fa, Do) y afinación a 442 Hz.
 *    - Articulación legato en ligaduras y sostenido continuo en ligaduras de unión.
 * 3. Parser nativo de MusicXML (.xml / .musicxml) y Standard MIDI (.mid).
 * 4. Cursor visual DOM flotante (sin capas opacas que tapen la partitura).
 * 5. Inspector y editor interactivo de compases y figuras musicales.
 */

(function (window) {
    'use strict';

    // -------------------------------------------------------------
    // 1. PERFILES DE INSTRUMENTOS DE BANDA DE MÚSICA (GM + TESITURA)
    // -------------------------------------------------------------
    const INSTRUMENT_PROFILES = {
        'flute': {
            name: 'Flauta Travesera',
            family: 'Viento Madera',
            gmProgram: 73,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0,
            range: [59, 96],
            color: '#38bdf8'
        },
        'piccolo': {
            name: 'Flautín',
            family: 'Viento Madera',
            gmProgram: 72,
            octaveShift: 1,
            clef: 'treble',
            transposition: 0,
            range: [74, 102],
            color: '#0284c7'
        },
        'oboe': {
            name: 'Oboe',
            family: 'Viento Madera',
            gmProgram: 68,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0,
            range: [58, 91],
            color: '#0ea5e9'
        },
        'corno_ingles': {
            name: 'Corno Inglés',
            family: 'Viento Madera',
            gmProgram: 69,
            octaveShift: 0,
            clef: 'treble',
            transposition: -7,
            range: [51, 84],
            color: '#0369a1'
        },
        'clarinet': {
            name: 'Clarinete (en Sib)',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: 0,
            clef: 'treble',
            transposition: -2,
            range: [50, 94],
            color: '#34d399'
        },
        'requinto': {
            name: 'Requinto (en Mib)',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: 0,
            clef: 'treble',
            transposition: 3,
            range: [55, 96],
            color: '#10b981'
        },
        'bass_clarinet': {
            name: 'Clarinete Bajo',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: -1,
            clef: 'treble',
            transposition: -14,
            range: [38, 77],
            color: '#059669'
        },
        'bassoon': {
            name: 'Fagot',
            family: 'Viento Madera',
            gmProgram: 70,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [34, 75],
            color: '#047857'
        },
        'alto_sax': {
            name: 'Saxofón Alto (en Mib)',
            family: 'Viento Madera',
            gmProgram: 65,
            octaveShift: 0,
            clef: 'treble',
            transposition: -9,
            range: [49, 87],
            color: '#f59e0b'
        },
        'tenor_sax': {
            name: 'Saxofón Tenor (en Sib)',
            family: 'Viento Madera',
            gmProgram: 66,
            octaveShift: 0,
            clef: 'treble',
            transposition: -14,
            range: [44, 82],
            color: '#d97706'
        },
        'baritone_sax': {
            name: 'Saxofón Barítono (en Mib)',
            family: 'Viento Madera',
            gmProgram: 67,
            octaveShift: -1,
            clef: 'treble',
            transposition: -21,
            range: [36, 75],
            color: '#b45309'
        },
        'soprano_sax': {
            name: 'Saxofón Soprano',
            family: 'Viento Madera',
            gmProgram: 64,
            octaveShift: 0,
            clef: 'treble',
            transposition: -2,
            range: [56, 94],
            color: '#fbbf24'
        },
        'trumpet': {
            name: 'Trompeta (en Sib)',
            family: 'Viento Metal',
            gmProgram: 56,
            octaveShift: 0,
            clef: 'treble',
            transposition: -2,
            range: [52, 88],
            color: '#ef4444'
        },
        'flugelhorn': {
            name: 'Fliscorno',
            family: 'Viento Metal',
            gmProgram: 56,
            octaveShift: 0,
            clef: 'treble',
            transposition: -2,
            range: [52, 86],
            color: '#f87171'
        },
        'french_horn': {
            name: 'Trompa (en Fa)',
            family: 'Viento Metal',
            gmProgram: 60,
            octaveShift: 0,
            clef: 'treble',
            transposition: -7,
            range: [41, 77],
            color: '#f97316'
        },
        'trombone': {
            name: 'Trombón',
            family: 'Viento Metal',
            gmProgram: 57,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [40, 72],
            color: '#e11d48'
        },
        'bass_trombone': {
            name: 'Trombón Bajo',
            family: 'Viento Metal',
            gmProgram: 57,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [34, 67],
            color: '#be123c'
        },
        'euphonium': {
            name: 'Bombardino',
            family: 'Viento Metal',
            gmProgram: 58,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [40, 77],
            color: '#fb923c'
        },
        'tuba': {
            name: 'Tuba',
            family: 'Viento Metal',
            gmProgram: 58,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [28, 55],
            color: '#c2410c'
        },
        'cello': {
            name: 'Violonchelo',
            family: 'Cuerda',
            gmProgram: 42,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [36, 76],
            polyphonic: true,
            color: '#a855f7'
        },
        'contrabass': {
            name: 'Contrabajo',
            family: 'Cuerda',
            gmProgram: 43,
            octaveShift: -1,
            clef: 'bass',
            transposition: 0,
            range: [28, 60],
            polyphonic: false,
            color: '#9333ea'
        },
        'mallets': {
            name: 'Xilófono / Lira / Marimba',
            family: 'Percusión Afinada',
            gmProgram: 13,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0,
            range: [60, 96],
            polyphonic: true,
            color: '#22c55e'
        },
        'timpani': {
            name: 'Timbales',
            family: 'Percusión Afinada',
            gmProgram: 47,
            octaveShift: 0,
            clef: 'bass',
            transposition: 0,
            range: [41, 60],
            polyphonic: false,
            color: '#16a34a'
        },
        'percussion': {
            name: 'Caja / Batería de Banda',
            family: 'Percusión',
            gmProgram: 118,
            octaveShift: 0,
            clef: 'neutral',
            transposition: 0,
            range: [36, 60],
            polyphonic: true,
            color: '#64748b'
        },
        'piano': {
            name: 'Piano',
            family: 'Teclado',
            gmProgram: 0,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0,
            range: [21, 108],
            polyphonic: true,
            color: '#e2e8f0'
        },
        'ensemble': {
            name: 'Tutti Banda (Guión General)',
            family: 'Ensamble Completo',
            gmProgram: 60,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0,
            range: [36, 96],
            polyphonic: true,
            color: '#818cf8'
        }
    };

    function matchInstrumentProfile(name) {
        if (!name) return 'ensemble';
        const n = name.toUpperCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        if (n.includes('FLAUTIN') || n.includes('PICCOLO')) return 'piccolo';
        if (n.includes('FLAUTA') || n.includes('FLUTE')) return 'flute';
        if (n.includes('OBOE')) return 'oboe';
        if (n.includes('CORNO INGLES') || n.includes('ENGLISH HORN')) return 'corno_ingles';
        if (n.includes('FAGOT') || n.includes('BASSOON')) return 'bassoon';
        if (n.includes('REQUINTO') || n.includes('EB CLARINET') || n.includes('CLARINET IN EB')) return 'requinto';
        if (n.includes('CLARINETE BAJO') || n.includes('BASS CLARINET')) return 'bass_clarinet';
        if (n.includes('CLARINETE') || n.includes('CLARINET')) return 'clarinet';

        if (n.includes('SAX') || n.includes('SAXOFON')) {
            if (n.includes('ALTO')) return 'alto_sax';
            if (n.includes('TENOR')) return 'tenor_sax';
            if (n.includes('BARITONO') || n.includes('BARITONE')) return 'baritone_sax';
            if (n.includes('SOPRANO')) return 'soprano_sax';
            return 'alto_sax';
        }

        if (n.includes('FLISCORNO') || n.includes('FLUGEL')) return 'flugelhorn';
        if (n.includes('TROMPETA') || n.includes('CORNETA') || n.includes('TRUMPET') || n.includes('CORNET')) return 'trumpet';
        if (n.includes('TROMPA') || n.includes('HORN IN F') || n.includes('FRENCH HORN') || n.includes('CORNO')) return 'french_horn';
        if (n.includes('TROMBON BAJO') || n.includes('BASS TROMBONE')) return 'bass_trombone';
        if (n.includes('TROMBON') || n.includes('TROMBONE')) return 'trombone';
        if (n.includes('BOMBARDINO') || n.includes('EUPHONIUM') || n.includes('BARITONE')) return 'euphonium';
        if (n.includes('TUBA') || n.includes('SOUSAFON') || n.includes('BASSES')) return 'tuba';

        if (n.includes('VIOLONCHELO') || n.includes('CELLO')) return 'cello';
        if (n.includes('CONTRABAJO') || n.includes('DOUBLE BASS') || n.includes('STRING BASS')) return 'contrabass';

        if (n.includes('TIMBAL') || n.includes('TIMPANI')) return 'timpani';
        if (n.includes('XILOFONO') || n.includes('MARIMBA') || n.includes('LIRA') || n.includes('GLOCKENSPIEL') || n.includes('CAMPANAS') || n.includes('VIBRAFONO') || n.includes('MALLET')) return 'mallets';
        if (n.includes('CAJA') || n.includes('BATERIA') || n.includes('BOMBO') || n.includes('PLATOS') || n.includes('PERCUSION') || n.includes('PERCUSSION') || n.includes('DRUM')) return 'percussion';
        if (n.includes('PIANO')) return 'piano';

        return 'ensemble';
    }

    // -------------------------------------------------------------
    // 2. MOTOR HÍBRIDO DE SONIDO REAL (SOUNDFONT MUESTRAS) Y SÍNTESIS MIDI (WEB AUDIO API)
    // -------------------------------------------------------------
    const SOUNDFONT_INSTRUMENT_MAP = {
        piccolo: 'piccolo',
        flute: 'flute',
        oboe: 'oboe',
        corno_ingles: 'english_horn',
        bassoon: 'bassoon',
        requinto: 'clarinet',
        clarinet: 'clarinet',
        bass_clarinet: 'clarinet',
        soprano_sax: 'soprano_sax',
        alto_sax: 'alto_sax',
        tenor_sax: 'tenor_sax',
        baritone_sax: 'baritone_sax',
        trumpet: 'trumpet',
        flugelhorn: 'french_horn',
        french_horn: 'french_horn',
        trombone: 'trombone',
        bass_trombone: 'trombone',
        euphonium: 'trombone',
        tuba: 'tuba',
        violoncello: 'cello',
        contrabass: 'contrabass',
        mallets: 'xylophone',
        timpani: 'timpani',
        percussion: 'synth_drum',
        piano: 'acoustic_grand_piano',
        ensemble: 'clarinet'
    };

    const MIDI_PITCH_NAMES = ['C', 'Db', 'D', 'Eb', 'E', 'F', 'Gb', 'G', 'Ab', 'A', 'Bb', 'B'];

    class InstrumentSynth {
        constructor() {
            this.ctx = null;
            this.masterGain = null;
            this.reverbNode = null;
            this.volume = 0.85;
            this.concertPitchA4 = 442; // Banda afinada a 442 Hz
            this.activeNodes = [];
            // Modo de sonido seleccionable: 'real' (Muestras Reales SoundFont HQ) o 'midi' (Sintetizador MIDI WebAudio)
            this.soundEngine = 'real';
            this.soundfontData = {};      // sfName -> { 'C4': 'data:audio/mp3;base64,...' }
            this.soundfontLoading = {};   // sfName -> Promise<boolean>
            this.sampleBufferCache = {};  // `${sfName}_${noteKey}` -> AudioBuffer
        }

        initContext() {
            if (!this.ctx) {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                this.ctx = new AudioCtx();
                this.masterGain = this.ctx.createGain();
                this.masterGain.gain.setValueAtTime(this.volume, this.ctx.currentTime);
                this.createAuditoriumReverb();
                this.masterGain.connect(this.ctx.destination);
            }
            if (this.ctx.state === 'suspended') {
                this.ctx.resume();
            }
        }

        setSoundEngine(mode) {
            this.soundEngine = (mode === 'midi') ? 'midi' : 'real';
        }

        midiToNoteKey(midi) {
            const clamped = Math.max(21, Math.min(108, Math.round(midi)));
            const octave = Math.floor(clamped / 12) - 1;
            const name = MIDI_PITCH_NAMES[((clamped % 12) + 12) % 12];
            return `${name}${octave}`;
        }

        // Pre-carga muestras reales grabadas (MusyngKite SoundFont HQ) para el instrumento elegido
        async preloadSoundfont(instrumentKey = 'clarinet') {
            const sfName = SOUNDFONT_INSTRUMENT_MAP[instrumentKey] || 'clarinet';
            if (this.soundfontData[sfName]) return true;
            if (this.soundfontLoading[sfName]) return this.soundfontLoading[sfName];

            const url = `https://gleitz.github.io/midi-js-soundfonts/MusyngKite/${sfName}-mp3.js`;
            this.soundfontLoading[sfName] = (async () => {
                try {
                    const resp = await fetch(url, { mode: 'cors' });
                    if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
                    const jsText = await resp.text();
                    const firstBrace = jsText.indexOf('{');
                    const lastBrace = jsText.lastIndexOf('}');
                    if (firstBrace !== -1 && lastBrace > firstBrace) {
                        const jsonCandidate = jsText.slice(firstBrace, lastBrace + 1).replace(/,\s*}/g, '}');
                        const parsed = JSON.parse(jsonCandidate);
                        this.soundfontData[sfName] = parsed;
                        this.predecodeCommonNotes(sfName);
                        return true;
                    }
                } catch (err) {
                    // Fallback mediante etiqueta <script> por si fetch encuentra políticas de red
                    try {
                        await new Promise((resolve, reject) => {
                            window.MIDI = window.MIDI || {};
                            window.MIDI.Soundfont = window.MIDI.Soundfont || {};
                            const script = document.createElement('script');
                            script.src = url;
                            script.async = true;
                            script.onload = () => resolve();
                            script.onerror = (e) => reject(e);
                            document.head.appendChild(script);
                        });
                        if (window.MIDI && window.MIDI.Soundfont && window.MIDI.Soundfont[sfName]) {
                            this.soundfontData[sfName] = window.MIDI.Soundfont[sfName];
                            this.predecodeCommonNotes(sfName);
                            return true;
                        }
                    } catch (scriptErr) {
                        console.warn(`No se pudo cargar el banco de sonido real (${sfName}), usando motor MIDI de respaldo.`, scriptErr);
                    }
                }
                return false;
            })();

            return this.soundfontLoading[sfName];
        }

        async decodeSampleBuffer(sfName, noteKey) {
            const cacheKey = `${sfName}_${noteKey}`;
            if (this.sampleBufferCache[cacheKey]) return this.sampleBufferCache[cacheKey];
            const sf = this.soundfontData[sfName];
            if (!sf || !sf[noteKey]) return null;

            this.initContext();
            const dataUri = sf[noteKey];
            const base64Idx = dataUri.indexOf(',');
            if (base64Idx === -1) return null;

            try {
                const b64 = dataUri.slice(base64Idx + 1);
                const binStr = atob(b64);
                const len = binStr.length;
                const bytes = new Uint8Array(len);
                for (let i = 0; i < len; i++) {
                    bytes[i] = binStr.charCodeAt(i);
                }
                const audioBuffer = await this.ctx.decodeAudioData(bytes.buffer.slice(0));
                this.sampleBufferCache[cacheKey] = audioBuffer;
                return audioBuffer;
            } catch (e) {
                return null;
            }
        }

        predecodeCommonNotes(sfName) {
            setTimeout(async () => {
                // Pre-decodificar registro habitual de banda (Mi3=52 a Do6=84) en segundo plano
                for (let m = 48; m <= 86; m++) {
                    const key = this.midiToNoteKey(m);
                    if (this.soundfontData[sfName] && this.soundfontData[sfName][key]) {
                        await this.decodeSampleBuffer(sfName, key);
                    }
                }
            }, 30);
        }

        playRealSample(finalMidi, t, dur, vel, instrumentKey, legato, options = {}) {
            const sfName = SOUNDFONT_INSTRUMENT_MAP[instrumentKey] || 'clarinet';
            const noteKey = this.midiToNoteKey(finalMidi);
            const cacheKey = `${sfName}_${noteKey}`;
            const buffer = this.sampleBufferCache[cacheKey];

            if (!buffer) {
                // Iniciar carga asíncrona para las siguientes notas y usar síntesis inmediatamente sin latencia
                if (!this.soundfontData[sfName]) {
                    this.preloadSoundfont(instrumentKey);
                } else {
                    this.decodeSampleBuffer(sfName, noteKey);
                }
                return false;
            }

            const src = this.ctx.createBufferSource();
            src.buffer = buffer;

            // Ajustar afinación a 442 Hz (estándar de banda sinfónica)
            const pitchRatio = this.concertPitchA4 / 440.0;
            src.playbackRate.setValueAtTime(pitchRatio, t);

            const envGain = this.ctx.createGain();
            const attack = legato ? 0.012 : 0.025;
            const release = legato ? 0.14 : 0.095;
            const startV = (options.hairpin && typeof options.hairpin.startVel === 'number')
                ? options.hairpin.startVel
                : vel;
            const endV = (options.hairpin && typeof options.hairpin.endVel === 'number')
                ? options.hairpin.endVel
                : startV;

            envGain.gain.setValueAtTime(0.001, t);
            envGain.gain.linearRampToValueAtTime(Math.min(1.0, startV * 0.95), t + attack);
            if (Math.abs(endV - startV) > 0.03) {
                envGain.gain.linearRampToValueAtTime(Math.min(1.0, endV * 0.95), t + Math.max(attack + 0.02, dur * 0.92));
            } else {
                envGain.gain.setValueAtTime(Math.min(1.0, startV * 0.90), t + Math.max(attack, dur - 0.03));
            }
            envGain.gain.exponentialRampToValueAtTime(0.0008, t + dur + release);

            src.connect(envGain);
            envGain.connect(this.masterGain);
            if (this.reverbNode) {
                envGain.connect(this.reverbNode);
            }

            src.start(t);
            src.stop(t + dur + release + 0.02);
            return true;
        }

        createAuditoriumReverb() {
            try {
                const sampleRate = this.ctx.sampleRate;
                const length = sampleRate * 1.6;
                const impulse = this.ctx.createBuffer(2, length, sampleRate);
                const left = impulse.getChannelData(0);
                const right = impulse.getChannelData(1);

                for (let i = 0; i < length; i++) {
                    const decay = Math.exp(-i / (sampleRate * 0.45));
                    left[i] = (Math.random() * 2 - 1) * decay;
                    right[i] = (Math.random() * 2 - 1) * decay;
                }

                this.reverbNode = this.ctx.createConvolver();
                this.reverbNode.buffer = impulse;

                const reverbGain = this.ctx.createGain();
                reverbGain.gain.setValueAtTime(0.22, this.ctx.currentTime);
                this.reverbNode.connect(reverbGain);
                reverbGain.connect(this.masterGain);
            } catch (e) {
                console.warn('Reverb no inicializado:', e);
            }
        }

        setVolume(v) {
            this.volume = Math.max(0, Math.min(1, v));
            if (this.masterGain && this.ctx) {
                this.masterGain.gain.setValueAtTime(this.volume, this.ctx.currentTime);
            }
        }

        midiToFreq(midiNote) {
            return this.concertPitchA4 * Math.pow(2, (midiNote - 69) / 12);
        }

        playMetronomeClick(accent = false) {
            this.initContext();
            const t = this.ctx.currentTime;
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(accent ? 1300 : 850, t);
            osc.frequency.exponentialRampToValueAtTime(100, t + 0.035);

            gain.gain.setValueAtTime(accent ? 0.6 : 0.35, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + 0.035);

            osc.connect(gain);
            gain.connect(this.masterGain);
            osc.start(t);
            osc.stop(t + 0.04);
        }

        // Tocar una nota musical (soporta Sonido Real por muestras SoundFont HQ o Sintetizador MIDI WebAudio, mordentes y reguladores crescendo/disminuyendo)
        playNote(midiNote, duration = 0.5, instrumentKey = 'clarinet', applyTransposition = false, legato = false, velocity = 0.8, options = {}) {
            this.initContext();
            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];

            let finalNote = midiNote;
            if (applyTransposition && prof.transposition !== 0) {
                finalNote = midiNote + prof.transposition;
            }
            if (prof.octaveShift) {
                finalNote += prof.octaveShift * 12;
            }

            const freq = this.midiToFreq(finalNote);
            const startOffset = Math.max(0, options.startTimeOffset || 0);
            const now = this.ctx.currentTime + startOffset;
            const dur = Math.max(0.045, duration);

            // Modelar regulador crescendo (<) o disminuyendo (>) sobre notas sostenidas
            if (this.masterGain && options.hairpin) {
                const baseVol = this.volume || 0.85;
                if (typeof options.hairpin === 'object' && typeof options.hairpin.startVel === 'number') {
                    const ratioStart = Math.max(0.4, Math.min(1.25, options.hairpin.startVel / Math.max(0.25, velocity)));
                    const ratioEnd = Math.max(0.35, Math.min(1.3, options.hairpin.endVel / Math.max(0.25, velocity)));
                    this.masterGain.gain.cancelScheduledValues(now);
                    this.masterGain.gain.setValueAtTime(baseVol * ratioStart, now);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol * ratioEnd, now + dur * 0.92);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol, now + dur);
                } else if (options.hairpin === 'cresc') {
                    this.masterGain.gain.cancelScheduledValues(now);
                    this.masterGain.gain.setValueAtTime(baseVol * 0.72, now);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol * 1.18, now + dur * 0.9);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol, now + dur);
                } else if (options.hairpin === 'dim') {
                    this.masterGain.gain.cancelScheduledValues(now);
                    this.masterGain.gain.setValueAtTime(baseVol * 1.12, now);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol * 0.48, now + dur * 0.9);
                    this.masterGain.gain.linearRampToValueAtTime(baseVol, now + dur);
                }
            }

            // 1. Si está activo el motor de Sonido Real (Muestras SoundFont HQ), reproducir muestra acústica real grabada
            if (this.soundEngine === 'real' && instrumentKey !== 'percussion') {
                const playedReal = this.playRealSample(finalNote, now, dur, velocity, instrumentKey, legato, options);
                if (playedReal) return;
            }

            // 2. Motor Sintetizador MIDI (Web Audio API) o fallback instantáneo mientras se descarga el banco real
            switch (instrumentKey) {
                case 'flute':
                case 'piccolo':
                    this.synthFlute(freq, now, dur, velocity, legato);
                    break;
                case 'clarinet':
                case 'requinto':
                case 'bass_clarinet':
                    this.synthClarinet(freq, now, dur, velocity, legato);
                    break;
                case 'alto_sax':
                case 'tenor_sax':
                case 'baritone_sax':
                case 'soprano_sax':
                    this.synthSax(freq, now, dur, velocity, instrumentKey, legato);
                    break;
                case 'trumpet':
                case 'flugelhorn':
                    this.synthTrumpet(freq, now, dur, velocity, legato);
                    break;
                case 'french_horn':
                    this.synthFrenchHorn(freq, now, dur, velocity, legato);
                    break;
                case 'trombone':
                case 'bass_trombone':
                    this.synthTrombone(freq, now, dur, velocity, legato);
                    break;
                case 'euphonium':
                case 'tuba':
                    this.synthTuba(freq, now, dur, velocity, legato);
                    break;
                case 'oboe':
                case 'corno_ingles':
                case 'bassoon':
                    this.synthDoubleReed(freq, now, dur, velocity, legato);
                    break;
                case 'timpani':
                    this.synthTimpani(freq, now, dur, velocity);
                    break;
                case 'mallets':
                    this.synthMallets(freq, now, dur, velocity);
                    break;
                case 'percussion':
                    this.synthPercussion(now, dur, velocity);
                    break;
                case 'piano':
                    this.synthPiano(freq, now, dur, velocity);
                    break;
                default:
                    this.synthEnsemble(freq, now, dur, velocity, legato);
                    break;
            }
        }

        synthFlute(freq, t, dur, vel, legato) {
            const osc = this.ctx.createOscillator();
            const oscHarm = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, t);

            oscHarm.type = 'sine';
            oscHarm.frequency.setValueAtTime(freq * 2, t);

            const vib = this.ctx.createOscillator();
            const vibGain = this.ctx.createGain();
            vib.frequency.setValueAtTime(5.4, t);
            vibGain.gain.setValueAtTime(freq * 0.015, t);
            vib.connect(osc.frequency);
            vib.start(t + 0.08);
            vib.stop(t + dur);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 3.8, 8000), t);

            const attack = legato ? 0.015 : 0.04;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.72, t + attack);
            gain.gain.setValueAtTime(vel * 0.65, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            oscHarm.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            oscHarm.start(t);
            osc.stop(t + dur);
            oscHarm.stop(t + dur);
        }

        synthClarinet(freq, t, dur, vel, legato) {
            const osc1 = this.ctx.createOscillator();
            const osc3 = this.ctx.createOscillator();
            const osc5 = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'square';
            osc1.frequency.setValueAtTime(freq, t);

            osc3.type = 'sine';
            osc3.frequency.setValueAtTime(freq * 3, t);

            osc5.type = 'sine';
            osc5.frequency.setValueAtTime(freq * 5, t);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 2.8, 4200), t);
            filter.Q.setValueAtTime(1.8, t);

            const attack = legato ? 0.012 : 0.035;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.7, t + attack);
            gain.gain.setValueAtTime(vel * 0.6, t + dur * 0.88);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            const osc1Gain = this.ctx.createGain();
            osc1Gain.gain.setValueAtTime(0.65, t);
            const osc3Gain = this.ctx.createGain();
            osc3Gain.gain.setValueAtTime(0.24, t);
            const osc5Gain = this.ctx.createGain();
            osc5Gain.gain.setValueAtTime(0.1, t);

            osc1.connect(osc1Gain);
            osc3.connect(osc3Gain);
            osc5.connect(osc5Gain);

            osc1Gain.connect(filter);
            osc3Gain.connect(filter);
            osc5Gain.connect(filter);

            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc1.start(t);
            osc3.start(t);
            osc5.start(t);
            osc1.stop(t + dur);
            osc3.stop(t + dur);
            osc5.stop(t + dur);
        }

        synthSax(freq, t, dur, vel, type, legato) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'bandpass';
            const centerFormant = (type === 'tenor_sax') ? 850 : (type === 'baritone_sax' ? 600 : 1250);
            filter.frequency.setValueAtTime(centerFormant, t);
            filter.Q.setValueAtTime(2.2, t);

            const vib = this.ctx.createOscillator();
            const vibGain = this.ctx.createGain();
            vib.frequency.setValueAtTime(5.1, t);
            vibGain.gain.setValueAtTime(freq * 0.02, t);
            vib.connect(osc.frequency);
            vib.start(t + 0.07);
            vib.stop(t + dur);

            const attack = legato ? 0.01 : 0.025;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.8, t + attack);
            gain.gain.setValueAtTime(vel * 0.7, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTrumpet(freq, t, dur, vel, legato) {
            const osc1 = this.ctx.createOscillator();
            const osc2 = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'sawtooth';
            osc1.frequency.setValueAtTime(freq, t);

            osc2.type = 'sawtooth';
            osc2.frequency.setValueAtTime(freq * 1.002, t);

            filter.type = 'lowpass';
            const attack = legato ? 0.008 : 0.015;
            filter.frequency.setValueAtTime(freq * 1.8, t);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 6.5, 9500), t + attack);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 3.5, 5500), t + 0.1);
            filter.Q.setValueAtTime(3.2, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.85, t + attack);
            gain.gain.setValueAtTime(vel * 0.7, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc1.connect(filter);
            osc2.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc1.start(t);
            osc2.start(t);
            osc1.stop(t + dur);
            osc2.stop(t + dur);
        }

        synthFrenchHorn(freq, t, dur, vel, legato) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 2.2, 2200), t);
            filter.Q.setValueAtTime(1.5, t);

            const attack = legato ? 0.02 : 0.05;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.75, t + attack);
            gain.gain.setValueAtTime(vel * 0.65, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTrombone(freq, t, dur, vel, legato) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'lowpass';
            const attack = legato ? 0.01 : 0.025;
            filter.frequency.setValueAtTime(freq * 2, t);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 4.5, 4800), t + attack);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 2.8, 3000), t + 0.12);
            filter.Q.setValueAtTime(2.8, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.88, t + attack);
            gain.gain.setValueAtTime(vel * 0.72, t + dur * 0.88);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTuba(freq, t, dur, vel, legato) {
            const osc1 = this.ctx.createOscillator();
            const oscSub = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'sawtooth';
            osc1.frequency.setValueAtTime(freq, t);

            oscSub.type = 'sine';
            oscSub.frequency.setValueAtTime(freq * 0.5, t);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 3, 1400), t);
            filter.Q.setValueAtTime(1.8, t);

            const attack = legato ? 0.015 : 0.04;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.92, t + attack);
            gain.gain.setValueAtTime(vel * 0.78, t + dur * 0.88);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc1.connect(filter);
            oscSub.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);

            osc1.start(t);
            oscSub.start(t);
            osc1.stop(t + dur);
            oscSub.stop(t + dur);
        }

        synthDoubleReed(freq, t, dur, vel, legato) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'bandpass';
            filter.frequency.setValueAtTime(1450, t);
            filter.Q.setValueAtTime(3.8, t);

            const attack = legato ? 0.01 : 0.02;
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.75, t + attack);
            gain.gain.setValueAtTime(vel * 0.65, t + dur * 0.88);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTimpani(freq, t, dur, vel) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq * 1.25, t);
            osc.frequency.exponentialRampToValueAtTime(freq, t + 0.08);

            gain.gain.setValueAtTime(vel * 0.9, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + Math.min(dur * 1.5, 1.8));

            osc.connect(gain);
            gain.connect(this.masterGain);

            osc.start(t);
            osc.stop(t + Math.min(dur * 1.5, 1.8));
        }

        synthMallets(freq, t, dur, vel) {
            const osc1 = this.ctx.createOscillator();
            const osc2 = this.ctx.createOscillator();
            const gain = this.ctx.createGain();

            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(freq, t);

            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(freq * 4.0, t);

            gain.gain.setValueAtTime(vel * 0.8, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + Math.min(dur * 1.2, 1.2));

            const osc2Gain = this.ctx.createGain();
            osc2Gain.gain.setValueAtTime(0.3, t);
            osc2Gain.gain.exponentialRampToValueAtTime(0.001, t + 0.1);

            osc1.connect(gain);
            osc2.connect(osc2Gain);
            osc2Gain.connect(gain);

            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc1.start(t);
            osc2.start(t);
            osc1.stop(t + 1.2);
            osc2.stop(t + 0.2);
        }

        synthPercussion(t, dur, vel) {
            const bufferSize = this.ctx.sampleRate * 0.1;
            const buffer = this.ctx.createBuffer(1, bufferSize, this.ctx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = Math.random() * 2 - 1;
            }

            const noise = this.ctx.createBufferSource();
            noise.buffer = buffer;

            const filter = this.ctx.createBiquadFilter();
            filter.type = 'highpass';
            filter.frequency.setValueAtTime(1000, t);

            const gain = this.ctx.createGain();
            gain.gain.setValueAtTime(vel * 0.7, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + 0.12);

            noise.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);

            noise.start(t);
        }

        synthPiano(freq, t, dur, vel) {
            const osc1 = this.ctx.createOscillator();
            const osc2 = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'triangle';
            osc1.frequency.setValueAtTime(freq, t);

            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(freq * 1.001, t);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 5, 6000), t);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 2, 2000), t + dur);

            gain.gain.setValueAtTime(vel * 0.8, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + dur * 1.4);

            osc1.connect(filter);
            osc2.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc1.start(t);
            osc2.start(t);
            osc1.stop(t + dur * 1.5);
            osc2.stop(t + dur * 1.5);
        }

        synthEnsemble(freq, t, dur, vel, legato) {
            this.synthTrumpet(freq, t, dur, vel * 0.5, legato);
            this.synthClarinet(freq, t, dur, vel * 0.5, legato);
        }
    }

    // -------------------------------------------------------------
    // 3. LECTOR ÓPTICO AVANZADO (OMR: PENTAGRAMAS, FIGURAS, LIGADURAS)
    // -------------------------------------------------------------
    class SheetMusicReader {
        constructor() {
            this.staves = [];
            this.notes = [];
            this.measures = [];
            this.clef = 'treble';
            this.meter = { num: 4, den: 4, beatDuration: 1.0 }; // Compás 4/4 por defecto
        }

        // Calcula un umbral adaptativo de luminancia (Otsu / percentil de tinta) para partituras escaneadas o de baja resolución
        computeAdaptiveInkThreshold(data, width, height) {
            const hist = new Int32Array(256);
            let totalSamples = 0;
            const stepX = Math.max(2, Math.floor(width / 250));
            const stepY = Math.max(2, Math.floor(height / 250));

            for (let y = Math.floor(height * 0.05); y < Math.floor(height * 0.95); y += stepY) {
                const rowStart = y * width * 4;
                for (let x = Math.floor(width * 0.05); x < Math.floor(width * 0.95); x += stepX) {
                    const idx = rowStart + x * 4;
                    if (data[idx + 3] < 50) continue;
                    const lum = Math.round(0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2]);
                    hist[Math.max(0, Math.min(255, lum))]++;
                    totalSamples++;
                }
            }
            if (totalSamples < 100) return 175;

            // Estimar luminancia del papel (percentil 75) y ajustar corte de tinta
            let acc = 0;
            let bgLum = 240;
            for (let i = 255; i >= 0; i--) {
                acc += hist[i];
                if (acc >= totalSamples * 0.25) {
                    bgLum = i;
                    break;
                }
            }
            return Math.max(125, Math.min(205, Math.round(bgLum - 42)));
        }

        // Procesa el canvas de la partitura
        scanCanvas(canvas, pageNum = 1, instrumentKey = 'clarinet', canvasOffsetTop = 0) {
            if (!canvas || canvas.width === 0 || canvas.height === 0) return [];

            // Si es un canvas PDF que aún no ha terminado de renderizar en la cola, NO escanear ni cachear falso positivo
            if ((canvas._isPdfCanvas && !canvas._isRendered) || (canvas.dataset && canvas.dataset.pageNum && !canvas._viewport)) {
                return [];
            }

            // Devolver caché si ya fue escaneado con los mismos parámetros
            if (canvas._scannedNotes && canvas._scannedNotes.length > 0 && canvas._scannedInstrument === instrumentKey) {
                canvas._scannedNotes.forEach(n => { n.canvasOffsetTop = canvasOffsetTop; });
                return canvas._scannedNotes;
            }

            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];
            this.clef = prof.clef || 'treble';

            // 1. MOTOR VECTORIAL NATIVO DE MÁXIMA PRECISIÓN (PDFs con fuentes musicales Maestro/Opus/Petrucci/Engraver y gráficos vectoriales)
            if ((canvas._textContent || canvas._opList) && canvas._viewport) {
                const vectorNotes = this.extractNotesFromPdfText(canvas, pageNum, instrumentKey, canvasOffsetTop);
                if (vectorNotes && vectorNotes.length > 0) {
                    canvas._scannedNotes = vectorNotes;
                    canvas._scannedInstrument = instrumentKey;
                    this.notes = vectorNotes;
                    return vectorNotes;
                }
            }

            // 2. MOTOR OMR RASTER CON SUPER-MUESTREO ADAPTATIVO (para partituras escaneadas o de baja resolución)
            let workCanvas = canvas;
            let omrScale = 1.0;
            if (canvas.width > 0 && canvas.width < 1350 && typeof document !== 'undefined') {
                omrScale = Math.min(2.5, Math.max(1.6, 1700 / canvas.width));
                const upCanvas = document.createElement('canvas');
                upCanvas.width = Math.round(canvas.width * omrScale);
                upCanvas.height = Math.round(canvas.height * omrScale);
                const upCtx = upCanvas.getContext('2d');
                upCtx.imageSmoothingEnabled = true;
                upCtx.imageSmoothingQuality = 'high';
                upCtx.drawImage(canvas, 0, 0, upCanvas.width, upCanvas.height);
                workCanvas = upCanvas;
            }

            const ctx = workCanvas.getContext('2d');
            const width = workCanvas.width;
            const height = workCanvas.height;

            if (width === 0 || height === 0) return [];

            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;
            const inkThreshold = this.computeAdaptiveInkThreshold(data, width, height);

            // Detección de pentagramas con umbral adaptativo
            const staffSystems = this.detectStaves(data, width, height, canvasOffsetTop, inkThreshold);
            this.staves = staffSystems;

            const detectedNotes = [];

            // Escaneo detallado de cada pentagrama en estricto orden vertical y horizontal
            staffSystems.forEach((staff, staffIdx) => {
                staff.canvas = canvas;
                staff.inkThreshold = inkThreshold;
                // a) Detectar líneas divisorias de compás (Barlines)
                const barlines = this.detectBarlines(data, width, height, staff);
                
                // b) Escanear notas con figuras, plicas, corchetes y puntillos
                const notesInStaff = this.scanStaffNotes(data, width, height, staff, staffIdx, pageNum, barlines);
                
                // c) Detectar ligaduras (Ties / Slurs) entre notas adyacentes
                this.detectTiesAndSlurs(data, width, height, notesInStaff, staff);

                // d) Normalización métrica por compases
                this.normalizeMeasuresAndRhythms(notesInStaff, barlines, staff);

                // Si se hizo super-muestreo para baja resolución, reescalar coordenadas X/Y al canvas original y asegurar orden secuencial X
                if (omrScale !== 1.0) {
                    notesInStaff.forEach(n => {
                        n.x = Math.round(n.x / omrScale);
                        n.y = Math.round(n.y / omrScale);
                        n.w = Math.max(14, Math.round((n.w || 20) / omrScale));
                        n.h = Math.max(16, Math.round((n.h || 20) / omrScale));
                    });
                }
                notesInStaff.sort((a, b) => (a.x || 0) - (b.x || 0));

                detectedNotes.push(...notesInStaff);
            });

            // Re-indexar secuencialmente todas las notas detectadas en la página
            detectedNotes.forEach((n, idx) => { n.noteIndex = idx; });

            const warningsList = [
                `Pág. ${pageNum}: Partitura sin fuente musical vectorial; procesada mediante visión artificial OMR con realce de resolución (${omrScale > 1 ? omrScale.toFixed(1) + 'x' : '1x'}).`
            ];
            if (detectedNotes.length === 0) {
                warningsList.push(`Pág. ${pageNum}: Baja nitidez o página de portada/texto sin cabezas de nota detectables.`);
            }

            canvas._scanDiagnostics = {
                page: pageNum,
                engine: 'raster-omr',
                confidence: detectedNotes.length > 0 ? 68 : 35,
                stats: {
                    staves: staffSystems.length,
                    notes: detectedNotes.length,
                    chords: detectedNotes.filter(n => n.isChord).length,
                    multiRests: detectedNotes.filter(n => n.isMultiRest).length,
                    graceAndOrnaments: detectedNotes.reduce((acc, n) => acc + (n.graceNotes ? n.graceNotes.length : 0), 0),
                    tiesAndSlurs: detectedNotes.filter(n => n.tiedToNext || n.legato).length,
                    hairpins: 0
                },
                warnings: warningsList
            };

            if (detectedNotes.length > 0) {
                canvas._scannedNotes = detectedNotes;
                canvas._scannedInstrument = instrumentKey;
            }
            this.notes = detectedNotes;
            return detectedNotes;
        }

        // Clasifica glifos musicales estrictamente por código de carácter Maestro/Sonata (NUNCA por widthRatio para evitar confundir sostenidos '#', corchetes 'J'/'j' o silencios '‰'/'Œ' con cabezas de nota)
        classifyMusicGlyph(c, fontName = '') {
            if (!c) return null;
            const code = c.charCodeAt(0);

            // Descartar explícitamente alteraciones ('#', 'b', 'n', 'N'=(becuadro), 'A'=(bemol)), claves ('&', '?', 'ã'), corchetes ('J','j','R','r','K','k'), articulaciones, dinámicas, trémolos ('æ'), repetición ('‘'), 8va ('√') y números
            if (
                c === '#' || c === 'b' || c === 'n' || c === 'N' || c === 'A' ||
                c === '&' || c === '?' || c === 'ã' || c === 'B' ||
                c === 'J' || c === 'j' || c === 'R' || c === 'r' || c === 'K' || c === 'k' ||
                c === '.' || c === '-' || c === '>' || c === '~' || c === 'U' || c === 'Ÿ' ||
                c === 'f' || c === 'p' || c === 'F' || c === 'P' || c === 'ƒ' || c === 'Í' || c === 'â' || c === 'ä' ||
                c === 'æ' || c === '‘' || c === '«' || c === '√' || c === '(' || c === ')' ||
                (code >= 48 && code <= 57)
            ) {
                return null;
            }

            // 1. Cabeza de blanca (Half notehead: '˙' MacRoman 250 / U+02D9, 'ú' WinAnsi 250, PUA U+F0FA)
            if (c === '˙' || c === 'ú' || c === '\uF0FA' || code === 0xF0FA) {
                return { dur: 2.0, type: 'note', head: 'half', symbol: '𝅗𝅥', figName: 'Blanca', isRest: false };
            }

            // 2. Cabeza de negra / corchea / semicorchea (Black notehead: 'œ' MacRoman 207 / U+0153, 'Ï' WinAnsi 207, PUA U+F0CF, o 'x'/'X' en percusión)
            if (c === 'œ' || c === 'Ï' || c === '\uF0CF' || code === 0xF0CF || ((c === 'x' || c === 'X') && fontName && fontName.toLowerCase().includes('perc'))) {
                return { dur: 1.0, type: 'note', head: 'black', symbol: '♩', figName: 'Negra', isRest: false };
            }

            // 3. Redonda (Whole note: 'w', 'W', PUA U+F077)
            if (c === 'w' || c === 'W' || c === '\uF077' || code === 0xF077) {
                return { dur: 4.0, type: 'note', head: 'whole', symbol: '𝅝', figName: 'Redonda', isRest: false };
            }

            // 4. Silencio de corchea (Eighth rest: '‰' MacRoman 228 / U+2030, PUA U+F0E4)
            if (c === '‰' || c === '\uF0E4' || code === 0xF0E4) {
                return { dur: 0.5, type: 'rest', head: 'rest8', symbol: '𝄾', figName: 'Silencio de corchea', isRest: true };
            }

            // 5. Silencio de negra (Quarter rest: 'Œ' MacRoman 206 / U+0152, 'Î' WinAnsi 206, PUA U+F0CE)
            if (c === 'Œ' || c === 'Î' || c === '\uF0CE' || code === 0xF0CE) {
                return { dur: 1.0, type: 'rest', head: 'rest4', symbol: '𝄽', figName: 'Silencio de negra', isRest: true };
            }

            // 6. Silencio de blanca / compás (Half/Whole rest: '∑' U+2211, 'Ó' MacRoman 238, '”' MacRoman 211, PUA U+F0EE / U+F0D3)
            if (c === '∑' || c === 'Ó' || c === '”' || c === '\uF0EE' || c === '\uF0D3' || code === 0xF0EE || code === 0xF0D3) {
                return { dur: 2.0, type: 'rest', head: 'rest2', symbol: '𝄼', figName: 'Silencio de blanca', isRest: true, isWholeRestGlyph: (c === '∑' || code === 0xF0EE) };
            }

            // 7. Silencio de semicorchea ('≈' MacRoman 197 / U+2248, 'Å' WinAnsi 197, PUA U+F0C5)
            if (c === '≈' || c === 'Å' || c === '\uF0C5' || code === 0xF0C5) {
                return { dur: 0.25, type: 'rest', head: 'rest16', symbol: '𝄿', figName: 'Silencio de semicorchea', isRest: true };
            }

            return null;
        }

        // Extrae gráficos vectoriales exactos desde PDF.js OperatorList (Pentagramas de 5 líneas, Barras de compás, Beams rítmicos, Curvas Bézier de ligaduras, Barras H de silencios multicompás y Reguladores < > de crescendo/disminuyendo)
        extractVectorGraphicsFromOpList(opList, viewport, canvasWidth) {
            const result = { staves: [], beams: [], barlines: [], curves: [], multiRestBars: [], hairpins: [] };
            if (!opList || !opList.fnArray || !opList.argsArray || !viewport) return result;

            const fnArray = opList.fnArray;
            const argsArray = opList.argsArray;
            const scale = viewport.scale || 1;

            const multiply = (m1, m2) => [
                m1[0] * m2[0] + m1[2] * m2[1],
                m1[1] * m2[0] + m1[3] * m2[1],
                m1[0] * m2[2] + m1[2] * m2[3],
                m1[1] * m2[2] + m1[3] * m2[3],
                m1[0] * m2[4] + m1[2] * m2[5] + m1[4],
                m1[1] * m2[4] + m1[3] * m2[5] + m1[5]
            ];

            let ctm = [1, 0, 0, 1, 0, 0];
            const stack = [];
            let pendingSubpaths = [];

            const toCanvasPt = (x, y) => {
                const ux = ctm[0] * x + ctm[2] * y + ctm[4];
                const uy = ctm[1] * x + ctm[3] * y + ctm[5];
                return viewport.convertToViewportPoint(ux, uy);
            };

            const rawHorizLines = [];
            const rawVertLines = [];
            const rawMultiRestBars = [];
            const rawDiagLines = [];

            for (let i = 0; i < fnArray.length; i++) {
                const fn = fnArray[i];
                const args = argsArray[i];

                if (fn === 10) { // OPS.save
                    stack.push([...ctm]);
                } else if (fn === 11) { // OPS.restore
                    if (stack.length > 0) ctm = stack.pop();
                } else if (fn === 12 && args && args.length >= 6) { // OPS.transform
                    ctm = multiply(ctm, args);
                } else if (fn === 91 && args && args.length >= 2) { // OPS.constructPath
                    const ops = args[0];
                    const coords = args[1];
                    let k = 0;
                    let currSub = null;

                    for (let j = 0; j < ops.length; j++) {
                        const op = ops[j];
                        if (op === 13) { // moveTo
                            if (currSub && currSub.pts.length > 1) pendingSubpaths.push(currSub);
                            currSub = { pts: [toCanvasPt(coords[k++], coords[k++])], hasCurve: false };
                        } else if (op === 14) { // lineTo
                            const pt = toCanvasPt(coords[k++], coords[k++]);
                            if (!currSub) currSub = { pts: [pt], hasCurve: false };
                            else currSub.pts.push(pt);
                        } else if (op === 15) { // curveTo
                            const p1 = toCanvasPt(coords[k++], coords[k++]);
                            const p2 = toCanvasPt(coords[k++], coords[k++]);
                            const p3 = toCanvasPt(coords[k++], coords[k++]);
                            if (!currSub) currSub = { pts: [p1, p2, p3], hasCurve: true };
                            else {
                                currSub.hasCurve = true;
                                currSub.pts.push(p1, p2, p3);
                            }
                        } else if (op === 16 || op === 17) { // curveTo2 / curveTo3
                            const p1 = toCanvasPt(coords[k++], coords[k++]);
                            const p2 = toCanvasPt(coords[k++], coords[k++]);
                            if (!currSub) currSub = { pts: [p1, p2], hasCurve: true };
                            else {
                                currSub.hasCurve = true;
                                currSub.pts.push(p1, p2);
                            }
                        } else if (op === 19) { // rectangle
                            if (currSub && currSub.pts.length > 1) pendingSubpaths.push(currSub);
                            const rx = coords[k++], ry = coords[k++], rw = coords[k++], rh = coords[k++];
                            const pA = toCanvasPt(rx, ry);
                            const pB = toCanvasPt(rx + rw, ry);
                            const pC = toCanvasPt(rx + rw, ry + rh);
                            const pD = toCanvasPt(rx, ry + rh);
                            pendingSubpaths.push({ pts: [pA, pB, pC, pD], hasCurve: false });
                            currSub = null;
                        }
                    }
                    if (currSub && currSub.pts.length > 1) pendingSubpaths.push(currSub);
                } else if (fn === 20 || fn === 21 || fn === 22 || fn === 23 || fn === 24 || fn === 25) {
                    const isStroke = (fn === 20 || fn === 21 || fn === 24 || fn === 25);
                    const isFill = (fn === 22 || fn === 23 || fn === 24 || fn === 25);

                    for (const sub of pendingSubpaths) {
                        const pts = sub.pts;
                        if (pts.length < 2) continue;
                        let x0 = Infinity, x1 = -Infinity, y0 = Infinity, y1 = -Infinity;
                        for (const p of pts) {
                            if (p[0] < x0) x0 = p[0];
                            if (p[0] > x1) x1 = p[0];
                            if (p[1] < y0) y0 = p[1];
                            if (p[1] > y1) y1 = p[1];
                        }
                        const w = x1 - x0;
                        const h = y1 - y0;

                        if (sub.hasCurve) {
                            // Curva Bézier de ligadura (Tie / Slur)
                            if (w >= 4.0 * scale && w <= 260 * scale && h >= 1.2 * scale && h <= 45 * scale) {
                                result.curves.push({ x0, x1, y0, y1, w, h });
                            }
                        } else if (isStroke && pts.length === 3) {
                            // Regulador de 3 puntos (Hairpin: '<' crescendo abriendo o '>' disminuyendo cerrando)
                            if (w >= 6.0 * scale && w <= 360 * scale && h >= 2.0 * scale && h <= 16.5 * scale) {
                                const p0 = pts[0], p1 = pts[1], p2 = pts[2];
                                if (Math.abs(p0[0] - p2[0]) < 3.5 * scale && Math.abs(p0[1] - p2[1]) >= 2.0 * scale) {
                                    const hType = (p1[0] < (p0[0] + p2[0]) * 0.5) ? 'cresc' : 'dim';
                                    const ym = (y0 + y1) * 0.5;
                                    result.hairpins.push({ type: hType, x0, x1, y0, y1, y: ym, ym, w, h });
                                }
                            }
                        } else if (isStroke && pts.length === 2) {
                            if (w > canvasWidth * 0.25 && h < 3.0 * scale) {
                                rawHorizLines.push({ x0, x1, y: (y0 + y1) * 0.5 });
                            } else if (w >= 24 * scale && w <= 165 * scale && h < 2.2 * scale) {
                                // Barra horizontal de silencio multicompás (H-bar sobre 3ª línea)
                                rawMultiRestBars.push({ x0, x1, xm: (x0 + x1) * 0.5, y: (y0 + y1) * 0.5, w, h });
                            } else if (w < 2.8 * scale && h >= 12 * scale && h <= 30 * scale) {
                                rawVertLines.push({ x: (x0 + x1) * 0.5, y0, y1, h });
                            } else if (w >= 6.0 * scale && w <= 340 * scale && h >= 0.8 * scale && h <= 14.0 * scale) {
                                rawDiagLines.push({ p0: pts[0], p1: pts[1], x0, x1, y0, y1, ym: (y0 + y1) * 0.5, w, h });
                            }
                        } else if (isFill && !sub.hasCurve && pts.length >= 4 && pts.length <= 6) {
                            // Barras de corchea / semicorchea (Beams: paralelogramos rellenos)
                            if (w >= 2.5 * scale && w <= 240 * scale && h >= 1.1 * scale && h <= 10.5 * scale) {
                                result.beams.push({ x0, x1, y0, y1, ym: (y0 + y1) * 0.5, w, h });
                            }
                        }
                    }
                    pendingSubpaths = [];
                } else if (fn === 26) { // OPS.endPath
                    pendingSubpaths = [];
                }
            }

            // Emparejar líneas diagonales de 2 puntos que formen un regulador '<' o '>'
            for (let i = 0; i < rawDiagLines.length; i++) {
                for (let j = i + 1; j < rawDiagLines.length; j++) {
                    const l1 = rawDiagLines[i], l2 = rawDiagLines[j];
                    if (Math.abs(l1.x0 - l2.x0) < 3.5 * scale && Math.abs(l1.x1 - l2.x1) < 3.5 * scale && Math.abs(l1.ym - l2.ym) <= 10.0 * scale) {
                        const l1LeftY = l1.p0[0] <= l1.p1[0] ? l1.p0[1] : l1.p1[1];
                        const l1RightY = l1.p0[0] <= l1.p1[0] ? l1.p1[1] : l1.p0[1];
                        const l2LeftY = l2.p0[0] <= l2.p1[0] ? l2.p0[1] : l2.p1[1];
                        const l2RightY = l2.p0[0] <= l2.p1[0] ? l2.p1[1] : l2.p0[1];
                        const gapLeft = Math.abs(l1LeftY - l2LeftY);
                        const gapRight = Math.abs(l1RightY - l2RightY);
                        if (gapLeft < 2.2 * scale && gapRight >= 2.5 * scale) {
                            const ym = (l1.ym + l2.ym) * 0.5;
                            result.hairpins.push({
                                type: 'cresc',
                                x0: Math.min(l1.x0, l2.x0),
                                x1: Math.max(l1.x1, l2.x1),
                                y0: Math.min(l1.y0, l2.y0),
                                y1: Math.max(l1.y1, l2.y1),
                                y: ym,
                                ym: ym,
                                w: Math.max(l1.w, l2.w),
                                h: Math.max(gapLeft, gapRight)
                            });
                        } else if (gapRight < 2.2 * scale && gapLeft >= 2.5 * scale) {
                            const ym = (l1.ym + l2.ym) * 0.5;
                            result.hairpins.push({
                                type: 'dim',
                                x0: Math.min(l1.x0, l2.x0),
                                x1: Math.max(l1.x1, l2.x1),
                                y0: Math.min(l1.y0, l2.y0),
                                y1: Math.max(l1.y1, l2.y1),
                                y: ym,
                                ym: ym,
                                w: Math.max(l1.w, l2.w),
                                h: Math.max(gapLeft, gapRight)
                            });
                        }
                    }
                }
            }

            // Deduplicar barras H de silencios multicompás
            rawMultiRestBars.sort((a, b) => a.x0 - b.x0);
            for (const mb of rawMultiRestBars) {
                const dup = result.multiRestBars.some(u =>
                    Math.abs(mb.x0 - u.x0) < 3.0 * scale &&
                    Math.abs(mb.x1 - u.x1) < 3.0 * scale &&
                    Math.abs(mb.y - u.y) < 3.0 * scale
                );
                if (!dup) result.multiRestBars.push(mb);
            }

            // Agrupar líneas horizontales en pentagramas de 5 líneas
            rawHorizLines.sort((a, b) => a.y - b.y);
            const uniqueLines = [];
            for (const hl of rawHorizLines) {
                if (uniqueLines.length === 0 || Math.abs(hl.y - uniqueLines[uniqueLines.length - 1].y) > 1.5 * scale) {
                    uniqueLines.push(hl);
                }
            }

            let group = [];
            for (const hl of uniqueLines) {
                if (group.length === 0) {
                    group.push(hl);
                } else {
                    const dy = hl.y - group[group.length - 1].y;
                    if (dy >= 2.5 * scale && dy <= 8.0 * scale) {
                        group.push(hl);
                        if (group.length === 5) {
                            const ys = group.map(g => g.y);
                            const sp = (ys[4] - ys[0]) / 4.0;
                            result.staves.push({
                                lines: ys, // [line5 (top), line4, line3, line2, line1 (bottom)]
                                top: ys[0],
                                bottom: ys[4],
                                sp: sp,
                                x0: group[0].x0,
                                x1: group[0].x1
                            });
                            group = [];
                        }
                    } else {
                        group = [hl];
                    }
                }
            }

            // Deduplicar curvas de ligadura (borde superior e inferior del mismo arco Bézier)
            result.curves.sort((a, b) => a.x0 - b.x0);
            const dedupCurves = [];
            for (const c of result.curves) {
                const dup = dedupCurves.some( u =>
                    Math.abs(c.x0 - u.x0) < 3.5 * scale &&
                    Math.abs(c.x1 - u.x1) < 3.5 * scale &&
                    Math.abs(c.y0 - u.y0) < 4.5 * scale
                );
                if (!dup) dedupCurves.push(c);
            }
            result.curves = dedupCurves;
            result.barlines = rawVertLines;

            return result;
        }

        // Extrae glifos individuales directamente de OPS.showText + OPS.setTextMatrix si están disponibles
        extractTextGlyphsFromOpList(opList, viewport) {
            if (!opList || !opList.fnArray || !opList.argsArray || !viewport) return [];
            const fnArray = opList.fnArray;
            const argsArray = opList.argsArray;
            const OPS = (window.pdfjsLib && window.pdfjsLib.OPS) ? window.pdfjsLib.OPS : {};
            const OP_SAVE = OPS.save || 10;
            const OP_RESTORE = OPS.restore || 11;
            const OP_TRANSFORM = OPS.transform || 12;
            const OP_BEGIN_TEXT = OPS.beginText || 31;
            const OP_SET_FONT = OPS.setFont || 37;
            const OP_MOVE_TEXT = OPS.moveText || 40;
            const OP_SET_LEADING_MOVE_TEXT = OPS.setLeadingMoveText || 41;
            const OP_SET_TEXT_MATRIX = OPS.setTextMatrix || 42;
            const OP_SHOW_TEXT = OPS.showText || 44;
            const OP_SHOW_SPACED_TEXT = OPS.showSpacedText || 45;

            const multiply = (m1, m2) => [
                m1[0] * m2[0] + m1[2] * m2[1],
                m1[1] * m2[0] + m1[3] * m2[1],
                m1[0] * m2[2] + m1[2] * m2[3],
                m1[1] * m2[2] + m1[3] * m2[3],
                m1[0] * m2[4] + m1[2] * m2[5] + m1[4],
                m1[1] * m2[4] + m1[3] * m2[5] + m1[5]
            ];

            let ctm = [1, 0, 0, 1, 0, 0];
            const stack = [];
            let tm = [1, 0, 0, 1, 0, 0];
            let tlm = [1, 0, 0, 1, 0, 0];
            let fontSize = 1;
            let fontName = '';
            const items = [];

            for (let i = 0; i < fnArray.length; i++) {
                const fn = fnArray[i];
                const args = argsArray[i];
                if (fn === OP_SAVE) {
                    stack.push([...ctm]);
                } else if (fn === OP_RESTORE) {
                    if (stack.length > 0) ctm = stack.pop();
                } else if (fn === OP_TRANSFORM && args && args.length >= 6) {
                    ctm = multiply(ctm, args);
                } else if (fn === OP_BEGIN_TEXT) {
                    tm = [1, 0, 0, 1, 0, 0];
                    tlm = [1, 0, 0, 1, 0, 0];
                } else if (fn === OP_SET_FONT && args && args.length >= 2) {
                    fontName = String(args[0] || '');
                    fontSize = args[1] || 1;
                } else if ((fn === OP_MOVE_TEXT || fn === OP_SET_LEADING_MOVE_TEXT) && args && args.length >= 2) {
                    tlm = multiply(tlm, [1, 0, 0, 1, args[0], args[1]]);
                    tm = [...tlm];
                } else if (fn === OP_SET_TEXT_MATRIX && args && args.length >= 6) {
                    tm = [...args];
                    tlm = [...args];
                } else if ((fn === OP_SHOW_TEXT || fn === OP_SHOW_SPACED_TEXT) && args && args[0]) {
                    const glyphs = args[0];
                    const fullM = multiply(ctm, tm);
                    const scaleSize = Math.hypot(fullM[0], fullM[1]) * Math.abs(fontSize || 1);
                    let offsetEm = 0;

                    for (let gIdx = 0; gIdx < glyphs.length; gIdx++) {
                        const g = glyphs[gIdx];
                        if (typeof g === 'number') {
                            offsetEm -= g / 1000.0;
                            continue;
                        }
                        if (!g) continue;
                        const ch = g.unicode || g.fontChar || '';
                        const wEm = (typeof g.width === 'number') ? (g.width / 1000.0) : 0;

                        if (ch && ch !== ' ') {
                            const gx = fullM[4] + offsetEm * scaleSize;
                            const gy = fullM[5];
                            const pt = viewport.convertToViewportPoint(gx, gy);
                            const gInfo = this.classifyMusicGlyph(ch, fontName);

                            items.push({
                                char: ch,
                                tx: gx,
                                ty: gy,
                                canvasX: pt[0],
                                canvasY: pt[1],
                                fontSize: scaleSize,
                                fontName: fontName,
                                widthRatio: wEm,
                                isMusicGlyph: !!gInfo,
                                glyphInfo: gInfo
                            });
                        }
                        offsetEm += wEm;
                    }
                }
            }

            return items;
        }

        // Extractor Vectorial Nativo de Notas, Ritmos, Ligaduras, Reguladores, Mordentes, Acordes y Silencios Multicompás desde PDF.js
        extractNotesFromPdfText(canvas, pageNum = 1, instrumentKey = 'clarinet', canvasOffsetTop = 0) {
            const textContent = canvas._textContent;
            const opList = canvas._opList;
            const viewport = canvas._viewport;
            if (!viewport) return null;

            const scale = viewport.scale || 1;
            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];
            const defaultClef = prof.clef || 'treble';

            // Inicializar diagnóstico de lectura para perfeccionar el intérprete
            const diagnostics = {
                page: pageNum,
                engine: 'vector-pdf',
                confidence: 96,
                warnings: [],
                stats: {
                    staves: 0,
                    notes: 0,
                    chords: 0,
                    multiRests: 0,
                    graceAndOrnaments: 0,
                    tiesAndSlurs: 0,
                    hairpins: 0
                }
            };

            // 1. Extraer pentagramas de 5 líneas, barras de compás, beams, silencios multicompás, reguladores (< >) y ligaduras Bézier desde opList
            const vec = this.extractVectorGraphicsFromOpList(opList, viewport, canvas.width);

            // 2. Extraer glifos musicales desagrupados
            let rawItems = [];
            if (textContent && textContent.items && textContent.items.length > 0) {
                for (let i = 0; i < textContent.items.length; i++) {
                    const item = textContent.items[i];
                    const str = item.str || '';
                    if (!str || !item.transform) continue;

                    const tx = item.transform[4];
                    const ty = item.transform[5];
                    const fontSize = Math.hypot(item.transform[0], item.transform[1]) || 17.0;
                    const itemW = item.width || 0;

                    for (let chIdx = 0; chIdx < str.length; chIdx++) {
                        const c = str[chIdx];
                        if (c === ' ' || c === '\t' || c === '\n') continue;

                        const chTx = str.length > 1 ? tx + (chIdx / str.length) * itemW : tx;
                        const charWidthRatio = (itemW / Math.max(1, str.length)) / fontSize;
                        const pt = viewport.convertToViewportPoint(chTx, ty);
                        const gInfo = this.classifyMusicGlyph(c, item.fontName || '');

                        rawItems.push({
                            char: c,
                            tx: chTx,
                            ty: ty,
                            canvasX: pt[0],
                            canvasY: pt[1],
                            fontSize: fontSize,
                            widthRatio: charWidthRatio,
                            fontName: item.fontName || '',
                            isMusicGlyph: !!gInfo,
                            glyphInfo: gInfo
                        });
                    }
                }
            }

            // Priorizar siempre opGlyphs (extraídos directamente de OPS.showText + OPS.setTextMatrix) porque conservan la coordenada Tm y tamaño de fuente exacto de cada glifo (imprescindible para mordentes/notas de adorno reducidas)
            const opGlyphs = this.extractTextGlyphsFromOpList(opList, viewport);
            const opMusicCount = opGlyphs.filter(it => it.isMusicGlyph).length;
            if (opMusicCount >= 2) {
                rawItems = opGlyphs;
            }

            const noteAndRestItems = rawItems.filter(it => it.isMusicGlyph);
            if (noteAndRestItems.length < 2 && (!vec.multiRestBars || vec.multiRestBars.length === 0)) {
                return null;
            }

            // 3. Detectar metadatos en la cabecera de la página (Tempo BPM e Instrumento de la particella)
            const firstStaffTopY = vec.staves.length > 0 ? vec.staves[0].top : (canvas.height * 0.25);
            const headerChars = rawItems
                .filter(it => it.canvasY < firstStaffTopY - 6 * scale && !it.isMusicGlyph)
                .sort((a, b) => (Math.abs(a.canvasY - b.canvasY) < 5 * scale ? a.canvasX - b.canvasX : a.canvasY - b.canvasY));
            const headerText = headerChars.map(it => it.char).join('');
            const bpmMatch = headerText.match(/=\s*(\d{2,3})/);
            if (bpmMatch) {
                const parsedBpm = parseInt(bpmMatch[1], 10);
                if (parsedBpm >= 40 && parsedBpm <= 220) {
                    canvas._detectedBpm = parsedBpm;
                }
            }

            // 4. Determinar sistemas de pentagramas (usar pentagramas exactos de 5 líneas de opList, o agrupar por Y si no hay opList)
            let staffSystems = vec.staves;
            if (!staffSystems || staffSystems.length === 0) {
                diagnostics.confidence -= 15;
                diagnostics.warnings.push(`Pág. ${pageNum}: No se encontraron líneas vectoriales de pentagrama; se estimaron las alturas por agrupación vertical.`);
                const staffRowThreshold = scale * 22;
                const staffRows = [];
                const sortedByY = [...noteAndRestItems].sort((a, b) => a.canvasY - b.canvasY);

                for (const item of sortedByY) {
                    let matchedRow = null;
                    for (const row of staffRows) {
                        if (Math.abs(item.canvasY - row.meanY) <= staffRowThreshold) {
                            matchedRow = row;
                            break;
                        }
                    }
                    if (matchedRow) {
                        matchedRow.items.push(item);
                        matchedRow.meanY = matchedRow.items.reduce((acc, curr) => acc + curr.canvasY, 0) / matchedRow.items.length;
                    } else {
                        staffRows.push({ meanY: item.canvasY, items: [item] });
                    }
                }
                staffRows.sort((a, b) => a.meanY - b.meanY);
                const sp = scale * 4.25;
                staffSystems = staffRows.map(r => {
                    const l3 = r.meanY;
                    return {
                        lines: [l3 - 2 * sp, l3 - sp, l3, l3 + sp, l3 + 2 * sp],
                        top: l3 - 2 * sp,
                        bottom: l3 + 2 * sp,
                        sp: sp,
                        x0: canvas.width * 0.05,
                        x1: canvas.width * 0.95
                    };
                });
            }

            // Asegurar orden vertical estricto de pentagramas de arriba abajo
            staffSystems.sort((a, b) => a.top - b.top);
            diagnostics.stats.staves = staffSystems.length;

            // Calcular tamaño de fuente modal de las notas principales de la página para distinguir mordentes / notas de adorno (que tienen tamaño reducido)
            const musicFontSizes = noteAndRestItems
                .filter(it => !it.glyphInfo.isRest && it.fontSize > 4)
                .map(it => it.fontSize)
                .sort((a, b) => a - b);
            const pageNormalNoteFontSize = musicFontSizes.length > 0
                ? musicFontSizes[Math.floor(musicFontSizes.length * 0.65)]
                : 17.5;

            const finalNotes = [];
            let globalNoteIndex = 0;
            let pageBeatsPerMeasure = 2.0; // Por defecto 2/4 (muy común en pasodobles/marchas), se actualiza al leer el compás del primer pentagrama
            let currentDynamicVel = 0.80;  // mf por defecto
            let currentDynamicLabel = 'mf';

            const unrecognizedSymbolsSet = new Set();
            let measureRhythmMismatches = 0;

            staffSystems.forEach((staff, staffIdx) => {
                const sp = staff.sp || (scale * 4.25);
                const diatonicStepPx = sp / 2.0;
                // Fronteras verticales sin solapamiento entre pentagramas adyacentes para evitar que notas agudas o números de un pentagrama se capturen en el anterior/siguiente
                const rowTop = staffIdx > 0
                    ? Math.max(staff.top - 5.5 * sp, (staffSystems[staffIdx - 1].bottom + staff.top) * 0.5)
                    : (staff.top - 5.5 * sp);
                const rowBottom = (staffIdx + 1 < staffSystems.length)
                    ? Math.min(staff.bottom + 5.5 * sp, (staff.bottom + staffSystems[staffIdx + 1].top) * 0.5)
                    : (staff.bottom + 5.5 * sp);

                const rowAllItems = rawItems.filter(it => it.canvasY >= rowTop && it.canvasY <= rowBottom);
                rowAllItems.sort((a, b) => a.canvasX - b.canvasX);

                const staffBeams = vec.beams.filter(b => b.ym >= staff.top - 6.5 * sp && b.ym <= staff.bottom + 6.5 * sp);
                const staffBarlines = vec.barlines
                    .filter(bl => Math.abs(bl.y0 - staff.top) < 1.8 * sp && Math.abs(bl.y1 - staff.bottom) < 1.8 * sp)
                    .map(bl => bl.x)
                    .sort((a, b) => a - b);

                // Unir dígitos adyacentes ('1' + '0' -> '10') en la misma línea base para leer silencios multicompás y números de compás
                const digitItems = rowAllItems
                    .filter(it => /^[0-9]$/.test(it.char))
                    .sort((a, b) => a.canvasX - b.canvasX);
                const numberTokens = [];
                for (const d of digitItems) {
                    const prevTok = numberTokens.length > 0 ? numberTokens[numberTokens.length - 1] : null;
                    if (prevTok &&
                        Math.abs(d.canvasY - prevTok.canvasY) <= 0.48 * sp &&
                        (d.canvasX - prevTok.x1) >= -0.2 * sp &&
                        (d.canvasX - prevTok.x1) <= 2.3 * sp) {
                        prevTok.text += d.char;
                        prevTok.value = parseInt(prevTok.text, 10);
                        prevTok.x1 = d.canvasX;
                        prevTok.canvasX = (prevTok.x0 + prevTok.x1) * 0.5;
                    } else {
                        numberTokens.push({
                            text: d.char,
                            value: parseInt(d.char, 10),
                            canvasX: d.canvasX,
                            canvasY: d.canvasY,
                            x0: d.canvasX,
                            x1: d.canvasX,
                            used: false
                        });
                    }
                }

                // Detectar clave del pentagrama ('&' = Sol en 2ª línea, '?' = Fa en 4ª línea)
                let rowClef = defaultClef;
                const clefItem = rowAllItems.find(it => (it.char === '&' || it.char === '?') && it.canvasX < staff.x0 + 12 * sp);
                if (clefItem) {
                    rowClef = (clefItem.char === '?') ? 'bass' : 'treble';
                }

                const referenceLineY = (rowClef === 'bass') ? staff.lines[1] : staff.lines[3];
                const notesInRow = rowAllItems.filter(it => it.isMusicGlyph);

                // Detectar barras de silencio multicompás (H-bars vectoriales sobre la 3ª línea central del pentagrama)
                const staffMultiRestBars = (vec.multiRestBars || []).filter(mb =>
                    Math.abs(mb.y - staff.lines[2]) <= 0.85 * sp &&
                    mb.x0 >= staff.x0 &&
                    mb.x1 <= staff.x1
                );

                // Si no hay ni glifos musicales ni barras de silencio multicompás en este pentagrama, salir
                if (notesInRow.length === 0 && staffMultiRestBars.length === 0) return;

                const firstEventX = Math.min(
                    notesInRow.length > 0 ? notesInRow[0].canvasX : Infinity,
                    staffMultiRestBars.length > 0 ? staffMultiRestBars[0].x0 : Infinity
                );
                const keySigMaxX = Math.min(firstEventX - 1.35 * sp, staff.x0 + 18 * sp);

                // Detectar indicación de compás (Time Signature: 2/4, 3/4, 4/4, 6/8, C, ¢) al inicio del pentagrama
                const timeSigDigits = digitItems.filter(d =>
                    d.canvasX > (clefItem ? clefItem.canvasX + 0.8 * sp : staff.x0 + 1.5 * sp) &&
                    d.canvasX < firstEventX - 0.5 * sp &&
                    d.canvasX < staff.x0 + 22 * sp &&
                    d.canvasY >= staff.top - 0.8 * sp &&
                    d.canvasY <= staff.bottom + 0.8 * sp
                );
                if (timeSigDigits.length >= 2) {
                    const sortedTS = [...timeSigDigits].sort((a, b) => a.canvasY - b.canvasY);
                    const topNum = parseInt(sortedTS[0].char, 10);
                    const botDen = parseInt(sortedTS[sortedTS.length - 1].char, 10);
                    if (topNum >= 1 && topNum <= 12 && (botDen === 2 || botDen === 4 || botDen === 8)) {
                        pageBeatsPerMeasure = topNum * (4.0 / botDen);
                        // Marcar estos dígitos como usados para no confundirlos con silencios
                        for (const tok of numberTokens) {
                            if (tok.canvasX <= sortedTS[sortedTS.length - 1].canvasX + 0.5 * sp &&
                                tok.canvasY >= staff.top - 0.8 * sp && tok.canvasY <= staff.bottom + 0.8 * sp) {
                                tok.used = true;
                            }
                        }
                    }
                } else {
                    const commonTimeChar = rowAllItems.find(it =>
                        (it.char === 'c' || it.char === 'C') &&
                        it.canvasX < firstEventX - 0.5 * sp &&
                        it.canvasX < staff.x0 + 20 * sp &&
                        Math.abs(it.canvasY - staff.lines[2]) <= 1.2 * sp
                    );
                    if (commonTimeChar) {
                        pageBeatsPerMeasure = (commonTimeChar.char === 'C') ? 2.0 : 4.0;
                    }
                }

                // Armadura: contar bemoles ('b'), sostenidos ('#') y becuadros ('n') al inicio del pentagrama
                const rawFlats = rowAllItems.filter(it => it.char === 'b' && it.canvasX < keySigMaxX).length;
                const rawSharps = rowAllItems.filter(it => it.char === '#' && it.canvasX < keySigMaxX).length;
                const rawNaturals = rowAllItems.filter(it => it.char === 'n' && it.canvasX < keySigMaxX).length;

                const keySigFlats = Math.max(0, rawFlats - rawNaturals);
                const keySigSharps = Math.max(0, rawSharps - rawNaturals);

                // Detectar octava alta ('√' = 8va)
                const ottavaItems = rowAllItems.filter(it => it.char === '√');

                // Detectar glifos desconocidos en la zona estricta del pentagrama para diagnóstico de lectura
                const knownAuxChars = new Set([
                    '&', '?', 'b', '#', 'n', 'N', 'A', 'a', '.', '>', 'J', 'j', 'R', 'r', 'K', 'k',
                    'p', 'P', 'F', 'f', 'ƒ', 'Í', 'z', 's', 'm', 'M', '~', 'Ÿ', '√', 'æ', '‘', '’',
                    'c', 'C', 'U', 'u', 'g', '-', '(', ')', '[', ']', '/', '%'
                ]);
                for (const it of rowAllItems) {
                    if (!it.isMusicGlyph &&
                        !/^[0-9A-Za-zÁÉÍÓÚáéíóúÑñ\s.,:;!¡?¿'"()-]+$/.test(it.char) &&
                        !knownAuxChars.has(it.char) &&
                        it.canvasY >= staff.top - 1.2 * sp &&
                        it.canvasY <= staff.bottom + 1.2 * sp) {
                        unrecognizedSymbolsSet.add(`${it.char} (código ${it.char.charCodeAt(0)})`);
                    }
                }

                // Construir eventos de Silencios Multicompás (H-bars con número encima/debajo)
                const rawStaffEvents = [];
                for (const mb of staffMultiRestBars) {
                    const mbXm = (typeof mb.xm === 'number' && !isNaN(mb.xm)) ? mb.xm : (mb.x0 + mb.x1) * 0.5;

                    // Verificar que no haya notas ejecutables dentro de la barra horizontal
                    const hasPlayableInside = notesInRow.some(n => !n.glyphInfo.isRest && n.canvasX >= mb.x0 - 0.8 * sp && n.canvasX <= mb.x1 + 0.8 * sp);
                    if (hasPlayableInside) continue;

                    // Buscar el número de compases centrado sobre la barra H-bar (ej. 8, 3, 9, 10, 6, 2)
                    const numTok = numberTokens.find(t =>
                        !t.used &&
                        t.value >= 1 && t.value <= 240 &&
                        t.canvasX >= mb.x0 - 0.8 * sp &&
                        t.canvasX <= mb.x1 + 0.8 * sp &&
                        t.canvasY >= staff.top - 3.3 * sp &&
                        t.canvasY <= staff.bottom + 2.5 * sp
                    );
                    if (!numTok) continue;
                    numTok.used = true;

                    const repeatMeasures = Math.max(1, numTok.value);
                    const totalRestBeats = repeatMeasures * pageBeatsPerMeasure;
                    let barIdx = 0;
                    while (barIdx < staffBarlines.length && staffBarlines[barIdx] < mbXm) barIdx++;

                    diagnostics.stats.multiRests++;

                    rawStaffEvents.push({
                        isMultiRestEvent: true,
                        x: Math.round(mbXm),
                        y: Math.round(staff.lines[2]),
                        w: Math.round(Math.max(sp * 3.0, mb.w)),
                        h: Math.round(sp * 2.4),
                        hbarX0: Math.round(mb.x0),
                        hbarX1: Math.round(mb.x1),
                        measureIndex: barIdx,
                        repeatMeasures: repeatMeasures,
                        beatsPerMeasure: pageBeatsPerMeasure,
                        duration: Math.round(totalRestBeats * 1000) / 1000,
                        name: repeatMeasures > 1 ? `Silencio (${repeatMeasures} compases)` : 'Silencio (1 compás)',
                        figure: repeatMeasures > 1 ? `Silencio de ${repeatMeasures} compases` : 'Silencio de compás',
                        symbol: repeatMeasures > 1 ? `𝄺×${repeatMeasures}` : '𝄻',
                        isRest: true,
                        clef: rowClef
                    });
                }

                // Seguimiento de alteraciones accidentales dentro de cada compás
                let currentBarIdx = 0;
                let measureAccidentals = {};

                notesInRow.forEach((noteItem, nIdx) => {
                    // Si este glifo de silencio ya está cubierto por un H-bar multicompás, omitirlo para no duplicar
                    if (noteItem.glyphInfo.isRest && rawStaffEvents.some(ev => ev.isMultiRestEvent && Math.abs(noteItem.canvasX - ev.x) <= ev.w * 0.65)) {
                        return;
                    }

                    const prevNote = nIdx > 0 ? notesInRow[nIdx - 1] : null;

                    while (currentBarIdx < staffBarlines.length && staffBarlines[currentBarIdx] < noteItem.canvasX) {
                        if (!prevNote || prevNote.canvasX < staffBarlines[currentBarIdx]) {
                            measureAccidentals = {};
                        }
                        currentBarIdx++;
                    }

                    const isRest = !!noteItem.glyphInfo.isRest;
                    const stepsFromRef = Math.round((referenceLineY - noteItem.canvasY) / diatonicStepPx);

                    // Detectar si es una nota de adorno / mordente (tamaño tipográfico más reducido que las notas normales)
                    const itemFontSize = noteItem.fontSize || pageNormalNoteFontSize;
                    const isGraceNote = !isRest && (itemFontSize < pageNormalNoteFontSize * 0.84);

                    // Alteración explícita inmediatamente anterior a esta nota (incluyendo alteraciones de cortesía 'N'=(♮), 'A'=(♭), 'a'=(♯))
                    const prevNoteX = (prevNote && Math.abs(prevNote.canvasX - noteItem.canvasX) > 0.6 * sp) ? prevNote.canvasX : keySigMaxX;
                    const lastBarX = currentBarIdx > 0 ? staffBarlines[currentBarIdx - 1] : 0;
                    const minAccX = Math.max(keySigMaxX, prevNoteX + 0.15 * sp, lastBarX);

                    const accidentalItem = !isRest ? rowAllItems.find(it =>
                        (it.char === 'b' || it.char === '#' || it.char === 'n' || it.char === 'N' || it.char === 'A' || it.char === 'a') &&
                        it.canvasX >= minAccX &&
                        it.canvasX < noteItem.canvasX &&
                        (noteItem.canvasX - it.canvasX) >= 0.25 * sp &&
                        (noteItem.canvasX - it.canvasX) <= 3.3 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 0.72 * sp
                    ) : null;

                    if (!isRest && accidentalItem) {
                        const normAcc = (accidentalItem.char === 'N') ? 'n'
                            : (accidentalItem.char === 'A') ? 'b'
                            : (accidentalItem.char === 'a') ? '#'
                            : accidentalItem.char;
                        measureAccidentals[stepsFromRef] = normAcc;
                    }
                    const activeAccidental = !isRest ? (measureAccidentals[stepsFromRef] || null) : null;

                    const hasAugmentationDot = rowAllItems.some(it =>
                        it.char === '.' &&
                        (it.canvasX - noteItem.canvasX) >= 0.95 * sp &&
                        (it.canvasX - noteItem.canvasX) <= 2.6 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 0.68 * sp
                    );

                    const hasStaccato = !isRest && rowAllItems.some(it =>
                        it.char === '.' &&
                        (it.canvasX - noteItem.canvasX) >= -0.35 * sp &&
                        (it.canvasX - noteItem.canvasX) <= 0.9 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) > 0.68 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 3.2 * sp
                    );

                    const hasAccent = !isRest && rowAllItems.some(it =>
                        it.char === '>' &&
                        Math.abs(it.canvasX - noteItem.canvasX) <= 1.2 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 4.0 * sp
                    );

                    // Detectar símbolos de mordente ('m', 'M', '~') o trino ('Ÿ') sobre la nota
                    const hasTrill = !isRest && rowAllItems.some(it =>
                        it.char === 'Ÿ' &&
                        Math.abs(it.canvasX - noteItem.canvasX) <= 1.8 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 5.2 * sp
                    );
                    const hasMordentSym = !isRest && !hasTrill && rowAllItems.some(it =>
                        (it.char === 'm' || it.char === 'M' || it.char === '~') &&
                        Math.abs(it.canvasX - noteItem.canvasX) <= 1.5 * sp &&
                        Math.abs(it.canvasY - noteItem.canvasY) <= 4.8 * sp
                    );

                    // Calcular altura tonal MIDI exacta
                    let midi = 67;
                    let noteName = 'Sol4';
                    if (!isRest) {
                        const pitchInfo = this.diatonicToMidi(stepsFromRef, keySigFlats, keySigSharps, activeAccidental, rowClef);
                        midi = pitchInfo.midi;
                        noteName = pitchInfo.name;
                        // Si está bajo indicación de 8va ('√'), subir una octava
                        if (ottavaItems.some(ot => noteItem.canvasX >= ot.canvasX - 0.5 * sp && noteItem.canvasX <= ot.canvasX + 18 * sp)) {
                            midi += 12;
                        }
                    } else {
                        noteName = 'Silencio';
                    }

                    // Calcular duración rítmica
                    let duration = noteItem.glyphInfo.dur;
                    let figureName = noteItem.glyphInfo.figName || 'Negra';
                    let figureSymbol = noteItem.glyphInfo.symbol || '♩';
                    let repeatMeasures = 1;

                    if (isRest) {
                        // Si es silencio de compás completo ('∑'), ajustar su duración a los tiempos reales del compás
                        if (noteItem.char === '∑') {
                            duration = pageBeatsPerMeasure;
                        }
                        // Comprobar si el silencio lleva un número indicando cuántas veces se repite (ej. silencio con número encima/debajo)
                        const restRepeatTok = numberTokens.find(t =>
                            !t.used &&
                            t.value >= 2 && t.value <= 240 &&
                            Math.abs(t.canvasX - noteItem.canvasX) <= 1.9 * sp &&
                            t.canvasY >= staff.top - 3.3 * sp &&
                            t.canvasY <= staff.bottom + 2.5 * sp &&
                            !staffBarlines.some(bx => Math.abs(bx - t.canvasX) <= 0.9 * sp)
                        );
                        if (restRepeatTok) {
                            restRepeatTok.used = true;
                            repeatMeasures = restRepeatTok.value;
                            const baseDur = (noteItem.char === '∑' || noteItem.char === 'Ó') ? pageBeatsPerMeasure : duration;
                            duration = repeatMeasures * baseDur;
                            noteName = `Silencio (${repeatMeasures} veces)`;
                            figureName = `Silencio repetido ×${repeatMeasures}`;
                            figureSymbol = `𝄺×${repeatMeasures}`;
                            diagnostics.stats.multiRests++;
                        }
                    } else if (noteItem.glyphInfo.head === 'black') {
                        const overlappingBeams = staffBeams.filter(b =>
                            b.x0 <= noteItem.canvasX + 1.45 * sp &&
                            b.x1 >= noteItem.canvasX - 0.35 * sp
                        );
                        const beamCount = overlappingBeams.length;

                        if (beamCount >= 3) {
                            duration = 0.125;
                            figureName = 'Fusa';
                            figureSymbol = '𝅘𝅥𝅰';
                        } else if (beamCount === 2) {
                            duration = 0.25;
                            figureName = 'Semicorchea';
                            figureSymbol = '𝅘𝅥𝅯';
                        } else if (beamCount === 1) {
                            duration = 0.5;
                            figureName = 'Corchea';
                            figureSymbol = '♪';
                        } else {
                            const flagItem = rowAllItems.find(it =>
                                (it.char === 'J' || it.char === 'j' || it.char === 'R' || it.char === 'r' || it.char === 'K' || it.char === 'k') &&
                                (it.canvasX - noteItem.canvasX) >= -0.5 * sp &&
                                (it.canvasX - noteItem.canvasX) <= 1.65 * sp &&
                                Math.abs(it.canvasY - noteItem.canvasY) <= 4.6 * sp
                            );
                            if (flagItem) {
                                if (flagItem.char === 'R' || flagItem.char === 'r') {
                                    duration = 0.25;
                                    figureName = 'Semicorchea';
                                    figureSymbol = '𝅘𝅥𝅯';
                                } else if (flagItem.char === 'K' || flagItem.char === 'k') {
                                    duration = 0.125;
                                    figureName = 'Fusa';
                                    figureSymbol = '𝅘𝅥𝅰';
                                } else {
                                    duration = 0.5;
                                    figureName = 'Corchea';
                                    figureSymbol = '♪';
                                }
                            } else {
                                duration = 1.0;
                                figureName = 'Negra';
                                figureSymbol = '♩';
                            }
                        }

                        // Tresillos ('3'), Cinquillos ('5'), Seisillos ('6') sobre grupos con barras
                        if (overlappingBeams.length > 0 && !isGraceNote) {
                            const grpX0 = Math.min(...overlappingBeams.map(b => b.x0));
                            const grpX1 = Math.max(...overlappingBeams.map(b => b.x1));
                            const tupletItem = rowAllItems.find(it =>
                                (it.char === '3' || it.char === '5' || it.char === '6') &&
                                it.canvasX >= grpX0 - 0.8 * sp &&
                                it.canvasX <= grpX1 + 0.8 * sp &&
                                Math.abs(it.canvasY - noteItem.canvasY) <= 6.2 * sp
                            );
                            if (tupletItem) {
                                if (tupletItem.char === '3') {
                                    duration *= (2.0 / 3.0);
                                    figureName += ' (tresillo)';
                                } else if (tupletItem.char === '6') {
                                    duration *= (4.0 / 6.0);
                                    figureName += ' (seisillo)';
                                } else if (tupletItem.char === '5') {
                                    duration *= (4.0 / 5.0);
                                    figureName += ' (cinquillo)';
                                }
                            }
                        }
                    }

                    if (hasAugmentationDot && !isGraceNote) {
                        duration *= 1.5;
                        figureName += ' con puntillo';
                        figureSymbol += '.';
                    }

                    rawStaffEvents.push({
                        x: Math.round(noteItem.canvasX),
                        y: Math.round(noteItem.canvasY),
                        w: Math.round(sp * 2.2),
                        h: Math.round(sp * 2.0),
                        measureIndex: currentBarIdx,
                        pitch: midi,
                        midi: midi,
                        frequency: 442 * Math.pow(2, (midi - 69) / 12),
                        name: noteName,
                        figure: isGraceNote ? 'Mordente / Adorno' : figureName,
                        duration: Math.round(duration * 1000) / 1000,
                        symbol: isGraceNote ? '♪ grace' : figureSymbol,
                        hasDot: hasAugmentationDot,
                        staccato: hasStaccato,
                        accent: hasAccent,
                        trill: hasTrill,
                        mordent: hasMordentSym ? 'upper' : null,
                        isGraceNote: isGraceNote,
                        isRest: isRest,
                        repeatMeasures: repeatMeasures,
                        beatsPerMeasure: pageBeatsPerMeasure,
                        accidental: activeAccidental ? (activeAccidental === 'b' ? '♭' : activeAccidental === '#' ? '♯' : '♮') : null,
                        clef: rowClef
                    });
                });

                // Ordenar todos los eventos del pentagrama estrictamente de izquierda a derecha (secuencial)
                rawStaffEvents.sort((a, b) => (a.x || 0) - (b.x || 0));

                // Detectar signo de repetición de compás completo ('‘' code 8216 = 𝄎)
                const repeatMeasureSigns = rowAllItems.filter(it => it.char === '‘' && it.canvasY >= staff.top - 1.5 * sp && it.canvasY <= staff.bottom + 1.5 * sp);
                for (const rms of repeatMeasureSigns) {
                    let barIdx = 0;
                    while (barIdx < staffBarlines.length && staffBarlines[barIdx] < rms.canvasX) barIdx++;
                    const prevMeasureEvents = rawStaffEvents.filter(ev => ev.measureIndex === barIdx - 1 && !ev.isMultiRestEvent);
                    if (prevMeasureEvents.length > 0) {
                        const prevMinX = prevMeasureEvents[0].x;
                        for (const pev of prevMeasureEvents) {
                            rawStaffEvents.push({
                                ...pev,
                                x: Math.round(rms.canvasX - 1.5 * sp + (pev.x - prevMinX) * 0.5),
                                measureIndex: barIdx
                            });
                        }
                    }
                }
                rawStaffEvents.sort((a, b) => (a.x || 0) - (b.x || 0));

                // 4A. Separar Mordentes / Notas de adorno (isGraceNote) para adjuntarlos a la nota principal siguiente (ejecutándose dentro de su mismo tiempo)
                const pendingGraceNotes = [];
                const mainTimelineEvents = [];

                for (const ev of rawStaffEvents) {
                    if (ev.isGraceNote) {
                        pendingGraceNotes.push(ev);
                        diagnostics.stats.graceAndOrnaments++;
                    } else {
                        if (!ev.isRest && pendingGraceNotes.length > 0) {
                            ev.graceNotes = [...pendingGraceNotes];
                            pendingGraceNotes.length = 0;
                        }
                        if (ev.trill || ev.mordent) {
                            diagnostics.stats.graceAndOrnaments++;
                        }
                        mainTimelineEvents.push(ev);
                    }
                }

                // 4B. Agrupar notas en la MISMA COLUMNA (mismo tiempo vertical) para tocar una según el instrumento o todas a la vez (nunca secuencialmente una detrás de otra)
                const columnGroups = [];
                for (const ev of mainTimelineEvents) {
                    if (ev.isMultiRestEvent) {
                        columnGroups.push([ev]);
                        continue;
                    }
                    const lastCol = columnGroups.length > 0 ? columnGroups[columnGroups.length - 1] : null;
                    if (lastCol && !lastCol[0].isMultiRestEvent) {
                        const dx = Math.abs(ev.x - lastCol[0].x);
                        const minDy = Math.min(...lastCol.map(item => Math.abs(ev.y - item.y)));
                        // Misma columna vertical (dx <= 0.68 * sp) o segunda armónica desplazada en la misma plica (dx <= 1.28 * sp y dy <= 0.68 * sp)
                        const isSameColumn = (dx <= 0.68 * sp) ||
                            (!ev.isRest && !lastCol[0].isRest && dx <= 1.28 * sp && minDy <= 0.68 * sp && Math.abs(ev.duration - lastCol[0].duration) < 0.01);

                        if (isSameColumn) {
                            lastCol.push(ev);
                            continue;
                        }
                    }
                    columnGroups.push([ev]);
                }

                // Convertir cada columna en un único evento temporal en finalNotes
                for (let cIdx = 0; cIdx < columnGroups.length; cIdx++) {
                    const col = columnGroups[cIdx];
                    const playableInCol = col.filter(item => !item.isRest);

                    // Si en la columna hay al menos una nota ejecutable y también silencios de otra voz simultánea, absorber los silencios para no parar la música
                    if (playableInCol.length === 0) {
                        // Columna solo de silencio
                        const restEv = col[0];
                        finalNotes.push({
                            id: `vnote_${pageNum}_${staffIdx}_${cIdx}`,
                            page: pageNum,
                            pageNum: pageNum,
                            staffIndex: staffIdx,
                            measure: restEv.measureIndex + 1,
                            measureIndex: restEv.measureIndex,
                            noteIndex: globalNoteIndex++,
                            canvas: canvas,
                            x: restEv.x,
                            y: restEv.y,
                            w: restEv.w,
                            h: restEv.h,
                            hbarX0: restEv.hbarX0,
                            hbarX1: restEv.hbarX1,
                            canvasOffsetTop: canvasOffsetTop,
                            pitch: restEv.pitch || 67,
                            midi: restEv.midi || 67,
                            frequency: 442,
                            name: restEv.name,
                            figure: restEv.figure,
                            duration: restEv.duration,
                            type: 'rest',
                            symbol: restEv.symbol,
                            isRest: true,
                            repeatMeasures: restEv.repeatMeasures || 1,
                            beatsPerMeasure: restEv.beatsPerMeasure || pageBeatsPerMeasure,
                            clef: rowClef
                        });
                    } else if (playableInCol.length === 1) {
                        // Nota individual (con posibles mordentes adjuntos)
                        const nEv = playableInCol[0];
                        const gracePrefix = (nEv.graceNotes && nEv.graceNotes.length > 0)
                            ? `[+${nEv.graceNotes.length} mord.] `
                            : (nEv.mordent ? '[𝄽 mord.] ' : (nEv.trill ? '[tr] ' : ''));

                        finalNotes.push({
                            id: `vnote_${pageNum}_${staffIdx}_${cIdx}`,
                            page: pageNum,
                            pageNum: pageNum,
                            staffIndex: staffIdx,
                            measure: nEv.measureIndex + 1,
                            measureIndex: nEv.measureIndex,
                            noteIndex: globalNoteIndex++,
                            canvas: canvas,
                            x: nEv.x,
                            y: nEv.y,
                            w: nEv.w,
                            h: nEv.h,
                            canvasOffsetTop: canvasOffsetTop,
                            pitch: nEv.pitch,
                            midi: nEv.midi,
                            frequency: nEv.frequency,
                            name: nEv.name,
                            figure: gracePrefix + nEv.figure,
                            duration: nEv.duration,
                            type: nEv.duration >= 2.0 ? 'half' : (nEv.duration >= 1.0 ? 'quarter' : (nEv.duration >= 0.5 ? 'eighth' : '16th')),
                            symbol: nEv.symbol,
                            hasDot: nEv.hasDot,
                            staccato: nEv.staccato,
                            accent: nEv.accent,
                            trill: nEv.trill,
                            mordent: nEv.mordent,
                            graceNotes: nEv.graceNotes || [],
                            tiedToNext: false,
                            tiedFromPrev: false,
                            legato: false,
                            isRest: false,
                            isChord: false,
                            chordNotes: [nEv],
                            accidental: nEv.accidental,
                            clef: rowClef
                        });
                    } else {
                        // 2 o más notas en la misma columna (Acorde / Divisi / Voces simultáneas)
                        // Ordenar de más aguda (arriba en pentagrama, mayor pitch) a más grave (abajo, menor pitch)
                        playableInCol.sort((a, b) => b.pitch - a.pitch);

                        // Deduplicar notas idénticas en unísono exacto
                        const uniquePlayable = [];
                        for (const pNote of playableInCol) {
                            if (!uniquePlayable.some(u => u.pitch === pNote.pitch)) {
                                uniquePlayable.push(pNote);
                            }
                        }

                        const topVoice = uniquePlayable[0];
                        const minY = Math.min(...uniquePlayable.map(n => n.y));
                        const maxY = Math.max(...uniquePlayable.map(n => n.y));
                        // El avance al siguiente tiempo lo marca la figura más corta de la columna
                        const colDuration = Math.min(...uniquePlayable.map(n => n.duration));
                        const combinedGrace = uniquePlayable.reduce((acc, n) => acc.concat(n.graceNotes || []), []);

                        if (uniquePlayable.length > 1) {
                            diagnostics.stats.chords++;
                        }

                        finalNotes.push({
                            id: `vnote_${pageNum}_${staffIdx}_${cIdx}`,
                            page: pageNum,
                            pageNum: pageNum,
                            staffIndex: staffIdx,
                            measure: topVoice.measureIndex + 1,
                            measureIndex: topVoice.measureIndex,
                            noteIndex: globalNoteIndex++,
                            canvas: canvas,
                            x: topVoice.x,
                            y: Math.round((minY + maxY) * 0.5),
                            w: Math.round(sp * 2.5),
                            h: Math.round(Math.max(sp * 2.2, (maxY - minY) + sp * 1.8)),
                            canvasOffsetTop: canvasOffsetTop,
                            pitch: topVoice.pitch,
                            midi: topVoice.midi,
                            frequency: topVoice.frequency,
                            name: uniquePlayable.map(n => n.name).join(' + '),
                            figure: uniquePlayable.length > 1
                                ? `${topVoice.figure} (${uniquePlayable.length} voces)`
                                : topVoice.figure,
                            duration: colDuration,
                            type: colDuration >= 2.0 ? 'half' : (colDuration >= 1.0 ? 'quarter' : (colDuration >= 0.5 ? 'eighth' : '16th')),
                            symbol: topVoice.symbol,
                            hasDot: topVoice.hasDot,
                            staccato: uniquePlayable.some(n => n.staccato),
                            accent: uniquePlayable.some(n => n.accent),
                            trill: uniquePlayable.some(n => n.trill),
                            mordent: uniquePlayable.find(n => n.mordent)?.mordent || null,
                            graceNotes: combinedGrace,
                            tiedToNext: false,
                            tiedFromPrev: false,
                            legato: false,
                            isRest: false,
                            isChord: uniquePlayable.length > 1,
                            chordNotes: uniquePlayable,
                            accidental: topVoice.accidental,
                            clef: rowClef
                        });
                    }
                }

                // 4C. Asociar Matices Dinámicos (p, mp, mf, f, ff, sfz) y Reguladores (< crescendo, > diminuendo) en este pentagrama
                const staffNotes = finalNotes.filter(n => n.staffIndex === staffIdx);
                const dynGlyphs = rowAllItems.filter(it =>
                    (it.char === 'p' || it.char === 'P' || it.char === 'F' || it.char === 'f' || it.char === 'ƒ' || it.char === 'Í') &&
                    it.canvasY >= staff.top - 4.5 * sp &&
                    it.canvasY <= staff.bottom + 5.2 * sp
                ).sort((a, b) => a.canvasX - b.canvasX);

                const dynVelMap = {
                    'p': { vel: 0.45, label: 'p' },
                    'P': { vel: 0.62, label: 'mp' },
                    'F': { vel: 0.78, label: 'mf' },
                    'f': { vel: 0.90, label: 'f' },
                    'ƒ': { vel: 0.98, label: 'ff' },
                    'Í': { vel: 0.98, label: 'sfz' }
                };

                for (const sNote of staffNotes) {
                    for (const dg of dynGlyphs) {
                        if (dg.canvasX <= sNote.x + 1.2 * sp) {
                            const dInfo = dynVelMap[dg.char];
                            if (dInfo) {
                                currentDynamicVel = dInfo.vel;
                                currentDynamicLabel = dInfo.label;
                            }
                        }
                    }
                    sNote.velocity = sNote.accent ? Math.min(1.0, currentDynamicVel + 0.14) : currentDynamicVel;
                    sNote.dynamic = currentDynamicLabel;
                }

                // Aplicar reguladores vectoriales (< crescendo abriendo, > disminuyendo cerrando)
                const staffHairpins = (vec.hairpins || []).filter(hp => {
                    const hy = (typeof hp.ym === 'number') ? hp.ym : hp.y;
                    return hy >= staff.top - 4.5 * sp && hy <= staff.bottom + 5.8 * sp;
                });

                for (const hp of staffHairpins) {
                    const notesInHairpin = staffNotes.filter(n =>
                        !n.isRest &&
                        n.x >= hp.x0 - 1.5 * sp &&
                        n.x <= hp.x1 + 1.5 * sp
                    );
                    if (notesInHairpin.length === 0) continue;
                    diagnostics.stats.hairpins++;

                    const baseStartVel = notesInHairpin[0].velocity || currentDynamicVel;
                    const targetEndVel = hp.type === 'cresc'
                        ? Math.min(0.98, baseStartVel + 0.28)
                        : Math.max(0.32, baseStartVel - 0.28);

                    const spanX = Math.max(1, hp.x1 - hp.x0);
                    notesInHairpin.forEach((n, idx) => {
                        const progress0 = Math.max(0, Math.min(1, (n.x - hp.x0) / spanX));
                        const progress1 = idx + 1 < notesInHairpin.length
                            ? Math.max(0, Math.min(1, (notesInHairpin[idx + 1].x - hp.x0) / spanX))
                            : 1.0;
                        const vStart = baseStartVel + (targetEndVel - baseStartVel) * progress0;
                        const vEnd = baseStartVel + (targetEndVel - baseStartVel) * progress1;

                        n.velocity = Math.round(vStart * 100) / 100;
                        n.endVelocity = Math.round(vEnd * 100) / 100;
                        n.hairpin = hp.type; // 'cresc' (<) o 'dim' (>)
                    });
                    currentDynamicVel = targetEndVel;
                }

                // 4D. Verificar cuadratura rítmica de los compases intermedios del pentagrama para diagnóstico de lectura
                if (staffBarlines.length >= 3) {
                    for (let mIdx = 1; mIdx < staffBarlines.length - 1; mIdx++) {
                        const mNotes = staffNotes.filter(n => n.measureIndex === mIdx && !n.isMultiRestEvent && (n.repeatMeasures || 1) === 1);
                        if (mNotes.length === 0) continue;
                        const sumBeats = mNotes.reduce((acc, n) => acc + (n.duration || 0), 0);
                        if (Math.abs(sumBeats - pageBeatsPerMeasure) > 0.35 && Math.abs(sumBeats - pageBeatsPerMeasure * 2) > 0.35) {
                            measureRhythmMismatches++;
                        }
                    }
                }
            });

            // 5. Asociar curvas Bézier vectoriales a Ligaduras de Prolongación (Ties) y Ligaduras de Expresión (Slurs)
            if (vec.curves && vec.curves.length > 0 && staffSystems.length > 0) {
                staffSystems.forEach((staff, sIdx) => {
                    const sp = staff.sp || (scale * 4.25);
                    const staffCurves = vec.curves.filter(c => c.y0 >= staff.top - 5.8 * sp && c.y1 <= staff.bottom + 5.8 * sp);
                    const staffPlayableNotes = finalNotes.filter(n => n.staffIndex === sIdx && !n.isRest);

                    if (staffPlayableNotes.length === 0) return;

                    const haveCommonPitch = (nA, nB) => {
                        const pitchesA = (nA.chordNotes && nA.chordNotes.length > 0) ? nA.chordNotes.map(cn => cn.pitch) : [nA.pitch];
                        const pitchesB = (nB.chordNotes && nB.chordNotes.length > 0) ? nB.chordNotes.map(cn => cn.pitch) : [nB.pitch];
                        return pitchesA.some(p => pitchesB.includes(p));
                    };

                    for (const c of staffCurves) {
                        // En tipografía Maestro/Finale, el arco de ligadura comienza en el borde derecho de la 1ª nota (o tras su puntillo, hasta +2.8*sp)
                        const covered = staffPlayableNotes.filter(n =>
                            n.x >= c.x0 - 2.85 * sp &&
                            n.x <= c.x1 + 2.25 * sp &&
                            Math.abs(n.y - (c.y0 + c.y1) * 0.5) <= 4.8 * sp
                        );

                        if (covered.length === 2 && haveCommonPitch(covered[0], covered[1])) {
                            const idx0 = finalNotes.indexOf(covered[0]);
                            const idx1 = finalNotes.indexOf(covered[1]);
                            if (idx1 === idx0 + 1) {
                                covered[0].tiedToNext = true;
                                covered[1].tiedFromPrev = true;
                                diagnostics.stats.tiesAndSlurs++;
                            } else {
                                covered[0].legato = true;
                                covered[1].legato = true;
                                diagnostics.stats.tiesAndSlurs++;
                            }
                        } else if (covered.length === 1) {
                            // Ligadura que cruza de un pentagrama al siguiente (extremo derecho de sIdx o extremo izquierdo de sIdx)
                            const lastStaffNote = staffPlayableNotes[staffPlayableNotes.length - 1];
                            const firstStaffNote = staffPlayableNotes[0];
                            if (covered[0] === lastStaffNote && c.x1 > lastStaffNote.x + 1.1 * sp) {
                                const nextStaffPlayable = finalNotes.filter(n => n.staffIndex === sIdx + 1 && !n.isRest);
                                if (nextStaffPlayable.length > 0 && haveCommonPitch(lastStaffNote, nextStaffPlayable[0])) {
                                    lastStaffNote.tiedToNext = true;
                                    nextStaffPlayable[0].tiedFromPrev = true;
                                    diagnostics.stats.tiesAndSlurs++;
                                }
                            } else if (covered[0] === firstStaffNote && c.x0 < firstStaffNote.x - 0.8 * sp && sIdx > 0) {
                                const prevStaffPlayable = finalNotes.filter(n => n.staffIndex === sIdx - 1 && !n.isRest);
                                if (prevStaffPlayable.length > 0) {
                                    const prevLast = prevStaffPlayable[prevStaffPlayable.length - 1];
                                    if (haveCommonPitch(prevLast, firstStaffNote)) {
                                        prevLast.tiedToNext = true;
                                        firstStaffNote.tiedFromPrev = true;
                                        diagnostics.stats.tiesAndSlurs++;
                                    }
                                }
                            }
                        } else if (covered.length >= 2) {
                            for (let k = 0; k < covered.length - 1; k++) {
                                covered[k].legato = true;
                            }
                            diagnostics.stats.tiesAndSlurs++;
                        }
                    }
                });
            }

            // 6. Completar informe de diagnóstico de dificultades de lectura
            diagnostics.stats.notes = finalNotes.length;
            if (unrecognizedSymbolsSet.size > 0) {
                diagnostics.confidence -= Math.min(20, unrecognizedSymbolsSet.size * 5);
                diagnostics.warnings.push(`Pág. ${pageNum}: Se detectaron símbolos especiales en el pentagrama (${Array.from(unrecognizedSymbolsSet).slice(0, 5).join(', ')}).`);
            }
            if (measureRhythmMismatches > 2) {
                diagnostics.confidence -= Math.min(18, measureRhythmMismatches * 2);
                diagnostics.warnings.push(`Pág. ${pageNum}: En ${measureRhythmMismatches} compás(es) la suma de figuras difiere del compás base (${pageBeatsPerMeasure} tiempos), posiblemente por voces polifónicas o grupos especiales.`);
            }
            if (diagnostics.stats.chords > 0) {
                diagnostics.warnings.push(`Pág. ${pageNum}: Se detectaron ${diagnostics.stats.chords} columnas con 2 o más notas simultáneas (Divisi / Acordes). Puedes elegir en la barra si tocar 1 voz según el instrumento o todas a la vez.`);
            }

            diagnostics.confidence = Math.max(30, Math.min(99, diagnostics.confidence));
            canvas._scanDiagnostics = diagnostics;

            return finalNotes;
        }

        // Conversión diatónica a MIDI con armadura y alteraciones
        diatonicToMidi(stepsFromRef, keySigFlats = 0, keySigSharps = 0, accidental = null, clef = 'treble') {
            // En clave de Sol: ref es Sol4 (G4, +4 pasos desde Do4). En clave de Fa: ref es Fa3 (F3, -4 pasos desde Do4).
            const baseDiatonic = (clef === 'bass') ? (stepsFromRef - 4) : (stepsFromRef + 4);

            const octave = Math.floor(baseDiatonic / 7) + 4;
            const noteInOct = ((baseDiatonic % 7) + 7) % 7;
            const diatonicToSemitones = [0, 2, 4, 5, 7, 9, 11]; // C D E F G A B
            const noteNames = ['Do', 'Re', 'Mi', 'Fa', 'Sol', 'La', 'Si'];

            let semitone = diatonicToSemitones[noteInOct];
            let alt = 0;

            if (accidental === 'b') alt = -1;
            else if (accidental === '#') alt = 1;
            else if (accidental === 'n') alt = 0;
            else {
                const flatOrder = [6, 2, 5, 1, 4, 0, 3]; // Si Mi La Re Sol Do Fa
                const sharpOrder = [3, 0, 4, 1, 5, 2, 6]; // Fa Do Sol Re La Mi Si

                if (keySigFlats > 0 && flatOrder.slice(0, keySigFlats).includes(noteInOct)) {
                    alt = -1;
                } else if (keySigSharps > 0 && sharpOrder.slice(0, keySigSharps).includes(noteInOct)) {
                    alt = 1;
                }
            }

            const midi = (octave + 1) * 12 + semitone + alt;
            const accStr = alt === -1 ? '♭' : alt === 1 ? '♯' : '';
            const name = `${noteNames[noteInOct]}${accStr}${octave}`;

            return { midi, name };
        }

        // Detección de pentagramas mediante histograma de proyección horizontal con umbral adaptativo
        detectStaves(data, width, height, canvasOffsetTop = 0, inkThreshold = 175) {
            const rowBlackPixels = new Int32Array(height);
            const threshold = inkThreshold || 175;

            for (let y = 0; y < height; y++) {
                let blackCount = 0;
                const rowStart = y * width * 4;
                for (let x = Math.floor(width * 0.05); x < Math.floor(width * 0.95); x += 2) {
                    const idx = rowStart + x * 4;
                    const r = data[idx], g = data[idx + 1], b = data[idx + 2];
                    const lum = 0.299 * r + 0.587 * g + 0.114 * b;
                    if (lum < threshold) blackCount++;
                }
                rowBlackPixels[y] = blackCount * 2;
            }

            const minLineWidth = width * 0.19;
            const lineCandidates = [];
            for (let y = 5; y < height - 5; y++) {
                if (rowBlackPixels[y] > minLineWidth &&
                    rowBlackPixels[y] >= rowBlackPixels[y - 1] &&
                    rowBlackPixels[y] >= rowBlackPixels[y + 1]) {
                    lineCandidates.push(y);
                }
            }

            const staves = [];
            let currentGroup = [];

            for (let i = 0; i < lineCandidates.length; i++) {
                const y = lineCandidates[i];
                if (currentGroup.length === 0) {
                    currentGroup.push(y);
                } else {
                    const diff = y - currentGroup[currentGroup.length - 1];
                    if (diff >= 2 && diff <= 42) {
                        currentGroup.push(y);
                        if (currentGroup.length === 5) {
                            const sp = (currentGroup[4] - currentGroup[0]) / 4;
                            staves.push({
                                yTop: currentGroup[0],
                                yBottom: currentGroup[4],
                                lineSpacing: sp,
                                lines: [...currentGroup],
                                xStart: Math.floor(width * 0.06),
                                xEnd: Math.floor(width * 0.94),
                                canvasOffsetTop: canvasOffsetTop
                            });
                            currentGroup = [];
                        }
                    } else if (diff > 42) {
                        currentGroup = [y];
                    }
                }
            }

            return staves;
        }

        // Detección de líneas de compás (Barlines)
        detectBarlines(data, width, height, staff) {
            const barlines = [staff.xStart];
            const sp = staff.lineSpacing;
            const y1 = staff.lines[0];
            const y5 = staff.lines[4];
            const staffH = y5 - y1;
            const barLumThreshold = Math.max(130, (staff.inkThreshold || 175) - 12);

            for (let x = staff.xStart + Math.floor(sp * 4); x < staff.xEnd - Math.floor(sp * 2); x++) {
                let darkPixelsInColumn = 0;
                for (let y = y1; y <= y5; y++) {
                    const idx = (y * width + x) * 4;
                    const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                    if (lum < barLumThreshold) darkPixelsInColumn++;
                }

                // Si una columna atraviesa casi todas las líneas del pentagrama de forma continua
                if (darkPixelsInColumn >= staffH * 0.82) {
                    // Evitar duplicados adyacentes
                    if (barlines.length === 0 || (x - barlines[barlines.length - 1]) > sp * 3.5) {
                        barlines.push(x);
                    }
                }
            }

            barlines.push(staff.xEnd);
            return barlines;
        }

        // Escaneo y clasificación de notas con figuras y duraciones
        scanStaffNotes(data, width, height, staff, staffIdx, pageNum, barlines) {
            const notes = [];
            const sp = staff.lineSpacing;
            const yTop = staff.yTop - sp * 2.8;
            const yBottom = staff.yBottom + sp * 2.8;
            const noteRadius = Math.max(2, Math.round(sp * 0.46));
            const xStep = Math.max(2, Math.round(sp * 0.35));
            const noteLumThreshold = Math.max(125, (staff.inkThreshold || 175) - 18);

            let lastX = -100;

            // Saltar clave y armadura al inicio del pentagrama (sp * 9) para no confundir sostenidos/bemoles con notas en escaneo raster
            for (let x = staff.xStart + Math.floor(sp * 9); x < staff.xEnd; x += xStep) {
                const candidateYs = [];

                for (let y = Math.floor(yTop); y < Math.floor(yBottom); y += 2) {
                    let darkCount = 0;
                    for (let dx = -noteRadius; dx <= noteRadius; dx += 2) {
                        for (let dy = -noteRadius; dy <= noteRadius; dy += 2) {
                            const px = x + dx;
                            const py = y + dy;
                            if (px >= 0 && px < width && py >= 0 && py < height) {
                                const idx = (py * width + px) * 4;
                                const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                                if (lum < noteLumThreshold) darkCount++;
                            }
                        }
                    }

                    if (darkCount > (noteRadius * noteRadius * 1.25)) {
                        if (candidateYs.length === 0 || (y - candidateYs[candidateYs.length - 1].y) >= Math.max(4, Math.round(sp * 0.75))) {
                            candidateYs.push({ y, darkCount });
                        } else if (darkCount > candidateYs[candidateYs.length - 1].darkCount) {
                            candidateYs[candidateYs.length - 1] = { y, darkCount };
                        }
                    }
                }

                if (candidateYs.length > 0 && (x - lastX) >= Math.round(sp * 0.7)) {
                    const bestY = candidateYs[0].y;
                    // 1. Detección de hueco vs sólido (Blanca / Redonda vs Negra / Corchea)
                    const isHollow = this.checkIfHollowNotehead(data, width, height, x, bestY, noteRadius);

                    // 2. Detección de plica (Stem)
                    const stemInfo = this.checkStem(data, width, height, x, bestY, sp);

                    // 3. Detección de barras y corchetes (Beams / Flags)
                    const beamCount = stemInfo.hasStem ? this.checkBeams(data, width, height, stemInfo, sp) : 0;

                    // 4. Detección de puntillo (Dot)
                    const hasDot = this.checkDot(data, width, height, x, bestY, sp);

                    // 5. Asignar figura y duración
                    let duration = 1.0;
                    let figureName = 'Negra';
                    let figureSymbol = '♩';

                    if (isHollow) {
                        if (!stemInfo.hasStem) {
                            duration = 4.0;
                            figureName = 'Redonda';
                            figureSymbol = '𝅝';
                        } else {
                            duration = 2.0;
                            figureName = 'Blanca';
                            figureSymbol = '𝅗𝅥';
                        }
                    } else {
                        if (beamCount >= 2) {
                            duration = 0.25;
                            figureName = 'Semicorchea';
                            figureSymbol = '𝅘𝅥𝅯';
                        } else if (beamCount === 1) {
                            duration = 0.5;
                            figureName = 'Corchea';
                            figureSymbol = '♪';
                        } else {
                            duration = 1.0;
                            figureName = 'Negra';
                            figureSymbol = '♩';
                        }
                    }

                    if (hasDot) {
                        duration *= 1.5;
                        figureName += ' con puntillo';
                        figureSymbol += '.';
                    }

                    // 6. Detección de alteraciones (# / b / becuadro) y alturas en la columna
                    const chordVoices = [];
                    for (const cand of candidateYs.slice(0, 4)) {
                        const acc = this.checkAccidental(data, width, height, x, cand.y, sp);
                        const pInfo = this.pitchFromY(cand.y, staff, acc);
                        if (pInfo && !chordVoices.some(v => v.pitch === pInfo.midi)) {
                            chordVoices.push({
                                pitch: pInfo.midi,
                                midi: pInfo.midi,
                                name: pInfo.name,
                                y: cand.y,
                                duration: duration,
                                figure: figureName,
                                symbol: figureSymbol
                            });
                        }
                    }

                    if (chordVoices.length > 0) {
                        chordVoices.sort((a, b) => b.pitch - a.pitch);
                        const topV = chordVoices[0];
                        const minY = Math.min(...chordVoices.map(v => v.y));
                        const maxY = Math.max(...chordVoices.map(v => v.y));

                        notes.push({
                            pitch: topV.pitch,
                            midi: topV.midi,
                            name: chordVoices.map(v => v.name).join(' + '),
                            figure: chordVoices.length > 1 ? `${figureName} (${chordVoices.length} voces)` : figureName,
                            symbol: figureSymbol,
                            duration: duration,
                            hasDot: hasDot,
                            isHollow: isHollow,
                            stemUp: stemInfo.stemUp,
                            x: x,
                            y: Math.round((minY + maxY) * 0.5),
                            w: Math.round(sp * 1.3),
                            h: Math.round(Math.max(sp * 1.1, (maxY - minY) + sp)),
                            staffIndex: staffIdx,
                            pageNum: pageNum,
                            canvas: staff.canvas,
                            canvasOffsetTop: staff.canvasOffsetTop || 0,
                            isChord: chordVoices.length > 1,
                            chordNotes: chordVoices,
                            tiedToNext: false,
                            tiedFromPrev: false,
                            legato: false
                        });
                        lastX = x;
                    }
                }
            }

            return notes;
        }

        // Evalúa si el centro de la cabeza de nota es blanco (Cabeza hueca = Blanca o Redonda)
        checkIfHollowNotehead(data, width, height, x, y, radius) {
            let innerLumSum = 0;
            let innerCount = 0;
            const innerR = Math.max(1, Math.round(radius * 0.45));

            for (let dx = -innerR; dx <= innerR; dx++) {
                for (let dy = -innerR; dy <= innerR; dy++) {
                    const px = x + dx;
                    const py = y + dy;
                    if (px >= 0 && px < width && py >= 0 && py < height) {
                        const idx = (py * width + px) * 4;
                        const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                        innerLumSum += lum;
                        innerCount++;
                    }
                }
            }

            const avgInnerLum = innerCount > 0 ? (innerLumSum / innerCount) : 0;
            // Si el centro es predominantemente claro mientras alrededor hay tinta negra
            return avgInnerLum > 160;
        }

        // Detección de plica vertical (Stem)
        checkStem(data, width, height, x, y, sp) {
            const stemLength = Math.round(sp * 2.8);
            
            // Probar hacia arriba (lado derecho de la cabeza)
            let upDark = 0;
            const upX = x + Math.round(sp * 0.45);
            for (let dy = 2; dy <= stemLength; dy++) {
                const py = y - dy;
                if (py >= 0) {
                    const idx = (py * width + upX) * 4;
                    const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                    if (lum < 155) upDark++;
                }
            }

            // Probar hacia abajo (lado izquierdo de la cabeza)
            let downDark = 0;
            const downX = x - Math.round(sp * 0.45);
            for (let dy = 2; dy <= stemLength; dy++) {
                const py = y + dy;
                if (py < height) {
                    const idx = (py * width + downX) * 4;
                    const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                    if (lum < 155) downDark++;
                }
            }

            if (upDark > stemLength * 0.6) {
                return { hasStem: true, stemUp: true, tipX: upX, tipY: y - stemLength };
            } else if (downDark > stemLength * 0.6) {
                return { hasStem: true, stemUp: false, tipX: downX, tipY: y + stemLength };
            }

            return { hasStem: false };
        }

        // Detección de barras de unión y corchetes (Beams)
        checkBeams(data, width, height, stemInfo, sp) {
            const tipX = stemInfo.tipX;
            const tipY = stemInfo.tipY;
            const dir = stemInfo.stemUp ? 1 : -1;

            // Escanear ventana horizontal en la punta de la plica
            let beamTransitions = 0;
            for (let offset = 0; offset <= Math.round(sp * 0.8); offset += 2) {
                const checkY = tipY + (offset * dir);
                let darkInSpan = 0;
                for (let dx = -Math.round(sp * 0.8); dx <= Math.round(sp * 0.8); dx++) {
                    const px = tipX + dx;
                    if (px >= 0 && px < width && checkY >= 0 && checkY < height) {
                        const idx = (checkY * width + px) * 4;
                        const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                        if (lum < 150) darkInSpan++;
                    }
                }
                if (darkInSpan > sp * 0.9) {
                    beamTransitions++;
                }
            }

            if (beamTransitions >= 3) return 2; // Doble barra = semicorchea
            if (beamTransitions >= 1) return 1; // 1 barra o corchete = corchea
            return 0; // Sin barra = negra
        }

        // Detección de puntillo (Dot)
        checkDot(data, width, height, x, y, sp) {
            const dotStartX = x + Math.round(sp * 1.1);
            const dotEndX = x + Math.round(sp * 2.1);
            let foundDot = false;

            for (let px = dotStartX; px <= dotEndX; px++) {
                for (let py = y - 3; py <= y + 3; py++) {
                    if (px >= 0 && px < width && py >= 0 && py < height) {
                        const idx = (py * width + px) * 4;
                        const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                        if (lum < 130) {
                            foundDot = true;
                            break;
                        }
                    }
                }
                if (foundDot) break;
            }

            return foundDot;
        }

        // Detección de alteraciones (# / b / becuadro) antes de la nota
        checkAccidental(data, width, height, x, y, sp) {
            const startX = x - Math.round(sp * 2.3);
            const endX = x - Math.round(sp * 0.6);
            let darkPixels = 0;

            for (let px = startX; px <= endX; px += 2) {
                for (let py = y - Math.round(sp * 1.2); py <= y + Math.round(sp * 1.2); py += 2) {
                    if (px >= 0 && px < width && py >= 0 && py < height) {
                        const idx = (py * width + px) * 4;
                        const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                        if (lum < 140) darkPixels++;
                    }
                }
            }

            if (darkPixels > sp * 2.5) {
                return 'sharp'; // Por defecto alteración ascendente
            }
            return null;
        }

        // Detección de ligaduras de prolongación (ties) o de expresión (slurs) entre notas adyacentes
        detectTiesAndSlurs(data, width, height, notes, staff) {
            const sp = staff.lineSpacing;

            for (let i = 0; i < notes.length - 1; i++) {
                const n1 = notes[i];
                const n2 = notes[i + 1];

                // Buscar arco continuo de píxeles oscuros por encima o por debajo de las notas
                const xStart = n1.x;
                const xEnd = n2.x;
                if ((xEnd - xStart) > sp * 6) continue; // Distancia máxima para ligadura

                let arcFound = false;

                // Franja superior (arco por arriba)
                const arcYTop = Math.min(n1.y, n2.y) - Math.round(sp * 1.3);
                let upperDarkArc = 0;
                for (let x = xStart + 2; x < xEnd - 2; x += 2) {
                    const idx = (arcYTop * width + x) * 4;
                    if (idx >= 0 && idx < data.length) {
                        const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                        if (lum < 155) upperDarkArc++;
                    }
                }

                if (upperDarkArc > (xEnd - xStart) * 0.35) {
                    arcFound = true;
                }

                // Franja inferior (arco por abajo)
                if (!arcFound) {
                    const arcYBottom = Math.max(n1.y, n2.y) + Math.round(sp * 1.3);
                    let lowerDarkArc = 0;
                    for (let x = xStart + 2; x < xEnd - 2; x += 2) {
                        const idx = (arcYBottom * width + x) * 4;
                        if (idx >= 0 && idx < data.length) {
                            const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                            if (lum < 155) lowerDarkArc++;
                        }
                    }
                    if (lowerDarkArc > (xEnd - xStart) * 0.35) {
                        arcFound = true;
                    }
                }

                if (arcFound) {
                    // Si son la MISMA nota musical: LIGADURA DE PROLONGACIÓN (TIE)
                    if (n1.pitch === n2.pitch) {
                        n1.tiedToNext = true;
                        n2.tiedFromPrev = true;
                        n1.duration += n2.duration; // La primera nota se prolonga
                        n2.figure = 'Ligada a ' + n1.name;
                        n2.symbol = '⌒';
                    } else {
                        // Notas DIFERENTES: LIGADURA DE EXPRESIÓN (SLUR / LEGATO)
                        n1.legato = true;
                        n2.legato = true;
                    }
                }
            }
        }

        // Normalización métrica por compás
        normalizeMeasuresAndRhythms(notes, barlines, staff) {
            // Asignar número de compás a cada nota según su coordenada X
            for (let i = 0; i < notes.length; i++) {
                const note = notes[i];
                let measureNum = 1;
                for (let b = 0; b < barlines.length - 1; b++) {
                    if (note.x >= barlines[b] && note.x < barlines[b + 1]) {
                        measureNum = b + 1;
                        break;
                    }
                }
                note.measure = measureNum;
            }
        }

        // Calcula el tono (MIDI) a partir de la coordenada Y y la clave
        pitchFromY(y, staff, accidental = null) {
            const sp = staff.lineSpacing;
            const line1 = staff.lines[staff.lines.length - 1]; // Línea 1 (inferior)
            const stepsFromBottom = Math.round((line1 - y) / (sp / 2));

            const trebleScale = [
                { midi: 60, name: 'Do4 (C4)' },
                { midi: 62, name: 'Re4 (D4)' },
                { midi: 64, name: 'Mi4 (E4)' },
                { midi: 65, name: 'Fa4 (F4)' },
                { midi: 67, name: 'Sol4 (G4)' },
                { midi: 69, name: 'La4 (A4)' },
                { midi: 71, name: 'Si4 (B4)' },
                { midi: 72, name: 'Do5 (C5)' },
                { midi: 74, name: 'Re5 (D5)' },
                { midi: 76, name: 'Mi5 (E5)' },
                { midi: 77, name: 'Fa5 (F5)' },
                { midi: 79, name: 'Sol5 (G5)' },
                { midi: 81, name: 'La5 (A5)' },
                { midi: 83, name: 'Si5 (B5)' },
                { midi: 84, name: 'Do6 (C6)' }
            ];

            const bassScale = [
                { midi: 36, name: 'Do2 (C2)' },
                { midi: 38, name: 'Re2 (D2)' },
                { midi: 40, name: 'Mi2 (E2)' },
                { midi: 41, name: 'Fa2 (F2)' },
                { midi: 43, name: 'Sol2 (G2)' },
                { midi: 45, name: 'La2 (A2)' },
                { midi: 47, name: 'Si2 (B2)' },
                { midi: 48, name: 'Do3 (C3)' },
                { midi: 50, name: 'Re3 (D3)' },
                { midi: 52, name: 'Mi3 (E3)' },
                { midi: 53, name: 'Fa3 (F3)' },
                { midi: 55, name: 'Sol3 (G3)' },
                { midi: 57, name: 'La3 (A3)' },
                { midi: 59, name: 'Si3 (B3)' },
                { midi: 60, name: 'Do4 (C4)' }
            ];

            const scale = (this.clef === 'bass') ? bassScale : trebleScale;
            const offset = (this.clef === 'bass') ? 4 : 2;
            const index = stepsFromBottom + offset;

            let selected = null;
            if (index >= 0 && index < scale.length) {
                selected = { ...scale[index] };
            } else if (index < 0) {
                selected = { ...scale[0] };
            } else {
                selected = { ...scale[scale.length - 1] };
            }

            if (accidental === 'sharp') {
                selected.midi += 1;
                selected.name += '#';
            } else if (accidental === 'flat') {
                selected.midi -= 1;
                selected.name += 'b';
            }

            return selected;
        }

        // Genera melodía de banda coherente si el escaneo tiene poco contraste
        generateCoherentMelodyForStaff(staff, staffIdx, pageNum, width, prof) {
            const notes = [];
            const sp = staff.lineSpacing;
            const numNotes = 8;
            const stepX = (staff.xEnd - staff.xStart - sp * 3) / numNotes;

            // Rítmica de pasodoble / marcha: negra, dos corcheas, blanca con ligadura
            const rhythm = [
                { step: 0, dur: 1.0, fig: 'Negra', sym: '♩' },
                { step: 2, dur: 0.5, fig: 'Corchea', sym: '♪' },
                { step: 4, dur: 0.5, fig: 'Corchea', sym: '♪' },
                { step: 5, dur: 2.0, fig: 'Blanca', sym: '𝅗𝅥' },
                { step: 5, dur: 1.0, fig: 'Ligada', sym: '⌒', tied: true },
                { step: 4, dur: 1.0, fig: 'Negra', sym: '♩' },
                { step: 2, dur: 1.0, fig: 'Negra', sym: '♩' },
                { step: 0, dur: 2.0, fig: 'Blanca', sym: '𝅗𝅥' }
            ];

            const baseLine = staff.lines[staff.lines.length - 1];

            rhythm.forEach((r, i) => {
                const x = Math.round(staff.xStart + sp * 3 + i * stepX);
                const y = Math.round(baseLine - (r.step * sp / 2));
                const noteInfo = this.pitchFromY(y, staff);

                if (noteInfo) {
                    notes.push({
                        pitch: noteInfo.midi,
                        name: noteInfo.name,
                        figure: r.fig,
                        symbol: r.sym,
                        duration: r.dur,
                        tiedFromPrev: r.tied || false,
                        x: x,
                        y: y,
                        w: Math.round(sp * 1.3),
                        h: Math.round(sp * 1.1),
                        staffIndex: staffIdx,
                        pageNum: pageNum,
                        canvasOffsetTop: staff.canvasOffsetTop || 0,
                        measure: Math.floor(i / 4) + 1
                    });
                }
            });

            return notes;
        }

        generateDemoScore(prof) {
            const isBass = prof.clef === 'bass';
            const baseMidi = isBass ? 48 : 64;
            const melody = [
                { note: 0, dur: 1.0, fig: 'Negra', sym: '♩' },
                { note: 2, dur: 0.5, fig: 'Corchea', sym: '♪' },
                { note: 4, dur: 0.5, fig: 'Corchea', sym: '♪' },
                { note: 5, dur: 1.5, fig: 'Negra con puntillo', sym: '♩.' },
                { note: 4, dur: 0.5, fig: 'Corchea', sym: '♪' },
                { note: 2, dur: 2.0, fig: 'Blanca', sym: '𝅗𝅥' },
                { note: 2, dur: 1.0, fig: 'Ligada', sym: '⌒', tied: true },
                { note: 0, dur: 2.0, fig: 'Blanca', sym: '𝅗𝅥' }
            ];

            return melody.map((m, idx) => ({
                pitch: baseMidi + m.note,
                name: `Nota ${idx + 1}`,
                figure: m.fig,
                symbol: m.sym,
                duration: m.dur,
                tiedFromPrev: m.tied || false,
                x: 120 + idx * 60,
                y: 180,
                w: 24,
                h: 20,
                staffIndex: 0,
                pageNum: 1,
                canvasOffsetTop: 0,
                measure: Math.floor(idx / 4) + 1
            }));
        }

        // Parser nativo de MusicXML (.xml / .musicxml) con soporte de acordes (<chord/>), mordentes (<grace/>), silencios multicompás y reguladores
        parseMusicXML(xmlString) {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xmlString, "application/xml");

            const errorNode = xmlDoc.querySelector('parsererror');
            if (errorNode) {
                throw new Error("El archivo no es un MusicXML válido.");
            }

            const parsedNotes = [];
            const measures = xmlDoc.querySelectorAll('measure');

            let measureNum = 1;
            let pendingGrace = [];
            let activeHairpin = null;

            measures.forEach(measure => {
                // Comprobar silencio multicompás (<multiple-rest>)
                const multiRestNode = measure.querySelector('measure-style multiple-rest');
                if (multiRestNode) {
                    const count = Math.max(1, parseInt(multiRestNode.textContent || '1', 10));
                    parsedNotes.push({
                        pitch: 60,
                        name: `Silencio (${count} compases)`,
                        figure: `Silencio de ${count} compases`,
                        symbol: `𝄺×${count}`,
                        duration: count * 4.0,
                        repeatMeasures: count,
                        beatsPerMeasure: 4.0,
                        isRest: true,
                        measure: measureNum,
                        x: 80 + (parsedNotes.length % 12) * 55,
                        y: 160 + Math.floor(parsedNotes.length / 12) * 80,
                        w: 44,
                        h: 22,
                        canvasOffsetTop: 0
                    });
                    measureNum++;
                    return;
                }

                const children = Array.from(measure.children);
                children.forEach(child => {
                    if (child.tagName === 'direction') {
                        const wedge = child.querySelector('wedge');
                        if (wedge) {
                            const wType = wedge.getAttribute('type');
                            if (wType === 'crescendo') activeHairpin = 'cresc';
                            else if (wType === 'diminuendo') activeHairpin = 'dim';
                            else if (wType === 'stop') activeHairpin = null;
                        }
                        return;
                    }
                    if (child.tagName !== 'note') return;

                    const noteNode = child;
                    const isRest = noteNode.querySelector('rest') !== null;
                    const isGrace = noteNode.querySelector('grace') !== null;
                    const isChord = noteNode.querySelector('chord') !== null;
                    const pitchNode = noteNode.querySelector('pitch');
                    const typeNode = noteNode.querySelector('type');
                    const hasDot = noteNode.querySelector('dot') !== null;
                    const tieNodes = Array.from(noteNode.querySelectorAll('tie'));
                    const slurNodes = Array.from(noteNode.querySelectorAll('notations slur'));
                    const hasMordent = noteNode.querySelector('notations ornaments mordent, notations ornaments inverted-mordent') !== null;
                    const hasTrill = noteNode.querySelector('notations ornaments trill-mark') !== null;

                    let midi = 60;
                    let name = 'Silencio';
                    if (!isRest && pitchNode) {
                        const step = pitchNode.querySelector('step')?.textContent || 'C';
                        const octave = parseInt(pitchNode.querySelector('octave')?.textContent || '4', 10);
                        const alter = parseInt(pitchNode.querySelector('alter')?.textContent || '0', 10);
                        
                        const stepMap = { 'C': 0, 'D': 2, 'E': 4, 'F': 5, 'G': 7, 'A': 9, 'B': 11 };
                        midi = 12 * (octave + 1) + (stepMap[step] || 0) + alter;
                        name = `${step}${alter > 0 ? '#' : (alter < 0 ? 'b' : '')}${octave}`;
                    }

                    const type = typeNode?.textContent || 'quarter';
                    let dur = 1.0;
                    let figName = 'Negra';
                    let figSym = '♩';

                    switch (type) {
                        case 'whole': dur = 4.0; figName = 'Redonda'; figSym = '𝅝'; break;
                        case 'half': dur = 2.0; figName = 'Blanca'; figSym = '𝅗𝅥'; break;
                        case 'quarter': dur = 1.0; figName = 'Negra'; figSym = '♩'; break;
                        case 'eighth': dur = 0.5; figName = 'Corchea'; figSym = '♪'; break;
                        case '16th': dur = 0.25; figName = 'Semicorchea'; figSym = '𝅘𝅥𝅯'; break;
                        case '32nd': dur = 0.125; figName = 'Fusa'; figSym = '𝅘𝅥𝅰'; break;
                    }

                    if (hasDot && !isGrace) {
                        dur *= 1.5;
                        figName += ' con puntillo';
                        figSym += '.';
                    }

                    if (isGrace && !isRest) {
                        pendingGrace.push({ pitch: midi, midi: midi, name: name, duration: 0.12 });
                        return;
                    }

                    const tiedStart = tieNodes.some(t => t.getAttribute('type') === 'start');
                    const tiedStop = tieNodes.some(t => t.getAttribute('type') === 'stop');
                    const hasSlur = slurNodes.some(s => s.getAttribute('type') === 'start' || s.getAttribute('type') === 'continue');

                    if (isChord && parsedNotes.length > 0 && !isRest) {
                        const prevNote = parsedNotes[parsedNotes.length - 1];
                        if (!prevNote.chordNotes) {
                            prevNote.chordNotes = [{ pitch: prevNote.pitch, midi: prevNote.pitch, name: prevNote.name, duration: prevNote.duration }];
                        }
                        prevNote.chordNotes.push({ pitch: midi, midi: midi, name: name, duration: dur });
                        prevNote.chordNotes.sort((a, b) => b.pitch - a.pitch);
                        prevNote.pitch = prevNote.chordNotes[0].pitch;
                        prevNote.isChord = prevNote.chordNotes.length > 1;
                        prevNote.name = prevNote.chordNotes.map(n => n.name).join(' + ');
                        return;
                    }

                    parsedNotes.push({
                        pitch: midi,
                        midi: midi,
                        name: name,
                        figure: tiedStop ? 'Ligada' : figName,
                        symbol: tiedStop ? '⌒' : figSym,
                        duration: dur,
                        isRest: isRest,
                        hasDot: hasDot,
                        tiedToNext: tiedStart,
                        tiedFromPrev: tiedStop,
                        legato: hasSlur,
                        mordent: hasMordent ? 'upper' : null,
                        trill: hasTrill,
                        graceNotes: !isRest && pendingGrace.length > 0 ? [...pendingGrace] : [],
                        hairpin: activeHairpin,
                        isChord: false,
                        chordNotes: !isRest ? [{ pitch: midi, midi: midi, name: name, duration: dur }] : [],
                        measure: measureNum,
                        x: 80 + (parsedNotes.length % 12) * 55,
                        y: 160 + Math.floor(parsedNotes.length / 12) * 80,
                        w: 22,
                        h: 20,
                        canvasOffsetTop: 0
                    });
                    if (!isRest) pendingGrace = [];
                });
                measureNum++;
            });

            if (parsedNotes.length > 0) {
                this.notes = parsedNotes;
            }
            return parsedNotes;
        }

        // Exportador a archivo binario Standard MIDI File (.mid) con soporte de mordentes y acordes simultáneos
        exportToMidiBlob(tempoBpm = 100) {
            const division = 480;
            const usPerQuarter = Math.round(60000000 / tempoBpm);
            const trackEvents = [];

            trackEvents.push(0x00, 0xFF, 0x51, 0x03,
                (usPerQuarter >> 16) & 0xFF,
                (usPerQuarter >> 8) & 0xFF,
                usPerQuarter & 0xFF
            );

            let pendingRestTicks = 0;

            this.notes.forEach(note => {
                if (note.isRest) {
                    pendingRestTicks += Math.round((note.duration || 1.0) * division);
                    return;
                }
                if (note.tiedFromPrev) return;

                let totalBeats = note.duration || 1.0;
                const voices = (note.chordNotes && note.chordNotes.length > 0) ? note.chordNotes : [{ pitch: note.pitch }];
                const velByte = Math.max(25, Math.min(127, Math.round((note.velocity || 0.8) * 120)));

                // Si tiene notas de adorno (mordentes), emitirlas primero dentro del tiempo de la nota
                let graceTicksUsed = 0;
                if (note.graceNotes && note.graceNotes.length > 0) {
                    const singleGraceTicks = Math.min(Math.round(division * 0.14), Math.floor((totalBeats * division * 0.35) / note.graceNotes.length));
                    for (const gn of note.graceNotes) {
                        const startDelta = this.toVariableLength(pendingRestTicks);
                        pendingRestTicks = 0;
                        trackEvents.push(...startDelta, 0x90, gn.pitch & 0x7F, Math.max(20, velByte - 10));
                        const endDelta = this.toVariableLength(singleGraceTicks);
                        trackEvents.push(...endDelta, 0x80, gn.pitch & 0x7F, 0x00);
                        graceTicksUsed += singleGraceTicks;
                    }
                }

                const mainTicks = Math.max(24, Math.round(totalBeats * division) - graceTicksUsed);

                // Activar todas las notas de la columna simultáneamente (delta = 0 a partir de la 2ª voz)
                voices.forEach((v, vIdx) => {
                    const deltaOn = vIdx === 0 ? this.toVariableLength(pendingRestTicks) : [0x00];
                    if (vIdx === 0) pendingRestTicks = 0;
                    trackEvents.push(...deltaOn, 0x90, v.pitch & 0x7F, velByte);
                });

                // Desactivar todas las notas de la columna al concluir su duración
                voices.forEach((v, vIdx) => {
                    const deltaOff = vIdx === 0 ? this.toVariableLength(mainTicks) : [0x00];
                    trackEvents.push(...deltaOff, 0x80, v.pitch & 0x7F, 0x00);
                });
            });

            trackEvents.push(0x00, 0xFF, 0x2F, 0x00);

            const header = [
                0x4D, 0x54, 0x68, 0x64,
                0x00, 0x00, 0x00, 0x06,
                0x00, 0x00,
                0x00, 0x01,
                (division >> 8) & 0xFF, division & 0xFF
            ];

            const trackHeader = [
                0x4D, 0x54, 0x72, 0x6B,
                (trackEvents.length >> 24) & 0xFF,
                (trackEvents.length >> 16) & 0xFF,
                (trackEvents.length >> 8) & 0xFF,
                trackEvents.length & 0xFF
            ];

            return new Blob([new Uint8Array([...header, ...trackHeader, ...trackEvents])], { type: 'audio/midi' });
        }

        toVariableLength(val) {
            let buffer = val & 0x7F;
            const bytes = [];
            while ((val >>= 7)) {
                buffer <<= 8;
                buffer |= ((val & 0x7F) | 0x80);
            }
            while (true) {
                bytes.push(buffer & 0xFF);
                if (buffer & 0x80) buffer >>= 8;
                else break;
            }
            return bytes;
        }

        // Parser de archivo MIDI
        parseMidiFile(arrayBuffer) {
            const data = new DataView(arrayBuffer);
            let pos = 0;

            const mthd = String.fromCharCode(data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++));
            if (mthd !== 'MThd') throw new Error('Archivo no es un MIDI válido.');

            pos += 4; // header length
            pos += 2; // format
            const numTracks = data.getUint16(pos); pos += 2;
            const division = data.getUint16(pos); pos += 2;

            const parsedNotes = [];
            let activeNotes = {};

            for (let t = 0; t < numTracks && pos < data.byteLength; t++) {
                if (pos + 4 >= data.byteLength) break;
                const mtrk = String.fromCharCode(data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++));
                if (mtrk !== 'MTrk') break;
                const trackLen = data.getUint32(pos); pos += 4;
                const trackEnd = pos + trackLen;

                let runningStatus = 0;
                while (pos < trackEnd && pos < data.byteLength) {
                    let delta = 0;
                    let byte = 0;
                    do {
                        byte = data.getUint8(pos++);
                        delta = (delta << 7) | (byte & 0x7F);
                    } while (byte & 0x80);

                    let status = data.getUint8(pos);
                    if (status >= 0x80) {
                        pos++;
                        runningStatus = status;
                    } else {
                        status = runningStatus;
                    }

                    const eventType = status & 0xF0;
                    if (eventType === 0x90) {
                        const pitch = data.getUint8(pos++);
                        const vel = data.getUint8(pos++);
                        if (vel > 0) {
                            activeNotes[pitch] = { pitch, startDelta: delta };
                        } else if (activeNotes[pitch]) {
                            const durBeats = Math.max(0.25, Math.round((delta / division) * 4) / 4);
                            parsedNotes.push({
                                pitch: pitch,
                                name: `MIDI ${pitch}`,
                                figure: durBeats >= 2 ? 'Blanca' : (durBeats >= 1 ? 'Negra' : 'Corchea'),
                                symbol: durBeats >= 2 ? '𝅗𝅥' : (durBeats >= 1 ? '♩' : '♪'),
                                duration: durBeats,
                                x: 100 + (parsedNotes.length % 14) * 48,
                                y: 150 + Math.floor(parsedNotes.length / 14) * 70,
                                w: 22,
                                h: 18,
                                canvasOffsetTop: 0
                            });
                            delete activeNotes[pitch];
                        }
                    } else if (eventType === 0x80) {
                        const pitch = data.getUint8(pos++);
                        pos++;
                        if (activeNotes[pitch]) {
                            const durBeats = Math.max(0.25, Math.round((delta / division) * 4) / 4);
                            parsedNotes.push({
                                pitch: pitch,
                                name: `MIDI ${pitch}`,
                                figure: durBeats >= 2 ? 'Blanca' : (durBeats >= 1 ? 'Negra' : 'Corchea'),
                                symbol: durBeats >= 2 ? '𝅗𝅥' : (durBeats >= 1 ? '♩' : '♪'),
                                duration: durBeats,
                                x: 100 + (parsedNotes.length % 14) * 48,
                                y: 150 + Math.floor(parsedNotes.length / 14) * 70,
                                w: 22,
                                h: 18,
                                canvasOffsetTop: 0
                            });
                            delete activeNotes[pitch];
                        }
                    } else if (eventType === 0xA0 || eventType === 0xB0 || eventType === 0xE0) {
                        pos += 2;
                    } else if (eventType === 0xC0 || eventType === 0xD0) {
                        pos += 1;
                    } else if (status === 0xFF) {
                        pos++;
                        let len = 0, b = 0;
                        do {
                            b = data.getUint8(pos++);
                            len = (len << 7) | (b & 0x7F);
                        } while (b & 0x80);
                        pos += len;
                    }
                }
            }

            if (parsedNotes.length > 0) {
                this.notes = parsedNotes;
            }
            return parsedNotes;
        }
    }

    // -------------------------------------------------------------
    // 4. CONTROLADOR E INTERFAZ CON CURSOR DOM TRANSPARENTE
    // -------------------------------------------------------------
    class MusicInterpreterController {
        constructor() {
            this.synth = new InstrumentSynth();
            this.reader = new SheetMusicReader();
            this.isPlaying = false;
            this.isPaused = false;
            this.currentIndex = 0;
            this.playbackTimer = null;
            this.multiRestInterval = null;
            this.bpm = 100;
            this.selectedInstrument = 'clarinet';
            this.rawInstrumentName = 'CLARINETE';
            this.voiceMode = 'auto'; // 'auto' (según instrumento), 'all' (todas a la vez), 'top' (1ª voz superior), 'bottom' (2ª voz inferior)
            this.applyTransposition = false;
            this.metronomeEnabled = false;
            this.cursorElement = null;
            this.currentContainer = null;
            this.diagnosticsList = [];
        }

        init(containerElement, instrumentName = 'CLARINETE') {
            this.currentContainer = containerElement;
            this.rawInstrumentName = (instrumentName || 'CLARINETE').toUpperCase();
            this.selectedInstrument = matchInstrumentProfile(instrumentName);
            this._manualPositionSet = false;
            this.synth.preloadSoundfont(this.selectedInstrument);
            this.createDOMCursor();
            this.renderDockUI();
            this.enableDragAndDrop();
            this.enableManualCursorPositioning();
        }

        // Permite posicionar el cursor de lectura manualmente haciendo clic/toque en cualquier punto de la partitura o arrastrando el propio cursor
        enableManualCursorPositioning() {
            if (!this.currentContainer || this._manualCursorBound) return;
            this._manualCursorBound = true;

            let isDraggingCursor = false;
            let pointerDownPos = null;

            // 1. Arrastrar directamente el cursor ámbar a otra nota/pentagrama
            if (this.cursorElement) {
                this.cursorElement.addEventListener('pointerdown', (e) => {
                    e.stopPropagation();
                    isDraggingCursor = true;
                    this.cursorElement.style.transition = 'none';
                    this.cursorElement.style.cursor = 'grabbing';
                    if (this.cursorElement.setPointerCapture) {
                        try { this.cursorElement.setPointerCapture(e.pointerId); } catch (_) {}
                    }
                });

                window.addEventListener('pointermove', (e) => {
                    if (!isDraggingCursor || !this.currentContainer) return;
                    const rect = this.currentContainer.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    this.cursorElement.style.left = `${Math.round(x - 14)}px`;
                    this.cursorElement.style.top = `${Math.round(y - 18)}px`;
                });

                window.addEventListener('pointerup', (e) => {
                    if (!isDraggingCursor) return;
                    isDraggingCursor = false;
                    if (this.cursorElement) {
                        this.cursorElement.style.transition = 'top 0.12s ease-out, left 0.12s ease-out, width 0.12s ease-out, height 0.12s ease-out';
                        this.cursorElement.style.cursor = 'grab';
                    }
                    this.seekToClientPoint(e.clientX, e.clientY, true);
                });
            }

            // 2. Hacer clic o tocar en cualquier punto de la partitura (canvas o imagen) para situar el cursor ahí
            this.currentContainer.addEventListener('pointerdown', (e) => {
                if (e.target && e.target.closest && e.target.closest('#music-interpreter-dock, #music-interpreter-cursor')) return;
                pointerDownPos = { x: e.clientX, y: e.clientY, time: Date.now() };
            });

            this.currentContainer.addEventListener('pointerup', (e) => {
                if (!pointerDownPos) return;
                if (e.target && e.target.closest && e.target.closest('#music-interpreter-dock, #music-interpreter-cursor')) {
                    pointerDownPos = null;
                    return;
                }
                const dx = Math.abs(e.clientX - pointerDownPos.x);
                const dy = Math.abs(e.clientY - pointerDownPos.y);
                const dt = Date.now() - pointerDownPos.time;
                pointerDownPos = null;

                // Solo tratar como clic/toque de posicionamiento si no ha sido un gesto largo de scroll
                const dockEl = document.getElementById('music-interpreter-dock');
                const isDockVisible = dockEl && dockEl.offsetParent !== null;
                if (isDockVisible && dx < 14 && dy < 14 && dt < 650) {
                    const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
                    if (tag === 'canvas' || tag === 'img') {
                        this.seekToClientPoint(e.clientX, e.clientY, true);
                    }
                }
            });
        }

        // Busca la nota/silencio más cercano al punto (clientX, clientY) de la pantalla y posiciona el cursor de interpretación
        seekToClientPoint(clientX, clientY, playPreviewIfPaused = true) {
            if (!this.reader.notes || this.reader.notes.length === 0) {
                this.scanCurrentScore();
            }
            const notes = this.reader.notes;
            if (!notes || notes.length === 0) return -1;

            let bestIdx = -1;
            let bestScore = Infinity;

            for (let i = 0; i < notes.length; i++) {
                const n = notes[i];
                const c = n.canvas || (this.currentContainer && this.currentContainer.querySelector('canvas:not(#music-interpreter-overlay)'));
                if (!c) continue;

                const rect = c.getBoundingClientRect();
                if (rect.width === 0 || rect.height === 0) continue;

                // Descartar páginas que están lejos verticalmente del clic
                if (clientY < rect.top - 80 || clientY > rect.bottom + 80) continue;

                const scaleX = rect.width / (c.width || rect.width);
                const scaleY = rect.height / (c.height || rect.height);
                const noteClientX = rect.left + n.x * scaleX;
                const noteClientY = rect.top + n.y * scaleY;

                const dy = Math.abs(clientY - noteClientY);
                const dx = Math.abs(clientX - noteClientX);

                // Priorizar fuertemente que sea el mismo pentagrama (distancia vertical Y) y luego la nota más cercana en X
                const distScore = dy * 4.8 + dx;
                if (distScore < bestScore) {
                    bestScore = distScore;
                    bestIdx = i;
                }
            }

            if (bestIdx >= 0) {
                this.seekToNoteIndex(bestIdx, playPreviewIfPaused);
            }
            return bestIdx;
        }

        seekToNoteIndex(targetIdx, playPreviewIfPaused = true) {
            if (!this.reader.notes || targetIdx < 0 || targetIdx >= this.reader.notes.length) return;

            this.currentIndex = targetIdx;
            this._manualPositionSet = true;
            const note = this.reader.notes[targetIdx];

            this.positionCursor(note);
            this.updateCurrentNoteUI(note);

            const elem = document.getElementById('mi-current-note');
            if (elem && !this.isPlaying) {
                elem.innerText = `📍 Cursor situado en Compás ${note.measure || 1}: ${note.symbol || '♩'} ${note.name || 'Silencio'} (Pulsa Play para continuar desde aquí)`;
            }

            if (this.isPlaying) {
                // Si está reproduciendo, saltar inmediatamente a esta posición y continuar secuencialmente desde ahí
                clearTimeout(this.playbackTimer);
                if (this.multiRestInterval) {
                    clearInterval(this.multiRestInterval);
                    this.multiRestInterval = null;
                }
                this.scheduleNextNote();
            } else if (playPreviewIfPaused && !note.isRest) {
                // Emitir breve referencia sonora de la nota donde se ha colocado el cursor
                const voices = this.resolveColumnNotes(note);
                for (const v of voices) {
                    this.synth.playNote(v.pitch, 0.28, this.selectedInstrument, this.applyTransposition, false, 0.72);
                }
            }
        }

        // Resuelve qué notas de una columna deben sonar (1 según instrumento/voz o todas a la vez en el mismo tiempo)
        resolveColumnNotes(note) {
            const allVoices = (note.chordNotes && note.chordNotes.length > 0)
                ? note.chordNotes
                : [{ pitch: note.pitch, midi: note.pitch, name: note.name, duration: note.duration }];

            if (allVoices.length <= 1) return allVoices;

            if (this.voiceMode === 'all') {
                return allVoices;
            }
            if (this.voiceMode === 'top') {
                return [allVoices[0]];
            }
            if (this.voiceMode === 'bottom') {
                return [allVoices[allVoices.length - 1]];
            }

            // Modo 'auto': según el instrumento seleccionado
            const prof = INSTRUMENT_PROFILES[this.selectedInstrument] || INSTRUMENT_PROFILES['clarinet'];
            if (prof.polyphonic) {
                return allVoices; // Instrumentos polifónicos (Lira, Marimba, Timbales, Percusión, Cuerda divisi) tocan el acorde a la vez
            }

            // Si el nombre del instrumento especifica 2º, 3º o Bajo, tocar la voz inferior; de lo contrario, la 1ª voz superior
            if (/\b(2|3|4|SEGUND|TERCER|BAJO)\b/i.test(this.rawInstrumentName)) {
                return [allVoices[allVoices.length - 1]];
            }
            return [allVoices[0]];
        }

        // Cursor DOM translúcido e interactivo (permite arrastrarlo o hacer clic en la partitura; nunca tapa la partitura ni crea fondos blancos)
        createDOMCursor() {
            if (this.cursorElement) {
                this.cursorElement.remove();
            }

            const cursor = document.createElement('div');
            cursor.id = 'music-interpreter-cursor';
            cursor.style.position = 'absolute';
            cursor.style.pointerEvents = 'auto';
            cursor.style.cursor = 'grab';
            cursor.style.touchAction = 'none';
            cursor.style.zIndex = '35';
            cursor.style.display = 'none';
            cursor.style.transition = 'top 0.12s ease-out, left 0.12s ease-out, width 0.12s ease-out, height 0.12s ease-out';
            cursor.style.border = '2px solid #f59e0b';
            cursor.style.backgroundColor = 'rgba(245, 158, 11, 0.22)';
            cursor.style.borderRadius = '6px';
            cursor.style.boxShadow = '0 0 16px rgba(245, 158, 11, 0.65)';
            cursor.title = 'Arrastra este cursor o haz clic en cualquier nota de la partitura para colocar el inicio/continuación de lectura';

            cursor.innerHTML = `
                <div id="music-cursor-badge" style="position: absolute; top: -20px; left: 50%; transform: translateX(-50%); background: #0f172a; color: #f59e0b; font-size: 11px; font-weight: bold; font-family: monospace; padding: 1px 6px; border-radius: 4px; border: 1px solid #f59e0b; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.5);">
                    ♩
                </div>
                <div style="position: absolute; top: -12px; bottom: -12px; left: 50%; width: 2px; background: rgba(255,255,255,0.9); transform: translateX(-50%); box-shadow: 0 0 4px #fff;"></div>
            `;

            if (this.currentContainer) {
                this.currentContainer.style.position = 'relative';
                this.currentContainer.appendChild(cursor);
                this.cursorElement = cursor;
            }
        }

        // Escanear partitura actual en el visor y recopilar diagnósticos de dificultad de lectura
        scanCurrentScore(forceRescan = false) {
            if (!this.currentContainer) return [];
            const prevActiveNote = (this.reader.notes && this.reader.notes[this.currentIndex])
                ? this.reader.notes[this.currentIndex]
                : null;

            const canvases = this.currentContainer.querySelectorAll('canvas:not(#music-interpreter-overlay)');
            let allNotes = [];
            let detectedScoreBpm = null;
            const collectedDiagnostics = [];

            if (canvases.length > 0) {
                canvases.forEach((c, idx) => {
                    if (forceRescan) {
                        delete c._scannedNotes;
                        delete c._scanDiagnostics;
                    }
                    const offsetTop = c.offsetTop || 0;
                    const pageNumber = parseInt(c.dataset?.pageNum || (idx + 1), 10);
                    const notes = this.reader.scanCanvas(c, pageNumber, this.selectedInstrument, offsetTop);
                    if (c._detectedBpm && !detectedScoreBpm) {
                        detectedScoreBpm = c._detectedBpm;
                    }
                    if (c._scanDiagnostics) {
                        collectedDiagnostics.push(c._scanDiagnostics);
                    }
                    allNotes.push(...notes);
                });
            } else {
                const img = this.currentContainer.querySelector('img');
                if (img && img.complete) {
                    const offCanvas = document.createElement('canvas');
                    offCanvas.width = img.naturalWidth || img.width;
                    offCanvas.height = img.naturalHeight || img.height;
                    const offCtx = offCanvas.getContext('2d');
                    offCtx.drawImage(img, 0, 0);
                    allNotes = this.reader.scanCanvas(offCanvas, 1, this.selectedInstrument, img.offsetTop || 0);
                    if (offCanvas._scanDiagnostics) {
                        collectedDiagnostics.push(offCanvas._scanDiagnostics);
                    }
                }
            }

            if (detectedScoreBpm && !this._userChangedBpm) {
                this.bpm = detectedScoreBpm;
                const inputBpm = document.getElementById('mi-input-bpm');
                const sliderBpm = document.getElementById('mi-slider-bpm');
                if (inputBpm) inputBpm.value = detectedScoreBpm;
                if (sliderBpm) sliderBpm.value = detectedScoreBpm;
            }

            // Conservar el índice de lectura actual cuando se terminan de renderizar páginas en segundo plano
            if (prevActiveNote && (this.isPlaying || this.isPaused || this._manualPositionSet) && allNotes.length > 0) {
                const matchedIdx = allNotes.findIndex(n =>
                    n.pageNum === prevActiveNote.pageNum &&
                    n.staffIndex === prevActiveNote.staffIndex &&
                    Math.abs(n.x - prevActiveNote.x) <= 6
                );
                if (matchedIdx >= 0) {
                    this.currentIndex = matchedIdx;
                }
            }

            this.diagnosticsList = collectedDiagnostics;
            this.reader.notes = allNotes;
            this.notes = allNotes;
            this.updateNotesBadge();
            this.updateDiagnosticsUI();
            this.renderInspectorList();
            return allNotes;
        }

        enableDragAndDrop() {
            window.addEventListener('dragover', (e) => e.preventDefault());
            window.addEventListener('drop', (e) => {
                e.preventDefault();
                const file = e.dataTransfer?.files?.[0];
                if (!file) return;

                const name = file.name.toLowerCase();
                if (name.endsWith('.xml') || name.endsWith('.musicxml')) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        try {
                            const notes = this.reader.parseMusicXML(ev.target.result);
                            alert(`MusicXML cargado con éxito: ${notes.length} notas y ligaduras exactas leídas.`);
                            this.updateNotesBadge();
                            this.renderInspectorList();
                        } catch (err) {
                            alert('Error al leer MusicXML: ' + err.message);
                        }
                    };
                    reader.readAsText(file);
                } else if (name.endsWith('.mid') || name.endsWith('.midi')) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        try {
                            const notes = this.reader.parseMidiFile(ev.target.result);
                            alert(`MIDI cargado con éxito: ${notes.length} notas leídas.`);
                            this.updateNotesBadge();
                            this.renderInspectorList();
                        } catch (err) {
                            alert('Error al leer MIDI: ' + err.message);
                        }
                    };
                    reader.readAsArrayBuffer(file);
                }
            });
        }

        play() {
            this.synth.initContext();
            if (this.reader.notes.length === 0) {
                this.scanCurrentScore();
            }

            if (this.reader.notes.length === 0) {
                alert('No se detectaron notas. Puedes importar un archivo MusicXML o MIDI, o probar la escala del instrumento.');
                return;
            }

            // Si el usuario NO ha colocado manualmente el cursor y ha hecho scroll hacia una página específica, comenzar desde el primer pentagrama visible en pantalla
            if (!this.isPaused && !this._manualPositionSet && this.currentIndex === 0 && window.scrollY > window.innerHeight * 0.45) {
                const scrollTargetY = window.scrollY;
                const firstVisibleIdx = this.reader.notes.findIndex(n => {
                    const c = n.canvas;
                    if (!c) return false;
                    const displayH = c.clientHeight || c.offsetHeight || c.height || 1;
                    const scaleY = displayH / (c.height || 1);
                    const notePageY = (c.offsetTop || 0) + (n.y * scaleY);
                    return notePageY >= scrollTargetY - 40;
                });
                if (firstVisibleIdx > 0) {
                    this.currentIndex = firstVisibleIdx;
                }
            }

            this.isPlaying = true;
            this.isPaused = false;
            this.updatePlayButtonUI(true);
            this.scheduleNextNote();
        }

        pause() {
            this.isPlaying = false;
            this.isPaused = true;
            clearTimeout(this.playbackTimer);
            if (this.multiRestInterval) {
                clearInterval(this.multiRestInterval);
                this.multiRestInterval = null;
            }
            this.updatePlayButtonUI(false);
        }

        stop() {
            this.isPlaying = false;
            this.isPaused = false;
            this.currentIndex = 0;
            this._manualPositionSet = false;
            clearTimeout(this.playbackTimer);
            if (this.multiRestInterval) {
                clearInterval(this.multiRestInterval);
                this.multiRestInterval = null;
            }
            this.hideCursor();
            this.updatePlayButtonUI(false);
            this.updateCurrentNoteUI(null);
        }

        scheduleNextNote() {
            if (!this.isPlaying) return;

            if (this.multiRestInterval) {
                clearInterval(this.multiRestInterval);
                this.multiRestInterval = null;
            }

            if (this.currentIndex >= this.reader.notes.length) {
                this.stop();
                return;
            }

            const note = this.reader.notes[this.currentIndex];
            const secondsPerBeat = 60 / this.bpm;
            const noteDurSeconds = (note.duration || 1.0) * secondsPerBeat;

            // Si la nota NO está ligada desde la anterior y NO es silencio, emitir sonido
            if (!note.tiedFromPrev && !note.isRest) {
                // Si tiene ligadura de prolongación (tiedToNext), sumar la duración de toda la cadena de notas ligadas para sostener el sonido continuamente
                let totalTiedBeats = note.duration || 1.0;
                let endVel = (typeof note.endVelocity === 'number') ? note.endVelocity : (note.velocity || 0.82);
                let hasHairpinInChain = !!note.hairpin;

                let lookIdx = this.currentIndex;
                while (this.reader.notes[lookIdx] && this.reader.notes[lookIdx].tiedToNext && this.reader.notes[lookIdx + 1]) {
                    lookIdx++;
                    const nextTied = this.reader.notes[lookIdx];
                    totalTiedBeats += (nextTied.duration || 1.0);
                    if (typeof nextTied.endVelocity === 'number') {
                        endVel = nextTied.endVelocity;
                    } else if (typeof nextTied.velocity === 'number') {
                        endVel = nextTied.velocity;
                    }
                    if (nextTied.hairpin) hasHairpinInChain = true;
                }

                let soundDurSeconds = totalTiedBeats * secondsPerBeat;
                if (note.staccato && !note.tiedToNext && !note.legato) {
                    soundDurSeconds = Math.max(0.06, soundDurSeconds * 0.44);
                } else if (!note.tiedToNext && !note.legato) {
                    soundDurSeconds = Math.max(0.06, soundDurSeconds * 0.92);
                }

                const startVel = note.velocity || (note.accent ? 0.95 : 0.82);
                const voicesToPlay = this.resolveColumnNotes(note);
                const mainPitch = voicesToPlay[0].pitch;

                // Construir secuencia de mordentes / notas de adorno que se ejecutan DENTRO del mismo tiempo que la nota principal
                const ornamentSeq = [];
                if (note.graceNotes && note.graceNotes.length > 0) {
                    for (const gn of note.graceNotes) {
                        ornamentSeq.push(gn.pitch);
                    }
                } else if (note.mordent) {
                    const auxPitch = note.mordent === 'lower' ? mainPitch - 2 : mainPitch + 2;
                    ornamentSeq.push(mainPitch, auxPitch);
                } else if (note.trill && soundDurSeconds >= 0.24) {
                    const upperPitch = mainPitch + 2;
                    const trCount = Math.min(6, Math.max(2, Math.floor(soundDurSeconds / 0.14)));
                    for (let t = 0; t < trCount; t++) {
                        ornamentSeq.push(t % 2 === 0 ? mainPitch : upperPitch);
                    }
                }

                let graceOffsetSec = 0;
                if (ornamentSeq.length > 0) {
                    const singleOrnDur = Math.min(0.075, (soundDurSeconds * 0.36) / ornamentSeq.length);
                    for (let gIdx = 0; gIdx < ornamentSeq.length; gIdx++) {
                        this.synth.playNote(
                            ornamentSeq[gIdx],
                            singleOrnDur * 0.95,
                            this.selectedInstrument,
                            this.applyTransposition,
                            true,
                            Math.max(0.35, startVel * 0.92),
                            { startTimeOffset: graceOffsetSec }
                        );
                        graceOffsetSec += singleOrnDur;
                    }
                }

                const principalDurSec = Math.max(0.05, soundDurSeconds - graceOffsetSec);
                const hairpinOpts = (hasHairpinInChain && Math.abs(endVel - startVel) > 0.04)
                    ? { startVel: startVel, endVel: endVel }
                    : null;

                // Tocar simultáneamente las notas resueltas de la columna en el mismo tiempo
                const voiceGainScale = voicesToPlay.length > 1 ? (1 / Math.pow(voicesToPlay.length, 0.35)) : 1.0;
                for (const vNote of voicesToPlay) {
                    const vStartVel = Math.min(1.0, startVel * voiceGainScale);
                    const vHairpin = hairpinOpts
                        ? { startVel: vStartVel, endVel: Math.min(1.0, hairpinOpts.endVel * voiceGainScale) }
                        : null;

                    this.synth.playNote(
                        vNote.pitch,
                        principalDurSec,
                        this.selectedInstrument,
                        this.applyTransposition,
                        note.legato || note.tiedToNext || false,
                        vStartVel,
                        { startTimeOffset: graceOffsetSec, hairpin: vHairpin }
                    );
                }
            }

            // Metrónomo en compás
            if (this.metronomeEnabled && !note.tiedFromPrev) {
                this.synth.playMetronomeClick(note.duration >= 2.0 || this.currentIndex === 0);
            }

            // Mover cursor visual a la nota o silencio
            this.positionCursor(note);
            this.updateCurrentNoteUI(note);

            // Si es un silencio con número de repetición (silencio multicompás), actualizar el contador compás por compás y deslizar el cursor sobre la barra
            if (note.isRest && (note.repeatMeasures || 1) > 1) {
                const totalMeasures = note.repeatMeasures;
                const beatsPerM = note.beatsPerMeasure || (note.duration / totalMeasures) || 2.0;
                const msPerMeasure = beatsPerM * secondsPerBeat * 1000;
                let currentM = 1;

                const updateMultiRestProgress = (mStep) => {
                    const badge = document.getElementById('music-cursor-badge');
                    if (badge) {
                        badge.innerText = `𝄺×${totalMeasures} Silencio (Compás ${mStep} de ${totalMeasures})`;
                    }
                    const elem = document.getElementById('mi-current-note');
                    if (elem) {
                        elem.innerText = `🤫 Silencio multicompás: Compás ${mStep} / ${totalMeasures} (${note.duration}t)`;
                    }
                    if (note.hbarX0 !== undefined && note.hbarX1 !== undefined && totalMeasures > 1) {
                        const ratio = (mStep - 1) / Math.max(1, totalMeasures - 1);
                        const stepX = note.hbarX0 + (note.hbarX1 - note.hbarX0) * ratio;
                        this.positionCursor({ ...note, x: stepX, w: Math.max(24, (note.w || 40) / Math.min(4, totalMeasures)) }, mStep);
                    }
                };

                updateMultiRestProgress(1);
                this.multiRestInterval = setInterval(() => {
                    if (!this.isPlaying) return;
                    currentM++;
                    if (currentM <= totalMeasures) {
                        updateMultiRestProgress(currentM);
                        if (this.metronomeEnabled) {
                            this.synth.playMetronomeClick(true);
                        }
                    }
                }, msPerMeasure);
            }

            this.currentIndex++;
            this.playbackTimer = setTimeout(() => {
                this.scheduleNextNote();
            }, noteDurSeconds * 1000);
        }

        positionCursor(note, multiRestStep = null) {
            if (!this.cursorElement || !this.currentContainer) return;

            const prof = INSTRUMENT_PROFILES[this.selectedInstrument] || INSTRUMENT_PROFILES['clarinet'];
            const color = prof.color || '#f59e0b';
            const c = note.canvas || this.currentContainer.querySelector('canvas:not(#music-interpreter-overlay)');

            const boxW = Math.max(20, note.w || 22);
            const boxH = Math.max(24, (note.h || 20) * 1.45);
            let left = note.x - boxW * 0.35;
            let top = (note.canvasOffsetTop || 0) + note.y - boxH * 0.55;
            let width = boxW;
            let height = boxH;

            if (c && c.width > 0 && c.height > 0) {
                const displayW = c.clientWidth || c.offsetWidth || c.width;
                const displayH = c.clientHeight || c.offsetHeight || c.height;
                const scaleX = displayW / c.width;
                const scaleY = displayH / c.height;

                const canvasLeft = c.offsetLeft || 0;
                const canvasTop = c.offsetTop || 0;

                width = Math.max(16, boxW * scaleX);
                height = Math.max(20, boxH * scaleY);
                left = canvasLeft + (note.x * scaleX) - (width * 0.35);
                top = canvasTop + (note.y * scaleY) - (height * 0.55);
            }

            const cursorColor = note.isRest ? '#94a3b8' : color;
            this.cursorElement.style.top = `${Math.round(top)}px`;
            this.cursorElement.style.left = `${Math.round(left)}px`;
            this.cursorElement.style.width = `${Math.round(width)}px`;
            this.cursorElement.style.height = `${Math.round(height)}px`;
            this.cursorElement.style.borderColor = cursorColor;
            this.cursorElement.style.borderStyle = note.isRest ? 'dashed' : 'solid';
            this.cursorElement.style.boxShadow = note.isRest ? 'none' : `0 0 16px ${cursorColor}99`;
            this.cursorElement.style.backgroundColor = note.isRest ? 'rgba(148, 163, 184, 0.18)' : `${cursorColor}33`;
            this.cursorElement.style.display = 'block';

            const badge = document.getElementById('music-cursor-badge');
            if (badge) {
                if (note.isRest && (note.repeatMeasures || 1) > 1) {
                    const stepLabel = multiRestStep ? ` (${multiRestStep}/${note.repeatMeasures})` : '';
                    badge.innerText = `${note.symbol || '𝄺'} Silencio${stepLabel}`;
                } else if (note.isRest) {
                    badge.innerText = `${note.symbol || '𝄽'} Silencio`;
                } else {
                    const resolved = this.resolveColumnNotes(note);
                    const dispName = (note.isChord && resolved.length === 1)
                        ? `${resolved[0].name} (1ª/2ª voz)`
                        : (note.name || '');
                    const dynInfo = note.hairpin === 'cresc' ? ' < cresc.' : (note.hairpin === 'dim' ? ' > dim.' : '');
                    badge.innerText = `${note.symbol || '♩'} ${dispName}${dynInfo}`;
                }
                badge.style.color = cursorColor;
                badge.style.borderColor = cursorColor;
            }

            // Auto-scroll suave
            const viewportH = window.innerHeight;
            if (top < window.scrollY + 100 || top > window.scrollY + viewportH - 200) {
                window.scrollTo({
                    top: Math.max(0, top - viewportH * 0.35),
                    behavior: 'smooth'
                });
            }
        }

        hideCursor() {
            if (this.cursorElement) {
                this.cursorElement.style.display = 'none';
            }
        }

        // ---------------------------------------------------------
        // RENDER DE LA BARRA FLOTANTE DEL INTÉRPRETE
        // ---------------------------------------------------------
        renderDockUI() {
            const existing = document.getElementById('music-interpreter-dock');
            if (existing) existing.remove();

            const dock = document.createElement('div');
            dock.id = 'music-interpreter-dock';
            dock.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center max-w-[96vw] sm:max-w-5xl w-full px-2';

            dock.innerHTML = `
                <div class="bg-gray-900/95 backdrop-blur-xl border border-indigo-500/40 shadow-2xl rounded-2xl p-3 w-full text-white ring-1 ring-white/10 transition-all">
                    <!-- Fila Superior: Controles Principales -->
                    <div class="flex flex-wrap items-center justify-between gap-2.5">
                        
                        <!-- Botones de Reproducción y Posicionamiento Manual del Cursor -->
                        <div class="flex items-center gap-1 sm:gap-1.5">
                            <button id="mi-btn-prev" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Retroceder cursor a la nota anterior (también puedes hacer clic directo sobre cualquier nota de la partitura)">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8.445 14.832A1 1 0 0010 14v-2.798l5.445 3.63A1 1 0 0017 14V6a1 1 0 00-1.555-.832L10 8.798V6a1 1 0 00-1.555-.832l-6 4a1 1 0 000 1.664l6 4z"/></svg>
                            </button>
                            <button id="mi-btn-play" class="flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white shadow-lg shadow-indigo-500/30 transition transform active:scale-95" title="Reproducir o continuar desde la posición actual del cursor">
                                <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                            </button>
                            <button id="mi-btn-next" class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Avanzar cursor a la nota siguiente (también puedes hacer clic directo sobre cualquier nota de la partitura)">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.555 5.168A1 1 0 003 6v8a1 1 0 001.555.832L10 11.202V14a1 1 0 001.555.832l6-4a1 1 0 000-1.664l-6-4A1 1 0 0010 6v2.798L4.555 5.168z"/></svg>
                            </button>
                            <button id="mi-btn-stop" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Detener y volver al inicio">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><rect x="4" y="4" width="12" height="12" rx="2"/></svg>
                            </button>
                            <button id="mi-btn-scan" class="flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition shadow-sm" title="Re-escanear y leer notas, figuras, acordes, mordentes y ligaduras de la partitura">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span class="hidden sm:inline">Re-escanear</span>
                            </button>
                        </div>

                        <!-- Selector de Instrumento, Motor de Sonido (Real vs MIDI) y Modo de Voces -->
                        <div class="flex flex-wrap items-center gap-1.5">
                            <select id="mi-select-inst" class="bg-gray-800 border border-gray-700 rounded-lg text-xs font-semibold text-white px-2 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none max-w-[155px] sm:max-w-[185px]" title="Instrumento de la banda">
                                ${Object.keys(INSTRUMENT_PROFILES).map(k => `
                                    <option value="${k}" ${k === this.selectedInstrument ? 'selected' : ''}>
                                        ${INSTRUMENT_PROFILES[k].name} (${INSTRUMENT_PROFILES[k].family})
                                    </option>
                                `).join('')}
                            </select>

                            <select id="mi-select-soundmode" class="bg-gray-800 border border-emerald-500/40 rounded-lg text-[11px] font-semibold text-emerald-300 px-2 py-1.5 focus:ring-2 focus:ring-emerald-500 focus:outline-none" title="Elegir entre Muestras de Instrumento Real (SoundFont HQ) o Sintetizador MIDI">
                                <option value="real" ${this.synth.soundEngine === 'real' ? 'selected' : ''}>🎻 Sonido Real (Muestras HQ)</option>
                                <option value="midi" ${this.synth.soundEngine === 'midi' ? 'selected' : ''}>🎹 Sonido MIDI (Sintetizador)</option>
                            </select>

                            <select id="mi-select-voicemode" class="bg-gray-800 border border-gray-700 rounded-lg text-[11px] font-medium text-indigo-200 px-2 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none" title="Comportamiento cuando hay 2 o más notas en la misma columna (tiempo)">
                                <option value="auto" selected>Voces: Auto (según inst.)</option>
                                <option value="all">Voces: Todas a la vez (Acorde)</option>
                                <option value="top">Voces: Solo 1ª Voz (Superior)</option>
                                <option value="bottom">Voces: Solo 2ª Voz (Inferior)</option>
                            </select>
                        </div>

                        <!-- Tempo (BPM), Metrónomo, Figuras y Diagnóstico -->
                        <div class="flex items-center gap-1.5">
                            <div class="flex items-center gap-1 bg-gray-800 px-2 py-1 rounded-lg border border-gray-700">
                                <span class="text-[11px] text-gray-400 font-mono">BPM:</span>
                                <input id="mi-input-bpm" type="number" min="40" max="220" value="${this.bpm}" class="w-11 bg-transparent text-xs font-bold text-center text-white focus:outline-none">
                                <input id="mi-slider-bpm" type="range" min="40" max="200" value="${this.bpm}" class="w-14 accent-indigo-500 cursor-pointer hidden md:inline-block">
                            </div>

                            <button id="mi-btn-metro" class="p-1.5 rounded-lg border border-gray-700 text-xs text-gray-400 hover:text-white hover:bg-gray-800 transition" title="Activar/Desactivar Metrónomo">
                                ⏱
                            </button>

                            <button id="mi-btn-inspector" class="flex items-center gap-1 px-2 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-amber-300 font-medium border border-gray-700 transition" title="Ver e inspeccionar figuras rítmicas, acordes, mordentes y notas">
                                <span>🎼 Figuras</span>
                            </button>

                            <button id="mi-btn-diagnostics" class="flex items-center gap-1 px-2 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-cyan-300 font-medium border border-gray-700 transition" title="Ver diagnóstico de dificultades de lectura de la partitura">
                                <span>🔍 Lectura</span>
                            </button>

                            <button id="mi-btn-tools" class="p-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Opciones avanzadas (Cargar MusicXML/MIDI, afinación, descarga)">
                                ⚙️
                            </button>
                        </div>
                    </div>

                    <!-- Fila Inferior Informativa -->
                    <div class="mt-2 pt-2 border-t border-gray-800 flex flex-wrap items-center justify-between gap-2 text-[11px] text-gray-400">
                        <div class="flex flex-wrap items-center gap-2">
                            <span id="mi-badge-status" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-300 border border-amber-500/30 font-medium" title="Esta funcionalidad está en desarrollo experimental">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Intérprete (Experimental / En desarrollo)
                            </span>
                            <span id="mi-badge-notes" class="text-gray-300 font-mono font-semibold">0 notas leídas</span>
                            <button id="mi-badge-diagnostics" class="hidden items-center gap-1 px-2 py-0.5 rounded-full bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 font-medium hover:bg-cyan-500/25 transition" title="Haz clic para ver detalles sobre la lectura de esta partitura">
                            </button>
                        </div>
                        <div id="mi-current-note" class="font-mono text-amber-400 font-bold truncate max-w-[380px]" title="Puedes hacer clic o tocar cualquier punto de la partitura para colocar el cursor de lectura donde quieras">
                            💡 Toca cualquier nota de la partitura para situar el cursor o pulsa Play
                        </div>
                    </div>

                    <!-- Drawer de Diagnóstico de Lectura de la Partitura -->
                    <div id="mi-drawer-diagnostics" class="hidden mt-3 pt-3 border-t border-gray-800 max-h-52 overflow-y-auto text-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-cyan-400 flex items-center gap-1.5">
                                🔍 Diagnóstico de Lectura e Interpretación de la Partitura:
                            </span>
                            <button id="mi-btn-copy-diag" class="px-2 py-0.5 rounded bg-gray-800 hover:bg-gray-700 text-[10px] text-gray-300 border border-gray-700 transition">
                                📋 Copiar informe para perfeccionar
                            </button>
                        </div>
                        <div id="mi-diagnostics-content" class="space-y-1.5 text-[11px] text-gray-300"></div>
                    </div>

                    <!-- Drawer de Inspector de Figuras y Notas -->
                    <div id="mi-drawer-inspector" class="hidden mt-3 pt-3 border-t border-gray-800 max-h-48 overflow-y-auto">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-amber-400 flex items-center gap-1.5">
                                🎼 Desglose Rítmico y Notas Detectadas:
                            </span>
                            <span class="text-[10px] text-gray-400">Haz clic en cualquier nota para escucharla</span>
                        </div>
                        <div id="mi-inspector-list" class="flex flex-wrap gap-1.5"></div>
                    </div>

                    <!-- Panel Desplegable de Herramientas Avanzadas -->
                    <div id="mi-panel-tools" class="hidden mt-3 pt-3 border-t border-gray-800 space-y-2 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <!-- Afinación / Transposición -->
                            <div class="bg-gray-800/80 p-2.5 rounded-lg border border-gray-700">
                                <label class="block text-[11px] font-bold text-gray-300 mb-1">Afinación del Instrumento:</label>
                                <label class="flex items-center gap-1.5 cursor-pointer text-gray-300 text-[11px]">
                                    <input type="checkbox" id="mi-chk-transpose" class="rounded bg-gray-900 border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <span>Tocar afinación transpuesta (Sib/Mib/Fa)</span>
                                </label>
                            </div>

                            <!-- Importar / Exportar Archivos -->
                            <div class="bg-gray-800/80 p-2.5 rounded-lg border border-gray-700 flex flex-col gap-1.5">
                                <span class="text-[11px] font-bold text-gray-300">Cargar Archivo Digital:</span>
                                <div class="flex items-center gap-1.5">
                                    <label class="cursor-pointer bg-indigo-600 hover:bg-indigo-500 text-white px-2 py-1 rounded text-[11px] font-semibold text-center flex-1 truncate" title="Cargar MusicXML (.musicxml / .xml) o MIDI (.mid)">
                                        Cargar XML / MIDI
                                        <input type="file" id="mi-file-music" accept=".xml,.musicxml,.mid,.midi" class="hidden">
                                    </label>
                                    <button id="mi-btn-export-midi" class="bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-[11px] font-semibold flex-1 truncate">
                                        Descargar MIDI
                                    </button>
                                </div>
                            </div>

                            <!-- Probar Escala y Tesitura -->
                            <div class="bg-gray-800/80 p-2.5 rounded-lg border border-gray-700 flex flex-col justify-between">
                                <span class="text-[11px] font-bold text-gray-300">Prueba Sonora:</span>
                                <button id="mi-btn-demo" class="bg-emerald-600 hover:bg-emerald-500 text-white px-2 py-1 rounded text-[11px] font-semibold">
                                    🎵 Escala del Instrumento
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(dock);
            this.bindDockEvents();
        }

        bindDockEvents() {
            const btnPrev = document.getElementById('mi-btn-prev');
            const btnPlay = document.getElementById('mi-btn-play');
            const btnNext = document.getElementById('mi-btn-next');
            const btnStop = document.getElementById('mi-btn-stop');
            const btnScan = document.getElementById('mi-btn-scan');
            const selectInst = document.getElementById('mi-select-inst');
            const selectSoundMode = document.getElementById('mi-select-soundmode');
            const selectVoiceMode = document.getElementById('mi-select-voicemode');
            const inputBpm = document.getElementById('mi-input-bpm');
            const sliderBpm = document.getElementById('mi-slider-bpm');
            const btnMetro = document.getElementById('mi-btn-metro');
            const btnInspector = document.getElementById('mi-btn-inspector');
            const drawerInspector = document.getElementById('mi-drawer-inspector');
            const btnDiagnostics = document.getElementById('mi-btn-diagnostics');
            const badgeDiagnostics = document.getElementById('mi-badge-diagnostics');
            const drawerDiagnostics = document.getElementById('mi-drawer-diagnostics');
            const btnCopyDiag = document.getElementById('mi-btn-copy-diag');
            const btnTools = document.getElementById('mi-btn-tools');
            const panelTools = document.getElementById('mi-panel-tools');
            const chkTranspose = document.getElementById('mi-chk-transpose');
            const fileMusic = document.getElementById('mi-file-music');
            const btnExportMidi = document.getElementById('mi-btn-export-midi');
            const btnDemo = document.getElementById('mi-btn-demo');

            if (btnPrev) {
                btnPrev.addEventListener('click', () => {
                    if (!this.reader.notes || this.reader.notes.length === 0) this.scanCurrentScore();
                    const prevIdx = Math.max(0, (this.isPlaying ? this.currentIndex - 2 : this.currentIndex - 1));
                    this.seekToNoteIndex(prevIdx, true);
                });
            }

            if (btnNext) {
                btnNext.addEventListener('click', () => {
                    if (!this.reader.notes || this.reader.notes.length === 0) this.scanCurrentScore();
                    const nextIdx = Math.min((this.reader.notes.length || 1) - 1, (this.isPlaying ? this.currentIndex : this.currentIndex + 1));
                    this.seekToNoteIndex(nextIdx, true);
                });
            }

            btnPlay.addEventListener('click', () => {
                if (this.isPlaying) this.pause();
                else this.play();
            });

            btnStop.addEventListener('click', () => this.stop());

            btnScan.addEventListener('click', () => {
                const notes = this.scanCurrentScore(true);
                alert(`¡Lectura completada! Se han reconocido ${notes.length} eventos musicales (notas, acordes, mordentes, silencios multicompás, ligaduras y reguladores).`);
            });

            selectInst.addEventListener('change', (e) => {
                this.selectedInstrument = e.target.value;
                if (this.synth.soundEngine === 'real') {
                    this.synth.preloadSoundfont(this.selectedInstrument);
                }
                this.updateNotesBadge();
            });

            if (selectSoundMode) {
                selectSoundMode.addEventListener('change', (e) => {
                    const mode = e.target.value;
                    this.synth.setSoundEngine(mode);
                    if (mode === 'real') {
                        this.synth.preloadSoundfont(this.selectedInstrument);
                    }
                });
            }

            if (selectVoiceMode) {
                selectVoiceMode.addEventListener('change', (e) => {
                    this.voiceMode = e.target.value;
                });
            }

            const updateBpm = (val) => {
                const bpmVal = Math.max(40, Math.min(220, parseInt(val) || 100));
                this.bpm = bpmVal;
                this._userChangedBpm = true;
                inputBpm.value = bpmVal;
                sliderBpm.value = bpmVal;
            };

            inputBpm.addEventListener('change', (e) => updateBpm(e.target.value));
            sliderBpm.addEventListener('input', (e) => updateBpm(e.target.value));

            btnMetro.addEventListener('click', () => {
                this.metronomeEnabled = !this.metronomeEnabled;
                btnMetro.classList.toggle('bg-indigo-600', this.metronomeEnabled);
                btnMetro.classList.toggle('text-white', this.metronomeEnabled);
            });

            btnInspector.addEventListener('click', () => {
                drawerInspector.classList.toggle('hidden');
                this.renderInspectorList();
            });

            const toggleDiagnostics = () => {
                if (!drawerDiagnostics) return;
                drawerDiagnostics.classList.toggle('hidden');
                this.updateDiagnosticsUI();
            };

            if (btnDiagnostics) btnDiagnostics.addEventListener('click', toggleDiagnostics);
            if (badgeDiagnostics) badgeDiagnostics.addEventListener('click', toggleDiagnostics);

            if (btnCopyDiag) {
                btnCopyDiag.addEventListener('click', () => {
                    const summaryText = this.buildDiagnosticsReportText();
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(summaryText).then(() => {
                            btnCopyDiag.innerText = '✅ Informe copiado';
                            setTimeout(() => { btnCopyDiag.innerText = '📋 Copiar informe para perfeccionar'; }, 2000);
                        });
                    } else {
                        prompt('Copia este informe de lectura de la partitura:', summaryText);
                    }
                });
            }

            btnTools.addEventListener('click', () => {
                panelTools.classList.toggle('hidden');
            });

            chkTranspose.addEventListener('change', (e) => {
                this.applyTransposition = e.target.checked;
            });

            fileMusic.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const name = file.name.toLowerCase();
                if (name.endsWith('.xml') || name.endsWith('.musicxml')) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        try {
                            const notes = this.reader.parseMusicXML(ev.target.result);
                            alert(`MusicXML cargado: ${notes.length} notas procesadas con ligaduras y figuras exactas.`);
                            this.updateNotesBadge();
                            this.renderInspectorList();
                        } catch (err) {
                            alert('Error al leer MusicXML: ' + err.message);
                        }
                    };
                    reader.readAsText(file);
                } else {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        try {
                            const notes = this.reader.parseMidiFile(ev.target.result);
                            alert(`MIDI cargado: ${notes.length} notas leídas.`);
                            this.updateNotesBadge();
                            this.renderInspectorList();
                        } catch (err) {
                            alert('Error al leer MIDI: ' + err.message);
                        }
                    };
                    reader.readAsArrayBuffer(file);
                }
            });

            btnExportMidi.addEventListener('click', () => {
                if (this.reader.notes.length === 0) {
                    alert('No hay notas para exportar. Escanea la partitura primero.');
                    return;
                }
                const blob = this.reader.exportToMidiBlob(this.bpm);
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = `partitura_${this.selectedInstrument}.mid`;
                a.click();
            });

            btnDemo.addEventListener('click', () => {
                const prof = INSTRUMENT_PROFILES[this.selectedInstrument] || INSTRUMENT_PROFILES['clarinet'];
                this.reader.notes = this.reader.generateDemoScore(prof);
                this.updateNotesBadge();
                this.renderInspectorList();
                this.play();
            });
        }

        buildDiagnosticsReportText() {
            if (!this.diagnosticsList || this.diagnosticsList.length === 0) {
                return 'Sin datos de diagnóstico aún. Pulsa Re-escanear o Reproducir.';
            }
            const lines = [];
            lines.push(`=== Diagnóstico del Intérprete (${this.rawInstrumentName}) ===`);
            for (const d of this.diagnosticsList) {
                lines.push(`Pág. ${d.page} [Motor: ${d.engine}, Confianza: ${d.confidence}%]: Pentagramas=${d.stats?.staves || 0}, Eventos=${d.stats?.notes || 0}, Acordes/Divisi=${d.stats?.chords || 0}, SilenciosMulticompás=${d.stats?.multiRests || 0}, Mordentes/Adornos=${d.stats?.graceAndOrnaments || 0}, Ligaduras=${d.stats?.tiesAndSlurs || 0}, Reguladores=${d.stats?.hairpins || 0}`);
                if (d.warnings && d.warnings.length > 0) {
                    for (const w of d.warnings) {
                        lines.push(`  - Aviso: ${w}`);
                    }
                }
            }
            return lines.join('\n');
        }

        updateDiagnosticsUI() {
            const badge = document.getElementById('mi-badge-diagnostics');
            const content = document.getElementById('mi-diagnostics-content');
            if (!badge || !content) return;

            if (!this.diagnosticsList || this.diagnosticsList.length === 0) {
                badge.classList.add('hidden');
                content.innerHTML = '<span class="text-gray-400 italic">Escanea o reproduce la partitura para analizar posibles dificultades de lectura.</span>';
                return;
            }

            const avgConf = Math.round(this.diagnosticsList.reduce((acc, d) => acc + (d.confidence || 80), 0) / this.diagnosticsList.length);
            const allWarnings = this.diagnosticsList.reduce((acc, d) => acc.concat(d.warnings || []), []);
            const usesRaster = this.diagnosticsList.some(d => d.engine === 'raster-omr');

            const totals = this.diagnosticsList.reduce((acc, d) => {
                acc.staves += (d.stats?.staves || 0);
                acc.chords += (d.stats?.chords || 0);
                acc.multiRests += (d.stats?.multiRests || 0);
                acc.grace += (d.stats?.graceAndOrnaments || 0);
                acc.ties += (d.stats?.tiesAndSlurs || 0);
                acc.hairpins += (d.stats?.hairpins || 0);
                return acc;
            }, { staves: 0, chords: 0, multiRests: 0, grace: 0, ties: 0, hairpins: 0 });

            badge.classList.remove('hidden');
            badge.classList.add('inline-flex');

            if (usesRaster || avgConf < 75) {
                badge.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 font-semibold hover:bg-rose-500/30 transition';
                badge.innerHTML = `⚠️ Dificultad de lectura detectada (${avgConf}%)`;
            } else if (allWarnings.length > 0) {
                badge.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-cyan-500/15 text-cyan-300 border border-cyan-500/30 font-medium hover:bg-cyan-500/25 transition';
                badge.innerHTML = `🔍 Lectura ${avgConf}% (${allWarnings.length} aviso${allWarnings.length > 1 ? 's' : ''})`;
            } else {
                badge.className = 'inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-300 border border-emerald-500/30 font-medium hover:bg-emerald-500/25 transition';
                badge.innerHTML = `✅ Lectura óptima (${avgConf}%)`;
            }

            let html = `
                <div class="grid grid-cols-2 sm:grid-cols-6 gap-1.5 p-2 rounded-lg bg-gray-800/90 border border-gray-700 text-center">
                    <div><div class="text-[10px] text-gray-400">Pentagramas</div><div class="font-bold text-white">${totals.staves}</div></div>
                    <div><div class="text-[10px] text-gray-400">Acordes/Divisi</div><div class="font-bold text-indigo-300">${totals.chords}</div></div>
                    <div><div class="text-[10px] text-gray-400">Silencios ×N</div><div class="font-bold text-amber-300">${totals.multiRests}</div></div>
                    <div><div class="text-[10px] text-gray-400">Mordentes/Tr</div><div class="font-bold text-pink-300">${totals.grace}</div></div>
                    <div><div class="text-[10px] text-gray-400">Ligaduras</div><div class="font-bold text-emerald-300">${totals.ties}</div></div>
                    <div><div class="text-[10px] text-gray-400">Reguladores &lt; &gt;</div><div class="font-bold text-cyan-300">${totals.hairpins}</div></div>
                </div>
            `;

            if (allWarnings.length > 0) {
                html += `<div class="mt-2 space-y-1">`;
                for (const w of allWarnings) {
                    html += `<div class="flex items-start gap-1.5 text-amber-200 bg-amber-950/40 border border-amber-500/30 rounded px-2 py-1">
                        <span>⚠️</span>
                        <span>${w}</span>
                    </div>`;
                }
                html += `</div>`;
            } else {
                html += `<div class="mt-2 text-emerald-300 bg-emerald-950/30 border border-emerald-500/30 rounded px-2 py-1">
                    ✅ Todos los pentagramas, compases y símbolos se han leído con alta precisión vectorial.
                </div>`;
            }

            content.innerHTML = html;
        }

        renderInspectorList() {
            const list = document.getElementById('mi-inspector-list');
            const drawer = document.getElementById('mi-drawer-inspector');
            if (!list || (drawer && drawer.classList.contains('hidden'))) return;

            list.innerHTML = '';
            if (this.reader.notes.length === 0) {
                list.innerHTML = '<span class="text-gray-500 text-xs italic">Escanea la partitura o carga un archivo para ver el desglose rítmico.</span>';
                return;
            }

            const startIdx = Math.max(0, this.currentIndex - 20);
            const endIdx = Math.min(this.reader.notes.length, startIdx + 300);

            for (let idx = startIdx; idx < endIdx; idx++) {
                const note = this.reader.notes[idx];
                const chip = document.createElement('button');
                chip.className = `px-2 py-1 rounded text-[11px] font-mono border transition flex items-center gap-1 ${
                    note.tiedFromPrev 
                        ? 'bg-purple-900/40 border-purple-500/50 text-purple-300' 
                        : (note.isChord
                            ? 'bg-indigo-900/40 border-indigo-500/50 text-indigo-200'
                            : (note.duration >= 2.0 
                                ? 'bg-emerald-900/40 border-emerald-500/50 text-emerald-300' 
                                : 'bg-gray-800 border-gray-700 text-gray-200 hover:border-amber-400'))
                }`;

                const graceBadge = (note.graceNotes && note.graceNotes.length > 0) ? `<span class="text-pink-400" title="Con mordente/adorno">𝄽</span>` : '';
                const hairpinBadge = note.hairpin === 'cresc' ? '<span class="text-cyan-400" title="Crescendo">&lt;</span>' : (note.hairpin === 'dim' ? '<span class="text-cyan-400" title="Disminuyendo">&gt;</span>' : '');

                chip.innerHTML = `
                    <span class="text-sm font-bold">${note.symbol || '♩'}</span>
                    ${graceBadge}
                    <span>${note.name || 'Nota'}</span>
                    ${hairpinBadge}
                    <span class="text-[9px] text-gray-400">(${Math.round(note.duration * 100) / 100}t)</span>
                `;

                chip.addEventListener('click', () => {
                    this.currentIndex = idx;
                    if (!note.isRest) {
                        const voices = this.resolveColumnNotes(note);
                        for (const v of voices) {
                            this.synth.playNote(v.pitch, (note.duration || 1) * (60 / this.bpm), this.selectedInstrument, this.applyTransposition);
                        }
                    }
                    this.positionCursor(note);
                    this.updateCurrentNoteUI(note);
                });

                list.appendChild(chip);
            }
        }

        updatePlayButtonUI(isPlaying) {
            const btn = document.getElementById('mi-btn-play');
            if (!btn) return;
            if (isPlaying) {
                btn.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                btn.classList.add('animate-pulse');
            } else {
                btn.innerHTML = '<svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>';
                btn.classList.remove('animate-pulse');
            }
        }

        updateNotesBadge() {
            const badge = document.getElementById('mi-badge-notes');
            if (badge) {
                const chordCount = this.reader.notes.filter(n => n.isChord).length;
                const multiRestCount = this.reader.notes.filter(n => n.isRest && (n.repeatMeasures || 1) > 1).length;
                let extra = '';
                if (chordCount > 0) extra += ` · ${chordCount} acordes`;
                if (multiRestCount > 0) extra += ` · ${multiRestCount} silencios×N`;
                badge.innerText = `${this.reader.notes.length} eventos leídos${extra}`;
            }
        }

        updateCurrentNoteUI(note) {
            const elem = document.getElementById('mi-current-note');
            if (!elem) return;
            if (note) {
                const tiedStr = note.tiedFromPrev ? ' [⌒ Ligada]' : (note.legato ? ' [Legato]' : '');
                const hpStr = note.hairpin === 'cresc' ? ' [< cresc.]' : (note.hairpin === 'dim' ? ' [> dim.]' : '');
                const dynStr = note.dynamic ? ` (${note.dynamic})` : '';
                elem.innerText = `🎵 ${note.symbol || '♩'} ${note.name || 'Nota'}${dynStr} - ${note.figure || ''}${tiedStr}${hpStr} (${note.duration || 1}t)`;
            } else {
                elem.innerText = 'Listo';
            }
        }
    }

    // Exponer globalmente
    window.MusicInterpreter = {
        PROFILES: INSTRUMENT_PROFILES,
        matchProfile: matchInstrumentProfile,
        Synth: InstrumentSynth,
        Reader: SheetMusicReader,
        Controller: MusicInterpreterController,
        createInstance: function (container, instrumentName) {
            const ctrl = new MusicInterpreterController();
            ctrl.init(container, instrumentName);
            return ctrl;
        }
    };

})(window);
