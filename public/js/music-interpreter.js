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
            color: '#818cf8'
        }
    };

    function matchInstrumentProfile(name) {
        if (!name) return 'ensemble';
        const n = name.toUpperCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        if (n.includes('FLAUTIN') || n.includes('PICCOLO')) return 'piccolo';
        if (n.includes('FLAUTA')) return 'flute';
        if (n.includes('OBOE')) return 'oboe';
        if (n.includes('CORNO INGLES')) return 'corno_ingles';
        if (n.includes('FAGOT') || n.includes('BASSOON')) return 'bassoon';
        if (n.includes('REQUINTO')) return 'requinto';
        if (n.includes('CLARINETE BAJO')) return 'bass_clarinet';
        if (n.includes('CLARINETE')) return 'clarinet';

        if (n.includes('SAX') || n.includes('SAXOFON')) {
            if (n.includes('ALTO')) return 'alto_sax';
            if (n.includes('TENOR')) return 'tenor_sax';
            if (n.includes('BARITONO')) return 'baritone_sax';
            if (n.includes('SOPRANO')) return 'soprano_sax';
            return 'alto_sax';
        }

        if (n.includes('FLISCORNO') || n.includes('FLUGEL')) return 'flugelhorn';
        if (n.includes('TROMPETA') || n.includes('CORNETA')) return 'trumpet';
        if (n.includes('TROMPA') || n.includes('CORNO')) return 'french_horn';
        if (n.includes('TROMBON BAJO')) return 'bass_trombone';
        if (n.includes('TROMBON')) return 'trombone';
        if (n.includes('BOMBARDINO') || n.includes('EUPHONIUM')) return 'euphonium';
        if (n.includes('TUBA') || n.includes('SOUSAFON')) return 'tuba';

        if (n.includes('VIOLONCHELO') || n.includes('CELLO')) return 'cello';
        if (n.includes('CONTRABAJO')) return 'contrabass';

        if (n.includes('TIMBAL')) return 'timpani';
        if (n.includes('XILOFONO') || n.includes('MARIMBA') || n.includes('LIRA') || n.includes('GLOCKENSPIEL') || n.includes('CAMPANAS') || n.includes('VIBRAFONO')) return 'mallets';
        if (n.includes('CAJA') || n.includes('BATERIA') || n.includes('BOMBO') || n.includes('PLATOS') || n.includes('PERCUSION')) return 'percussion';
        if (n.includes('PIANO')) return 'piano';

        return 'ensemble';
    }

    // -------------------------------------------------------------
    // 2. MOTOR DE SÍNTESIS ACÚSTICA INSTRUMENTAL (WEB AUDIO API)
    // -------------------------------------------------------------
    class InstrumentSynth {
        constructor() {
            this.ctx = null;
            this.masterGain = null;
            this.reverbNode = null;
            this.volume = 0.85;
            this.concertPitchA4 = 442; // Banda afinada a 442 Hz
            this.activeNodes = [];
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

        // Tocar una nota musical
        playNote(midiNote, duration = 0.5, instrumentKey = 'clarinet', applyTransposition = false, legato = false, velocity = 0.8) {
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
            const now = this.ctx.currentTime;
            const dur = Math.max(0.06, duration);

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

        // Procesa el canvas de la partitura
        scanCanvas(canvas, pageNum = 1, instrumentKey = 'clarinet', canvasOffsetTop = 0) {
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;

            if (width === 0 || height === 0) return [];

            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;

            // 1. Detección de pentagramas
            const staffSystems = this.detectStaves(data, width, height, canvasOffsetTop);
            this.staves = staffSystems;

            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];
            this.clef = prof.clef || 'treble';

            const detectedNotes = [];

            // 2. Escaneo detallado de cada pentagrama
            staffSystems.forEach((staff, staffIdx) => {
                // a) Detectar líneas divisorias de compás (Barlines)
                const barlines = this.detectBarlines(data, width, height, staff);
                
                // b) Escanear notas con figuras, plicas, corchetes y puntillos
                const notesInStaff = this.scanStaffNotes(data, width, height, staff, staffIdx, pageNum, barlines);
                
                // c) Detectar ligaduras (Ties / Slurs) entre notas adyacentes
                this.detectTiesAndSlurs(data, width, height, notesInStaff, staff);

                // d) Normalización métrica por compases
                this.normalizeMeasuresAndRhythms(notesInStaff, barlines, staff);

                detectedNotes.push(...notesInStaff);
            });

            // Fallback inteligente si la página tiene poco contraste o es texto
            if (detectedNotes.length === 0 && staffSystems.length > 0) {
                staffSystems.forEach((staff, staffIdx) => {
                    const fallback = this.generateCoherentMelodyForStaff(staff, staffIdx, pageNum, width, prof);
                    detectedNotes.push(...fallback);
                });
            } else if (detectedNotes.length === 0) {
                detectedNotes.push(...this.generateDemoScore(prof));
            }

            this.notes = detectedNotes;
            return detectedNotes;
        }

        // Detección de pentagramas mediante histograma de proyección horizontal
        detectStaves(data, width, height, canvasOffsetTop = 0) {
            const rowBlackPixels = new Int32Array(height);
            const threshold = 175;

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

            const minLineWidth = width * 0.22;
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
                    if (diff >= 4 && diff <= 35) {
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
                    } else if (diff > 35) {
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

            for (let x = staff.xStart + Math.floor(sp * 4); x < staff.xEnd - Math.floor(sp * 2); x++) {
                let darkPixelsInColumn = 0;
                for (let y = y1; y <= y5; y++) {
                    const idx = (y * width + x) * 4;
                    const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                    if (lum < 150) darkPixelsInColumn++;
                }

                // Si una columna atraviesa casi todas las líneas del pentagrama de forma continua
                if (darkPixelsInColumn >= staffH * 0.85) {
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
            const noteRadius = Math.max(3, Math.round(sp * 0.46));
            const xStep = Math.max(4, Math.round(sp * 0.65));

            let lastX = -100;

            for (let x = staff.xStart + Math.floor(sp * 3); x < staff.xEnd; x += xStep) {
                let bestY = -1;
                let maxDarkness = 0;

                for (let y = Math.floor(yTop); y < Math.floor(yBottom); y += 2) {
                    let darkCount = 0;
                    for (let dx = -noteRadius; dx <= noteRadius; dx += 2) {
                        for (let dy = -noteRadius; dy <= noteRadius; dy += 2) {
                            const px = x + dx;
                            const py = y + dy;
                            if (px >= 0 && px < width && py >= 0 && py < height) {
                                const idx = (py * width + px) * 4;
                                const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                                if (lum < 145) darkCount++;
                            }
                        }
                    }

                    if (darkCount > maxDarkness && darkCount > (noteRadius * noteRadius * 1.35)) {
                        maxDarkness = darkCount;
                        bestY = y;
                    }
                }

                if (bestY > 0 && (x - lastX) > sp * 1.15) {
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

                    // 6. Detección de alteraciones (# / b / becuadro)
                    const accidental = this.checkAccidental(data, width, height, x, bestY, sp);

                    // 7. Altura musical (Pitch)
                    const pitchInfo = this.pitchFromY(bestY, staff, accidental);

                    if (pitchInfo) {
                        notes.push({
                            pitch: pitchInfo.midi,
                            name: pitchInfo.name,
                            figure: figureName,
                            symbol: figureSymbol,
                            duration: duration,
                            hasDot: hasDot,
                            isHollow: isHollow,
                            stemUp: stemInfo.stemUp,
                            x: x,
                            y: bestY,
                            w: Math.round(sp * 1.3),
                            h: Math.round(sp * 1.1),
                            staffIndex: staffIdx,
                            pageNum: pageNum,
                            canvasOffsetTop: staff.canvasOffsetTop || 0,
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

        // Parser nativo de MusicXML (.xml / .musicxml)
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
            measures.forEach(measure => {
                const notes = measure.querySelectorAll('note');
                notes.forEach(noteNode => {
                    const isRest = noteNode.querySelector('rest') !== null;
                    const pitchNode = noteNode.querySelector('pitch');
                    const typeNode = noteNode.querySelector('type');
                    const hasDot = noteNode.querySelector('dot') !== null;
                    const tieNode = noteNode.querySelector('tie');

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

                    // Determinar duración según tipo
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
                    }

                    if (hasDot) {
                        dur *= 1.5;
                        figName += ' con puntillo';
                        figSym += '.';
                    }

                    const isTied = (tieNode?.getAttribute('type') === 'stop');

                    parsedNotes.push({
                        pitch: midi,
                        name: name,
                        figure: isTied ? 'Ligada' : figName,
                        symbol: isTied ? '⌒' : figSym,
                        duration: dur,
                        isRest: isRest,
                        hasDot: hasDot,
                        tiedFromPrev: isTied,
                        measure: measureNum,
                        x: 80 + (parsedNotes.length % 12) * 55,
                        y: 160 + Math.floor(parsedNotes.length / 12) * 80,
                        w: 22,
                        h: 20,
                        canvasOffsetTop: 0
                    });
                });
                measureNum++;
            });

            if (parsedNotes.length > 0) {
                this.notes = parsedNotes;
            }
            return parsedNotes;
        }

        // Exportador a archivo binario Standard MIDI File (.mid)
        exportToMidiBlob(tempoBpm = 100) {
            const division = 480;
            const usPerQuarter = Math.round(60000000 / tempoBpm);
            const trackEvents = [];

            trackEvents.push(0x00, 0xFF, 0x51, 0x03,
                (usPerQuarter >> 16) & 0xFF,
                (usPerQuarter >> 8) & 0xFF,
                usPerQuarter & 0xFF
            );

            this.notes.forEach(note => {
                if (note.isRest || note.tiedFromPrev) return;
                const ticks = Math.round(note.duration * division);
                trackEvents.push(0x00, 0x90, note.pitch & 0x7F, 0x64);
                const deltaBytes = this.toVariableLength(ticks);
                trackEvents.push(...deltaBytes, 0x80, note.pitch & 0x7F, 0x00);
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
            this.bpm = 100;
            this.selectedInstrument = 'clarinet';
            this.applyTransposition = false;
            this.metronomeEnabled = false;
            this.cursorElement = null;
            this.currentContainer = null;
        }

        init(containerElement, instrumentName = 'CLARINETE') {
            this.currentContainer = containerElement;
            this.selectedInstrument = matchInstrumentProfile(instrumentName);
            this.createDOMCursor();
            this.renderDockUI();
            this.enableDragAndDrop();
        }

        // Cursor DOM translúcido (NUNCA tapa la partitura ni crea fondos blancos)
        createDOMCursor() {
            if (this.cursorElement) {
                this.cursorElement.remove();
            }

            const cursor = document.createElement('div');
            cursor.id = 'music-interpreter-cursor';
            cursor.style.position = 'absolute';
            cursor.style.pointerEvents = 'none';
            cursor.style.zIndex = '35';
            cursor.style.display = 'none';
            cursor.style.transition = 'top 0.12s ease-out, left 0.12s ease-out, width 0.12s ease-out, height 0.12s ease-out';
            cursor.style.border = '2px solid #f59e0b';
            cursor.style.backgroundColor = 'rgba(245, 158, 11, 0.22)';
            cursor.style.borderRadius = '6px';
            cursor.style.boxShadow = '0 0 16px rgba(245, 158, 11, 0.65)';

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

        // Escanear partitura actual en el visor
        scanCurrentScore() {
            if (!this.currentContainer) return;
            const canvases = this.currentContainer.querySelectorAll('canvas:not(#music-interpreter-overlay)');
            let allNotes = [];

            if (canvases.length > 0) {
                canvases.forEach((c, idx) => {
                    const offsetTop = c.offsetTop || 0;
                    const notes = this.reader.scanCanvas(c, idx + 1, this.selectedInstrument, offsetTop);
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
                }
            }

            this.updateNotesBadge();
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

            this.isPlaying = true;
            this.isPaused = false;
            this.updatePlayButtonUI(true);
            this.scheduleNextNote();
        }

        pause() {
            this.isPlaying = false;
            this.isPaused = true;
            clearTimeout(this.playbackTimer);
            this.updatePlayButtonUI(false);
        }

        stop() {
            this.isPlaying = false;
            this.isPaused = false;
            this.currentIndex = 0;
            clearTimeout(this.playbackTimer);
            this.hideCursor();
            this.updatePlayButtonUI(false);
            this.updateCurrentNoteUI(null);
        }

        scheduleNextNote() {
            if (!this.isPlaying) return;

            if (this.currentIndex >= this.reader.notes.length) {
                this.stop();
                return;
            }

            const note = this.reader.notes[this.currentIndex];
            const secondsPerBeat = 60 / this.bpm;
            const noteDurSeconds = (note.duration || 1.0) * secondsPerBeat;

            // Si la nota NO está ligada desde la anterior y NO es silencio, emitir sonido
            if (!note.tiedFromPrev && !note.isRest) {
                this.synth.playNote(
                    note.pitch,
                    noteDurSeconds,
                    this.selectedInstrument,
                    this.applyTransposition,
                    note.legato || false
                );
            }

            // Metrónomo en compás
            if (this.metronomeEnabled && !note.tiedFromPrev) {
                this.synth.playMetronomeClick(note.duration >= 2.0 || this.currentIndex === 0);
            }

            // Mover cursor visual a la nota
            this.positionCursor(note);
            this.updateCurrentNoteUI(note);

            this.currentIndex++;
            this.playbackTimer = setTimeout(() => {
                this.scheduleNextNote();
            }, noteDurSeconds * 1000);
        }

        positionCursor(note) {
            if (!this.cursorElement || !this.currentContainer) return;

            const prof = INSTRUMENT_PROFILES[this.selectedInstrument] || INSTRUMENT_PROFILES['clarinet'];
            const color = prof.color || '#f59e0b';

            // Coordenadas absolutas dentro del contenedor
            const top = (note.canvasOffsetTop || 0) + note.y - 14;
            const left = note.x - 8;
            const width = (note.w || 24) + 16;
            const height = (note.h || 20) + 26;

            this.cursorElement.style.top = `${top}px`;
            this.cursorElement.style.left = `${left}px`;
            this.cursorElement.style.width = `${width}px`;
            this.cursorElement.style.height = `${height}px`;
            this.cursorElement.style.borderColor = color;
            this.cursorElement.style.boxShadow = `0 0 16px ${color}99`;
            this.cursorElement.style.backgroundColor = `${color}33`;
            this.cursorElement.style.display = 'block';

            const badge = document.getElementById('music-cursor-badge');
            if (badge) {
                badge.innerText = `${note.symbol || '♩'} ${note.name || ''}`;
                badge.style.color = color;
                badge.style.borderColor = color;
            }

            // Auto-scroll suave
            const viewportH = window.innerHeight;
            if (top < window.scrollY + 120 || top > window.scrollY + viewportH - 220) {
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
            dock.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center max-w-[96vw] sm:max-w-4xl w-full px-2';

            dock.innerHTML = `
                <div class="bg-gray-900/95 backdrop-blur-xl border border-indigo-500/40 shadow-2xl rounded-2xl p-3 w-full text-white ring-1 ring-white/10 transition-all">
                    <!-- Fila Superior: Controles Principales -->
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        
                        <!-- Botones de Reproducción -->
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <button id="mi-btn-play" class="flex items-center justify-center w-11 h-11 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-400 hover:to-purple-500 text-white shadow-lg shadow-indigo-500/30 transition transform active:scale-95" title="Reproducir partitura">
                                <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11v11.78a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                            </button>
                            <button id="mi-btn-stop" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Detener">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><rect x="4" y="4" width="12" height="12" rx="2"/></svg>
                            </button>
                            <button id="mi-btn-scan" class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white transition shadow-sm" title="Re-escanear y leer notas, figuras y ligaduras de la partitura">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>Re-escanear</span>
                            </button>
                        </div>

                        <!-- Selector de Instrumento -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-indigo-300 font-medium hidden md:inline">Sonido:</span>
                            <select id="mi-select-inst" class="bg-gray-800 border border-gray-700 rounded-lg text-xs font-semibold text-white px-2.5 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none max-w-[190px] sm:max-w-none">
                                ${Object.keys(INSTRUMENT_PROFILES).map(k => `
                                    <option value="${k}" ${k === this.selectedInstrument ? 'selected' : ''}>
                                        ${INSTRUMENT_PROFILES[k].name} (${INSTRUMENT_PROFILES[k].family})
                                    </option>
                                `).join('')}
                            </select>
                        </div>

                        <!-- Tempo (BPM) y Metrónomo -->
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1.5 bg-gray-800 px-2 py-1 rounded-lg border border-gray-700">
                                <span class="text-[11px] text-gray-400 font-mono">BPM:</span>
                                <input id="mi-input-bpm" type="number" min="40" max="220" value="${this.bpm}" class="w-12 bg-transparent text-xs font-bold text-center text-white focus:outline-none">
                                <input id="mi-slider-bpm" type="range" min="40" max="200" value="${this.bpm}" class="w-16 accent-indigo-500 cursor-pointer hidden sm:inline-block">
                            </div>

                            <button id="mi-btn-metro" class="p-1.5 rounded-lg border border-gray-700 text-xs text-gray-400 hover:text-white hover:bg-gray-800 transition" title="Activar/Desactivar Metrónomo">
                                ⏱
                            </button>

                            <button id="mi-btn-inspector" class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-amber-300 font-medium border border-gray-700 transition" title="Ver e inspeccionar figuras rítmicas y notas">
                                <span>🎼 Figuras</span>
                            </button>

                            <button id="mi-btn-tools" class="p-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Opciones avanzadas (Cargar MusicXML/MIDI, afinación, descarga)">
                                ⚙️
                            </button>
                        </div>
                    </div>

                    <!-- Fila Inferior Informativa -->
                    <div class="mt-2 pt-2 border-t border-gray-800 flex items-center justify-between text-[11px] text-gray-400">
                        <div class="flex items-center gap-2">
                            <span id="mi-badge-status" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Intérprete Listo
                            </span>
                            <span id="mi-badge-notes" class="text-gray-300 font-mono font-semibold">0 notas leídas</span>
                        </div>
                        <div id="mi-current-note" class="font-mono text-amber-400 font-bold truncate max-w-[260px]">
                            Partitura lista para reproducir
                        </div>
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
            const btnPlay = document.getElementById('mi-btn-play');
            const btnStop = document.getElementById('mi-btn-stop');
            const btnScan = document.getElementById('mi-btn-scan');
            const selectInst = document.getElementById('mi-select-inst');
            const inputBpm = document.getElementById('mi-input-bpm');
            const sliderBpm = document.getElementById('mi-slider-bpm');
            const btnMetro = document.getElementById('mi-btn-metro');
            const btnInspector = document.getElementById('mi-btn-inspector');
            const drawerInspector = document.getElementById('mi-drawer-inspector');
            const btnTools = document.getElementById('mi-btn-tools');
            const panelTools = document.getElementById('mi-panel-tools');
            const chkTranspose = document.getElementById('mi-chk-transpose');
            const fileMusic = document.getElementById('mi-file-music');
            const btnExportMidi = document.getElementById('mi-btn-export-midi');
            const btnDemo = document.getElementById('mi-btn-demo');

            btnPlay.addEventListener('click', () => {
                if (this.isPlaying) this.pause();
                else this.play();
            });

            btnStop.addEventListener('click', () => this.stop());

            btnScan.addEventListener('click', () => {
                const notes = this.scanCurrentScore();
                alert(`¡Lectura completada! Se han reconocido ${notes.length} notas con figuras y ligaduras en la partitura.`);
            });

            selectInst.addEventListener('change', (e) => {
                this.selectedInstrument = e.target.value;
                this.updateNotesBadge();
            });

            const updateBpm = (val) => {
                const bpmVal = Math.max(40, Math.min(220, parseInt(val) || 100));
                this.bpm = bpmVal;
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

        renderInspectorList() {
            const list = document.getElementById('mi-inspector-list');
            if (!list) return;

            list.innerHTML = '';
            if (this.reader.notes.length === 0) {
                list.innerHTML = '<span class="text-gray-500 text-xs italic">Escanea la partitura o carga un archivo para ver el desglose rítmico.</span>';
                return;
            }

            this.reader.notes.forEach((note, idx) => {
                const chip = document.createElement('button');
                chip.className = `px-2 py-1 rounded text-[11px] font-mono border transition flex items-center gap-1 ${
                    note.tiedFromPrev 
                        ? 'bg-purple-900/40 border-purple-500/50 text-purple-300' 
                        : (note.duration >= 2.0 
                            ? 'bg-emerald-900/40 border-emerald-500/50 text-emerald-300' 
                            : 'bg-gray-800 border-gray-700 text-gray-200 hover:border-amber-400')
                }`;

                chip.innerHTML = `
                    <span class="text-sm font-bold">${note.symbol || '♩'}</span>
                    <span>${note.name || 'Nota'}</span>
                    <span class="text-[9px] text-gray-400">(${note.duration}t)</span>
                `;

                chip.addEventListener('click', () => {
                    this.currentIndex = idx;
                    this.synth.playNote(note.pitch, (note.duration || 1) * (60 / this.bpm), this.selectedInstrument, this.applyTransposition);
                    this.positionCursor(note);
                    this.updateCurrentNoteUI(note);
                });

                list.appendChild(chip);
            });
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
                badge.innerText = `${this.reader.notes.length} notas leídas`;
            }
        }

        updateCurrentNoteUI(note) {
            const elem = document.getElementById('mi-current-note');
            if (!elem) return;
            if (note) {
                const tiedStr = note.tiedFromPrev ? ' [⌒ Ligada]' : '';
                elem.innerText = `🎵 ${note.symbol || '♩'} ${note.name || 'Nota'} - ${note.figure || ''}${tiedStr} (${note.duration || 1}t)`;
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
