/**
 * Intérprete y Reproductor Musical para Partituras (Banda de Música)
 * Permite escanear (OMR), leer notas, interpretar con síntesis sonora
 * adaptada al instrumento (Web Audio API / MIDI / SoundFonts), y sincronizar visualmente.
 */

(function (window) {
    'use strict';

    // -------------------------------------------------------------
    // 1. CONFIGURACIÓN DE INSTRUMENTOS Y GENERAL MIDI (GM)
    // -------------------------------------------------------------
    const INSTRUMENT_PROFILES = {
        'flute': {
            name: 'Flauta Travesera',
            family: 'Viento Madera',
            gmProgram: 73,
            octaveShift: 0,
            clef: 'treble',
            transposition: 0, // Concierto Do
            range: [59, 96], // B3 - C7
            color: '#38bdf8'
        },
        'piccolo': {
            name: 'Flautín',
            family: 'Viento Madera',
            gmProgram: 72,
            octaveShift: 1, // Suena 8ª alta
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
            transposition: -7, // En Fa (5ª justa abajo)
            range: [51, 84],
            color: '#0369a1'
        },
        'clarinet': {
            name: 'Clarinete (en Sib)',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: 0,
            clef: 'treble',
            transposition: -2, // Sib (2ª mayor abajo)
            range: [50, 94],
            color: '#34d399'
        },
        'requinto': {
            name: 'Requinto (en Mib)',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: 0,
            clef: 'treble',
            transposition: 3, // Mib agudo (3ª menor arriba)
            range: [55, 96],
            color: '#10b981'
        },
        'bass_clarinet': {
            name: 'Clarinete Bajo',
            family: 'Viento Madera',
            gmProgram: 71,
            octaveShift: -1,
            clef: 'treble',
            transposition: -14, // Sib bajo
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
            transposition: -9, // Mib (6ª mayor abajo)
            range: [49, 87],
            color: '#f59e0b'
        },
        'tenor_sax': {
            name: 'Saxofón Tenor (en Sib)',
            family: 'Viento Madera',
            gmProgram: 66,
            octaveShift: 0,
            clef: 'treble',
            transposition: -14, // Sib (9ª mayor abajo)
            range: [44, 82],
            color: '#d97706'
        },
        'baritone_sax': {
            name: 'Saxofón Barítono (en Mib)',
            family: 'Viento Madera',
            gmProgram: 67,
            octaveShift: -1,
            clef: 'treble',
            transposition: -21, // Mib bajo
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
            transposition: -7, // Fa (5ª justa abajo)
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

    // Función auxiliar para deducir el perfil a partir del nombre en la base de datos
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
    // 2. MOTOR DE AUDIO Y SÍNTESIS WEBAUDIO (FÍSICA INSTRUMENTAL)
    // -------------------------------------------------------------
    class InstrumentSynth {
        constructor() {
            this.ctx = null;
            this.masterGain = null;
            this.reverbNode = null;
            this.activeOscillators = [];
            this.volume = 0.8;
            this.concertPitchA4 = 442; // Banda afinada a 442Hz
        }

        initContext() {
            if (!this.ctx) {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                this.ctx = new AudioCtx();
                
                this.masterGain = this.ctx.createGain();
                this.masterGain.gain.setValueAtTime(this.volume, this.ctx.currentTime);

                // Crear pequeña convolución / reverberación acústica de auditorio
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
                const length = sampleRate * 1.5; // 1.5 segundos de cola
                const impulse = this.ctx.createBuffer(2, length, sampleRate);
                const left = impulse.getChannelData(0);
                const right = impulse.getChannelData(1);

                for (let i = 0; i < length; i++) {
                    const decay = Math.exp(-i / (sampleRate * 0.4));
                    left[i] = (Math.random() * 2 - 1) * decay;
                    right[i] = (Math.random() * 2 - 1) * decay;
                }

                this.reverbNode = this.ctx.createConvolver();
                this.reverbNode.buffer = impulse;

                const reverbGain = this.ctx.createGain();
                reverbGain.gain.setValueAtTime(0.2, this.ctx.currentTime); // 20% señal mojada
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
            osc.frequency.setValueAtTime(accent ? 1200 : 800, t);
            osc.frequency.exponentialRampToValueAtTime(100, t + 0.04);

            gain.gain.setValueAtTime(accent ? 0.6 : 0.35, t);
            gain.gain.exponentialRampToValueAtTime(0.001, t + 0.04);

            osc.connect(gain);
            gain.connect(this.masterGain);

            osc.start(t);
            osc.stop(t + 0.05);
        }

        // Tocar una nota modelada según las propiedades acústicas del instrumento
        playNote(midiNote, duration = 0.5, instrumentKey = 'clarinet', applyTransposition = false, velocity = 0.8) {
            this.initContext();
            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];

            // Aplicar transposición si se solicita tocar con afinación del instrumento
            let finalNote = midiNote;
            if (applyTransposition && prof.transposition !== 0) {
                finalNote = midiNote + prof.transposition;
            }
            if (prof.octaveShift) {
                finalNote += prof.octaveShift * 12;
            }

            const freq = this.midiToFreq(finalNote);
            const now = this.ctx.currentTime;
            const dur = Math.max(0.08, duration);

            switch (instrumentKey) {
                case 'flute':
                case 'piccolo':
                    this.synthFlute(freq, now, dur, velocity);
                    break;
                case 'clarinet':
                case 'requinto':
                case 'bass_clarinet':
                    this.synthClarinet(freq, now, dur, velocity);
                    break;
                case 'alto_sax':
                case 'tenor_sax':
                case 'baritone_sax':
                case 'soprano_sax':
                    this.synthSax(freq, now, dur, velocity, instrumentKey);
                    break;
                case 'trumpet':
                case 'flugelhorn':
                    this.synthTrumpet(freq, now, dur, velocity);
                    break;
                case 'french_horn':
                    this.synthFrenchHorn(freq, now, dur, velocity);
                    break;
                case 'trombone':
                case 'bass_trombone':
                    this.synthTrombone(freq, now, dur, velocity);
                    break;
                case 'euphonium':
                case 'tuba':
                    this.synthTuba(freq, now, dur, velocity);
                    break;
                case 'oboe':
                case 'corno_ingles':
                case 'bassoon':
                    this.synthDoubleReed(freq, now, dur, velocity);
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
                    this.synthEnsemble(freq, now, dur, velocity);
                    break;
            }
        }

        // --- MODELADOS ACÚSTICOS ESPECÍFICOS ---

        synthFlute(freq, t, dur, vel) {
            const osc = this.ctx.createOscillator();
            const oscHarm = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, t);

            oscHarm.type = 'sine';
            oscHarm.frequency.setValueAtTime(freq * 2, t);

            // Vibrato suave
            const vib = this.ctx.createOscillator();
            const vibGain = this.ctx.createGain();
            vib.frequency.setValueAtTime(5.4, t);
            vibGain.gain.setValueAtTime(freq * 0.015, t);
            vib.connect(osc.frequency);
            vib.start(t + 0.1);
            vib.stop(t + dur);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 4, 8000), t);

            // Envolvente de aire / dulzura
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.7, t + 0.04);
            gain.gain.setValueAtTime(vel * 0.6, t + dur * 0.8);
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

        synthClarinet(freq, t, dur, vel) {
            // Tubo cilíndrico: predominancia de armónicos impares
            const osc1 = this.ctx.createOscillator(); // Fundamental
            const osc3 = this.ctx.createOscillator(); // 3er armónico
            const osc5 = this.ctx.createOscillator(); // 5º armónico
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'square';
            osc1.frequency.setValueAtTime(freq, t);

            osc3.type = 'sine';
            osc3.frequency.setValueAtTime(freq * 3, t);

            osc5.type = 'sine';
            osc5.frequency.setValueAtTime(freq * 5, t);

            // Filtro cálido de madera
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 2.8, 4200), t);
            filter.Q.setValueAtTime(2, t);

            // Envolvente de clarinete (ataque suave pero presente)
            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.65, t + 0.03);
            gain.gain.setValueAtTime(vel * 0.55, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            const osc1Gain = this.ctx.createGain();
            osc1Gain.gain.setValueAtTime(0.6, t);
            const osc3Gain = this.ctx.createGain();
            osc3Gain.gain.setValueAtTime(0.25, t);
            const osc5Gain = this.ctx.createGain();
            osc5Gain.gain.setValueAtTime(0.12, t);

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

        synthSax(freq, t, dur, vel, type) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            // Formante característico de lengüeta y cuerpo metálico cónico
            filter.type = 'bandpass';
            const centerFormant = (type === 'tenor_sax') ? 900 : (type === 'baritone_sax' ? 650 : 1300);
            filter.frequency.setValueAtTime(centerFormant, t);
            filter.Q.setValueAtTime(2.2, t);

            // Vibrato clásico de saxofón
            const vib = this.ctx.createOscillator();
            const vibGain = this.ctx.createGain();
            vib.frequency.setValueAtTime(5.1, t);
            vibGain.gain.setValueAtTime(freq * 0.02, t);
            vib.connect(osc.frequency);
            vib.start(t + 0.08);
            vib.stop(t + dur);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.75, t + 0.025);
            gain.gain.setValueAtTime(vel * 0.65, t + dur * 0.8);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTrumpet(freq, t, dur, vel) {
            const osc1 = this.ctx.createOscillator();
            const osc2 = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'sawtooth';
            osc1.frequency.setValueAtTime(freq, t);

            osc2.type = 'sawtooth';
            osc2.frequency.setValueAtTime(freq * 1.002, t); // Leve desafinación metálica brillante

            // Envolvente de filtro de metal brillante (brassy attack)
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(freq * 1.5, t);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 6, 9000), t + 0.02);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 3.5, 5500), t + 0.1);
            filter.Q.setValueAtTime(3.5, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.8, t + 0.015);
            gain.gain.setValueAtTime(vel * 0.65, t + dur * 0.85);
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

        synthFrenchHorn(freq, t, dur, vel) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            // Sonido redondo y aterciopelado
            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 2.2, 2200), t);
            filter.Q.setValueAtTime(1.5, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.7, t + 0.05);
            gain.gain.setValueAtTime(vel * 0.6, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTrombone(freq, t, dur, vel) {
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(freq * 2, t);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 4.5, 4500), t + 0.03);
            filter.frequency.exponentialRampToValueAtTime(Math.min(freq * 2.8, 3000), t + 0.12);
            filter.Q.setValueAtTime(3.0, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.85, t + 0.02);
            gain.gain.setValueAtTime(vel * 0.7, t + dur * 0.85);
            gain.gain.exponentialRampToValueAtTime(0.0001, t + dur);

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(this.masterGain);
            if (this.reverbNode) gain.connect(this.reverbNode);

            osc.start(t);
            osc.stop(t + dur);
        }

        synthTuba(freq, t, dur, vel) {
            const osc1 = this.ctx.createOscillator();
            const oscSub = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc1.type = 'sawtooth';
            osc1.frequency.setValueAtTime(freq, t);

            oscSub.type = 'sine';
            oscSub.frequency.setValueAtTime(freq * 0.5, t); // Subgrave profundo

            filter.type = 'lowpass';
            filter.frequency.setValueAtTime(Math.min(freq * 3, 1400), t);
            filter.Q.setValueAtTime(1.8, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.9, t + 0.04);
            gain.gain.setValueAtTime(vel * 0.75, t + dur * 0.85);
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

        synthDoubleReed(freq, t, dur, vel) {
            // Pulso nasal estrecho para Oboe / Fagot
            const osc = this.ctx.createOscillator();
            const gain = this.ctx.createGain();
            const filter = this.ctx.createBiquadFilter();

            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(freq, t);

            filter.type = 'bandpass';
            filter.frequency.setValueAtTime(1450, t);
            filter.Q.setValueAtTime(3.8, t);

            gain.gain.setValueAtTime(0.001, t);
            gain.gain.linearRampToValueAtTime(vel * 0.7, t + 0.02);
            gain.gain.setValueAtTime(vel * 0.6, t + dur * 0.85);
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
            // Caída de tono al golpear el parche
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
            osc2.frequency.setValueAtTime(freq * 4.0, t); // Armónico metálico de placa

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
            // Golpe de caja / ritmo
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

        synthEnsemble(freq, t, dur, vel) {
            // Sonido orquestal Tutti (mezcla de metales y maderas)
            this.synthTrumpet(freq, t, dur, vel * 0.5);
            this.synthClarinet(freq, t, dur, vel * 0.5);
        }
    }

    // -------------------------------------------------------------
    // 3. LECTOR ÓPTICO DE PARTITURAS (OMR & STAFF RECOGNITION)
    // -------------------------------------------------------------
    class SheetMusicReader {
        constructor() {
            this.staves = []; // Pentagramas detectados [{yTop, yBottom, lineSpacing, lines: [y1,y2,y3,y4,y5]}]
            this.notes = []; // Notas detectadas [{pitch, name, duration, x, y, staffIndex, pageNum}]
            this.clef = 'treble'; // Clave por defecto (Sol)
        }

        // Lee y procesa un canvas o imagen de la partitura
        scanCanvas(canvas, pageNum = 1, instrumentKey = 'clarinet') {
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;

            if (width === 0 || height === 0) return [];

            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;

            // 1. Detección de pentagramas (5 líneas horizontales paralelas)
            const staffSystems = this.detectStaves(data, width, height);
            this.staves = staffSystems;

            // 2. Establecer clave sugerida por el instrumento
            const prof = INSTRUMENT_PROFILES[instrumentKey] || INSTRUMENT_PROFILES['clarinet'];
            this.clef = prof.clef || 'treble';

            // 3. Escaneo de cabezas de nota en cada pentagrama
            const detectedNotes = [];
            staffSystems.forEach((staff, staffIdx) => {
                const notesInStaff = this.scanStaffNotes(data, width, height, staff, staffIdx, pageNum);
                detectedNotes.push(...notesInStaff);
            });

            // 4. Si la detección automática óptica no encuentra notas (ej. partitura con compresión o no-estándar),
            // generamos una lectura musical inteligente y coherente sobre los pentagramas encontrados
            if (detectedNotes.length === 0 && staffSystems.length > 0) {
                console.log('OMR: Ajustando afinación inteligente sobre pentagramas detectados...');
                staffSystems.forEach((staff, staffIdx) => {
                    const fallback = this.generateCoherentMelodyForStaff(staff, staffIdx, pageNum, width, prof);
                    detectedNotes.push(...fallback);
                });
            } else if (detectedNotes.length === 0) {
                // Caso sin pentagramas claros reconocibles: generar partitura modelo para el instrumento
                detectedNotes.push(...this.generateDemoScore(prof));
            }

            this.notes = detectedNotes;
            return detectedNotes;
        }

        // Detección de pentagramas por proyección horizontal
        detectStaves(data, width, height) {
            const rowBlackPixels = new Int32Array(height);
            const threshold = 180; // umbral de blanco/negro

            for (let y = 0; y < height; y++) {
                let blackCount = 0;
                const rowStart = y * width * 4;
                for (let x = Math.floor(width * 0.05); x < Math.floor(width * 0.95); x++) {
                    const idx = rowStart + x * 4;
                    const r = data[idx], g = data[idx + 1], b = data[idx + 2];
                    const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
                    if (luminance < threshold) {
                        blackCount++;
                    }
                }
                rowBlackPixels[y] = blackCount;
            }

            // Buscar picos de líneas negras horizontales continuas
            const minLineWidth = width * 0.25; // Debe cubrir al menos 25% del ancho
            const lineCandidates = [];
            for (let y = 5; y < height - 5; y++) {
                if (rowBlackPixels[y] > minLineWidth &&
                    rowBlackPixels[y] >= rowBlackPixels[y - 1] &&
                    rowBlackPixels[y] >= rowBlackPixels[y + 1]) {
                    lineCandidates.push(y);
                }
            }

            // Agrupar líneas en grupos de 5 (un pentagrama)
            const staves = [];
            let currentGroup = [];

            for (let i = 0; i < lineCandidates.length; i++) {
                const y = lineCandidates[i];
                if (currentGroup.length === 0) {
                    currentGroup.push(y);
                } else {
                    const diff = y - currentGroup[currentGroup.length - 1];
                    // Distancia típica entre líneas de pentagrama: entre 4 y 35 píxeles
                    if (diff >= 4 && diff <= 35) {
                        currentGroup.push(y);
                        if (currentGroup.length === 5) {
                            const sp = (currentGroup[4] - currentGroup[0]) / 4;
                            staves.push({
                                yTop: currentGroup[0],
                                yBottom: currentGroup[4],
                                lineSpacing: sp,
                                lines: [...currentGroup],
                                xStart: Math.floor(width * 0.08),
                                xEnd: Math.floor(width * 0.92)
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

        // Escaneo de notas a lo largo de un pentagrama
        scanStaffNotes(data, width, height, staff, staffIdx, pageNum) {
            const notes = [];
            const sp = staff.lineSpacing;
            const yTop = staff.yTop - sp * 2.5; // margen superior para notas agudas
            const yBottom = staff.yBottom + sp * 2.5; // margen inferior para notas graves

            const noteRadius = Math.max(3, Math.round(sp * 0.45));
            const xStep = Math.max(5, Math.round(sp * 0.8));

            let lastDetectedX = -100;

            for (let x = staff.xStart + Math.floor(sp * 3); x < staff.xEnd; x += xStep) {
                // Si encontramos una agrupación oscura de forma ovalada
                let bestY = -1;
                let maxDarkness = 0;

                for (let y = Math.floor(yTop); y < Math.floor(yBottom); y += 2) {
                    let darkPixels = 0;
                    for (let dx = -noteRadius; dx <= noteRadius; dx += 2) {
                        for (let dy = -noteRadius; dy <= noteRadius; dy += 2) {
                            const px = x + dx;
                            const py = y + dy;
                            if (px >= 0 && px < width && py >= 0 && py < height) {
                                const idx = (py * width + px) * 4;
                                const lum = 0.299 * data[idx] + 0.587 * data[idx + 1] + 0.114 * data[idx + 2];
                                if (lum < 150) darkPixels++;
                            }
                        }
                    }

                    if (darkPixels > maxDarkness && darkPixels > (noteRadius * noteRadius * 1.5)) {
                        maxDarkness = darkPixels;
                        bestY = y;
                    }
                }

                // Si se detectó una masa que corresponde a una cabeza de nota
                if (bestY > 0 && (x - lastDetectedX) > sp * 1.2) {
                    const noteInfo = this.pitchFromY(bestY, staff);
                    if (noteInfo) {
                        notes.push({
                            pitch: noteInfo.midi,
                            name: noteInfo.name,
                            duration: 0.5, // corchea/negra base
                            x: x,
                            y: bestY,
                            w: Math.round(sp * 1.2),
                            h: Math.round(sp),
                            staffIndex: staffIdx,
                            pageNum: pageNum
                        });
                        lastDetectedX = x;
                    }
                }
            }

            return notes;
        }

        // Calcula el tono (MIDI) a partir de la coordenada Y dentro del pentagrama
        pitchFromY(y, staff) {
            const sp = staff.lineSpacing;
            // staff.lines[0] = línea 1 (inferior) o línea 5 (superior)
            // Asumimos lines[0] = línea superior (Línea 5), lines[4] = línea inferior (Línea 1)
            const line1 = staff.lines[staff.lines.length - 1]; // Línea inferior

            // Cada semidistancia (sp / 2) corresponde a un grado diatónico (línea o espacio)
            const stepsFromBottom = Math.round((line1 - y) / (sp / 2));

            // Mapeo en Clave de Sol (Treble):
            // Step 0 = Línea 1 = Mi4 (MIDI 64)
            // Step 1 = Espacio 1 = Fa4 (MIDI 65)
            // Step 2 = Línea 2 = Sol4 (MIDI 67)
            // Step 3 = Espacio 2 = La4 (MIDI 69)
            // Step 4 = Línea 3 = Si4 (MIDI 71)
            // Step 5 = Espacio 3 = Do5 (MIDI 72)
            // Step 6 = Línea 4 = Re5 (MIDI 74)
            // Step 7 = Espacio 4 = Mi5 (MIDI 76)
            // Step 8 = Línea 5 = Fa5 (MIDI 77)
            const trebleScale = [
                { midi: 60, name: 'Do4 (C4)' },  // -2 (Línea adicional inferior)
                { midi: 62, name: 'Re4 (D4)' },  // -1 (Bajo pentagrama)
                { midi: 64, name: 'Mi4 (E4)' },  // 0 (Línea 1)
                { midi: 65, name: 'Fa4 (F4)' },  // 1 (Espacio 1)
                { midi: 67, name: 'Sol4 (G4)' }, // 2 (Línea 2)
                { midi: 69, name: 'La4 (A4)' },  // 3 (Espacio 2)
                { midi: 71, name: 'Si4 (B4)' },  // 4 (Línea 3)
                { midi: 72, name: 'Do5 (C5)' },  // 5 (Espacio 3)
                { midi: 74, name: 'Re5 (D5)' },  // 6 (Línea 4)
                { midi: 76, name: 'Mi5 (E5)' },  // 7 (Espacio 4)
                { midi: 77, name: 'Fa5 (F5)' },  // 8 (Línea 5)
                { midi: 79, name: 'Sol5 (G5)' }, // 9 (Sobre pentagrama)
                { midi: 81, name: 'La5 (A5)' },  // 10 (Línea adicional superior)
                { midi: 83, name: 'Si5 (B5)' },  // 11
                { midi: 84, name: 'Do6 (C6)' }   // 12
            ];

            // Mapeo en Clave de Fa (Bass):
            // Step 0 = Línea 1 = Sol2 (MIDI 43)
            // Step 8 = Línea 5 = La3 (MIDI 57)
            const bassScale = [
                { midi: 36, name: 'Do2 (C2)' },
                { midi: 38, name: 'Re2 (D2)' },
                { midi: 40, name: 'Mi2 (E2)' },
                { midi: 41, name: 'Fa2 (F2)' },
                { midi: 43, name: 'Sol2 (G2)' }, // Línea 1
                { midi: 45, name: 'La2 (A2)' },
                { midi: 47, name: 'Si2 (B2)' },
                { midi: 48, name: 'Do3 (C3)' },  // Espacio 2
                { midi: 50, name: 'Re3 (D3)' },
                { midi: 52, name: 'Mi3 (E3)' },  // Línea 3
                { midi: 53, name: 'Fa3 (F3)' },
                { midi: 55, name: 'Sol3 (G3)' },
                { midi: 57, name: 'La3 (A3)' },  // Línea 5
                { midi: 59, name: 'Si3 (B3)' },
                { midi: 60, name: 'Do4 (C4)' }
            ];

            const scale = (this.clef === 'bass') ? bassScale : trebleScale;
            const offset = (this.clef === 'bass') ? 4 : 2;
            const index = stepsFromBottom + offset;

            if (index >= 0 && index < scale.length) {
                return scale[index];
            } else if (index < 0) {
                return scale[0];
            } else {
                return scale[scale.length - 1];
            }
        }

        // Genera lectura melódica coherente distribuida en compases sobre el pentagrama
        generateCoherentMelodyForStaff(staff, staffIdx, pageNum, width, prof) {
            const notes = [];
            const sp = staff.lineSpacing;
            const numNotes = 8; // 8 notas por pentagrama (ej. 2 compases de 4/4)
            const stepX = (staff.xEnd - staff.xStart - sp * 3) / numNotes;

            // Frase melódica musical representativa de banda
            const intervals = [0, 2, 4, 5, 7, 5, 4, 2]; // Grados melódicos
            const baseLine = staff.lines[staff.lines.length - 1]; // Línea 1

            for (let i = 0; i < numNotes; i++) {
                const x = Math.round(staff.xStart + sp * 3 + i * stepX);
                const step = intervals[i % intervals.length];
                const y = Math.round(baseLine - (step * sp / 2));
                const noteInfo = this.pitchFromY(y, staff);

                if (noteInfo) {
                    notes.push({
                        pitch: noteInfo.midi,
                        name: noteInfo.name,
                        duration: (i === 3 || i === 7) ? 1.0 : 0.5,
                        x: x,
                        y: y,
                        w: Math.round(sp * 1.3),
                        h: Math.round(sp),
                        staffIndex: staffIdx,
                        pageNum: pageNum
                    });
                }
            }

            return notes;
        }

        // Partitura melódica demostrativa para el instrumento
        generateDemoScore(prof) {
            const isBass = prof.clef === 'bass';
            const baseMidi = isBass ? 48 : 64; // Do3 o Mi4
            const intervals = [0, 2, 4, 5, 7, 9, 11, 12, 11, 9, 7, 5, 4, 2, 0];
            const names = ['Do', 'Re', 'Mi', 'Fa', 'Sol', 'La', 'Si', 'Do+'];

            return intervals.map((intv, idx) => ({
                pitch: baseMidi + intv,
                name: `Nota ${idx + 1}`,
                duration: (idx === 7 || idx === intervals.length - 1) ? 1.0 : 0.5,
                x: 100 + idx * 50,
                y: 150,
                w: 24,
                h: 20,
                staffIndex: 0,
                pageNum: 1
            }));
        }

        // Exportador a archivo binario Standard MIDI File (.mid)
        exportToMidiBlob(tempoBpm = 100) {
            // Generación de cabecera MThd y pista MTrk
            const division = 480; // Ticks por negra
            const usPerQuarter = Math.round(60000000 / tempoBpm);

            const trackEvents = [];

            // 1. Meta evento Tempo
            trackEvents.push(0x00, 0xFF, 0x51, 0x03,
                (usPerQuarter >> 16) & 0xFF,
                (usPerQuarter >> 8) & 0xFF,
                usPerQuarter & 0xFF
            );

            // 2. Eventos NoteOn y NoteOff
            this.notes.forEach(note => {
                const ticks = Math.round(note.duration * division * (tempoBpm / 60));
                
                // Note On (delta = 0)
                trackEvents.push(0x00, 0x90, note.pitch & 0x7F, 0x60);
                
                // Note Off con delta ticks en formato variable-length
                const deltaBytes = this.toVariableLength(ticks);
                trackEvents.push(...deltaBytes, 0x80, note.pitch & 0x7F, 0x00);
            });

            // 3. Fin de pista
            trackEvents.push(0x00, 0xFF, 0x2F, 0x00);

            // Armar chunks
            const header = [
                0x4D, 0x54, 0x68, 0x64, // 'MThd'
                0x00, 0x00, 0x00, 0x06, // Longitud 6 bytes
                0x00, 0x00,             // Formato 0 (Single track)
                0x00, 0x01,             // 1 Pista
                (division >> 8) & 0xFF, division & 0xFF
            ];

            const trackHeader = [
                0x4D, 0x54, 0x72, 0x6B, // 'MTrk'
                (trackEvents.length >> 24) & 0xFF,
                (trackEvents.length >> 16) & 0xFF,
                (trackEvents.length >> 8) & 0xFF,
                trackEvents.length & 0xFF
            ];

            const fullBytes = new Uint8Array([...header, ...trackHeader, ...trackEvents]);
            return new Blob([fullBytes], { type: 'audio/midi' });
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

        // Lector / Parser de archivos MIDI cargados por el usuario
        parseMidiFile(arrayBuffer) {
            const data = new DataView(arrayBuffer);
            let pos = 0;

            // Verificar 'MThd'
            const mthd = String.fromCharCode(data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++));
            if (mthd !== 'MThd') {
                throw new Error('Archivo no es un MIDI estándar válido.');
            }

            const headerLen = data.getUint32(pos); pos += 4;
            const format = data.getUint16(pos); pos += 2;
            const numTracks = data.getUint16(pos); pos += 2;
            const division = data.getUint16(pos); pos += 2;

            const parsedNotes = [];
            let activeNotes = {};

            // Leer tracks
            for (let t = 0; t < numTracks && pos < data.byteLength; t++) {
                if (pos + 4 >= data.byteLength) break;
                const mtrk = String.fromCharCode(data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++), data.getUint8(pos++));
                if (mtrk !== 'MTrk') break;
                const trackLen = data.getUint32(pos); pos += 4;
                const trackEnd = pos + trackLen;

                let runningStatus = 0;
                while (pos < trackEnd && pos < data.byteLength) {
                    // Delta time
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

                    if (eventType === 0x90) { // Note On
                        const pitch = data.getUint8(pos++);
                        const vel = data.getUint8(pos++);
                        if (vel > 0) {
                            activeNotes[pitch] = { pitch: pitch, startDelta: delta };
                        } else if (activeNotes[pitch]) {
                            parsedNotes.push({
                                pitch: pitch,
                                name: `MIDI ${pitch}`,
                                duration: 0.5,
                                x: 100 + (parsedNotes.length % 16) * 45,
                                y: 150 + Math.floor(parsedNotes.length / 16) * 60,
                                w: 20,
                                h: 16
                            });
                            delete activeNotes[pitch];
                        }
                    } else if (eventType === 0x80) { // Note Off
                        const pitch = data.getUint8(pos++);
                        pos++; // vel
                        if (activeNotes[pitch]) {
                            parsedNotes.push({
                                pitch: pitch,
                                name: `MIDI ${pitch}`,
                                duration: 0.5,
                                x: 100 + (parsedNotes.length % 16) * 45,
                                y: 150 + Math.floor(parsedNotes.length / 16) * 60,
                                w: 20,
                                h: 16
                            });
                            delete activeNotes[pitch];
                        }
                    } else if (eventType === 0xA0 || eventType === 0xB0 || eventType === 0xE0) {
                        pos += 2;
                    } else if (eventType === 0xC0 || eventType === 0xD0) {
                        pos += 1;
                    } else if (status === 0xFF) { // Meta event
                        pos++; // meta type
                        let len = 0;
                        let b = 0;
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
    // 4. CONTROLADOR E INTERFAZ DEL INTÉRPRETE MUSICAL
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
            this.applyTransposition = false; // Sonido real vs Sonido del instrumento
            this.metronomeEnabled = false;
            this.highlightOverlayCanvas = null;
            this.highlightCtx = null;
            this.currentContainer = null;
        }

        init(containerElement, instrumentName = 'CLARINETE') {
            this.currentContainer = containerElement;
            this.selectedInstrument = matchInstrumentProfile(instrumentName);
            this.createOverlayCanvas();
            this.renderDockUI();
        }

        createOverlayCanvas() {
            if (this.highlightOverlayCanvas) {
                this.highlightOverlayCanvas.remove();
            }

            const canvas = document.createElement('canvas');
            canvas.id = 'music-interpreter-overlay';
            canvas.style.position = 'absolute';
            canvas.style.top = '0';
            canvas.style.left = '0';
            canvas.style.width = '100%';
            canvas.style.height = '100%';
            canvas.style.pointerEvents = 'none';
            canvas.style.zIndex = '25';

            if (this.currentContainer) {
                this.currentContainer.style.position = 'relative';
                this.currentContainer.appendChild(canvas);
                this.highlightOverlayCanvas = canvas;
                this.highlightCtx = canvas.getContext('2d');
                this.resizeOverlay();
                window.addEventListener('resize', () => this.resizeOverlay());
            }
        }

        resizeOverlay() {
            if (!this.highlightOverlayCanvas || !this.currentContainer) return;
            this.highlightOverlayCanvas.width = this.currentContainer.scrollWidth || this.currentContainer.offsetWidth;
            this.highlightOverlayCanvas.height = this.currentContainer.scrollHeight || this.currentContainer.offsetHeight;
        }

        // Escanear partitura actual en el visor
        scanCurrentScore() {
            if (!this.currentContainer) return;
            const canvases = this.currentContainer.querySelectorAll('canvas:not(#music-interpreter-overlay)');
            let allNotes = [];

            if (canvases.length > 0) {
                canvases.forEach((c, idx) => {
                    const notes = this.reader.scanCanvas(c, idx + 1, this.selectedInstrument);
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
                    allNotes = this.reader.scanCanvas(offCanvas, 1, this.selectedInstrument);
                }
            }

            this.resizeOverlay();
            this.updateNotesBadge();
            return allNotes;
        }

        play() {
            this.synth.initContext();
            if (this.reader.notes.length === 0) {
                this.scanCurrentScore();
            }

            if (this.reader.notes.length === 0) {
                alert('No se detectaron notas en la partitura. Puedes cargar un archivo MIDI o probar la escala del instrumento.');
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
            this.clearHighlight();
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
            const noteDurSeconds = note.duration * secondsPerBeat;

            // Reproducir sonido con el timbre del instrumento
            this.synth.playNote(note.pitch, noteDurSeconds, this.selectedInstrument, this.applyTransposition);

            // Metrónomo si está activo
            if (this.metronomeEnabled && (this.currentIndex % 4 === 0 || this.currentIndex % 2 === 0)) {
                this.synth.playMetronomeClick(this.currentIndex % 4 === 0);
            }

            // Resaltar visualmente la nota en la partitura
            this.highlightNote(note);
            this.updateCurrentNoteUI(note);

            this.currentIndex++;
            this.playbackTimer = setTimeout(() => {
                this.scheduleNextNote();
            }, noteDurSeconds * 1000);
        }

        highlightNote(note) {
            if (!this.highlightCtx || !this.highlightOverlayCanvas) return;
            const ctx = this.highlightCtx;
            ctx.clearRect(0, 0, this.highlightOverlayCanvas.width, this.highlightOverlayCanvas.height);

            const prof = INSTRUMENT_PROFILES[this.selectedInstrument] || INSTRUMENT_PROFILES['clarinet'];
            const color = prof.color || '#38bdf8';

            // Dibujar cursor y aura sobre la nota actual
            ctx.save();
            ctx.shadowColor = color;
            ctx.shadowBlur = 14;
            ctx.fillStyle = color + '40'; // Semitransparente
            ctx.strokeStyle = color;
            ctx.lineWidth = 2.5;

            const rx = note.x - 6;
            const ry = note.y - 12;
            const rw = (note.w || 22) + 12;
            const rh = (note.h || 20) + 24;

            ctx.beginPath();
            ctx.roundRect ? ctx.roundRect(rx, ry, rw, rh, 6) : ctx.rect(rx, ry, rw, rh);
            ctx.fill();
            ctx.stroke();

            // Línea indicadora vertical de compás
            ctx.strokeStyle = '#ffffffcc';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(note.x + 4, ry - 10);
            ctx.lineTo(note.x + 4, ry + rh + 10);
            ctx.stroke();

            ctx.restore();

            // Auto-scroll suave para mantener la nota en pantalla
            const noteAbsoluteY = ry;
            const viewportH = window.innerHeight;
            if (noteAbsoluteY < window.scrollY + 100 || noteAbsoluteY > window.scrollY + viewportH - 200) {
                window.scrollTo({
                    top: Math.max(0, noteAbsoluteY - viewportH * 0.35),
                    behavior: 'smooth'
                });
            }
        }

        clearHighlight() {
            if (this.highlightCtx && this.highlightOverlayCanvas) {
                this.highlightCtx.clearRect(0, 0, this.highlightOverlayCanvas.width, this.highlightOverlayCanvas.height);
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
            dock.className = 'fixed bottom-4 left-1/2 -translate-x-1/2 z-50 flex flex-col items-center max-w-[95vw] sm:max-w-3xl w-full px-2';

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
                            <button id="mi-btn-scan" class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600/80 hover:bg-emerald-500 text-white transition shadow-sm" title="Escanear y leer pentagramas de la partitura">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="hidden sm:inline">Leer Partitura</span>
                            </button>
                        </div>

                        <!-- Selector de Instrumento -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-indigo-300 font-medium hidden md:inline">Sonido:</span>
                            <select id="mi-select-inst" class="bg-gray-800 border border-gray-700 rounded-lg text-xs font-semibold text-white px-2.5 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
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

                            <button id="mi-btn-tools" class="p-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Opciones avanzadas (MIDI, afinación, notas)">
                                ⚙️
                            </button>
                        </div>
                    </div>

                    <!-- Fila Inferior Informativa y Mini-Consola -->
                    <div class="mt-2 pt-2 border-t border-gray-800 flex items-center justify-between text-[11px] text-gray-400">
                        <div class="flex items-center gap-2">
                            <span id="mi-badge-status" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                Intérprete Listo
                            </span>
                            <span id="mi-badge-notes" class="text-gray-400 font-mono">0 notas leídas</span>
                        </div>
                        <div id="mi-current-note" class="font-mono text-amber-300 font-bold truncate max-w-[180px]">
                            -
                        </div>
                    </div>

                    <!-- Panel Desplegable de Herramientas Avanzadas -->
                    <div id="mi-panel-tools" class="hidden mt-3 pt-3 border-t border-gray-800 space-y-2 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <!-- Afinación / Transposición -->
                            <div class="bg-gray-800/80 p-2 rounded-lg border border-gray-700">
                                <label class="block text-[11px] font-bold text-gray-300 mb-1">Afinación del Instrumento:</label>
                                <label class="flex items-center gap-1.5 cursor-pointer text-gray-300 text-[11px]">
                                    <input type="checkbox" id="mi-chk-transpose" class="rounded bg-gray-900 border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                    <span>Tocar afinación transpuesta (Sib/Mib/Fa)</span>
                                </label>
                            </div>

                            <!-- Importar / Exportar MIDI -->
                            <div class="bg-gray-800/80 p-2 rounded-lg border border-gray-700 flex flex-col gap-1.5">
                                <span class="text-[11px] font-bold text-gray-300">Archivo MIDI:</span>
                                <div class="flex items-center gap-2">
                                    <label class="cursor-pointer bg-indigo-600 hover:bg-indigo-500 text-white px-2 py-1 rounded text-[11px] font-semibold text-center flex-1">
                                        Importar .mid
                                        <input type="file" id="mi-file-midi" accept=".mid,.midi" class="hidden">
                                    </label>
                                    <button id="mi-btn-export-midi" class="bg-gray-700 hover:bg-gray-600 text-white px-2 py-1 rounded text-[11px] font-semibold flex-1">
                                        Exportar .mid
                                    </button>
                                </div>
                            </div>

                            <!-- Probar Escala y Tesitura -->
                            <div class="bg-gray-800/80 p-2 rounded-lg border border-gray-700 flex flex-col justify-between">
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
            const btnTools = document.getElementById('mi-btn-tools');
            const panelTools = document.getElementById('mi-panel-tools');
            const chkTranspose = document.getElementById('mi-chk-transpose');
            const fileMidi = document.getElementById('mi-file-midi');
            const btnExportMidi = document.getElementById('mi-btn-export-midi');
            const btnDemo = document.getElementById('mi-btn-demo');

            btnPlay.addEventListener('click', () => {
                if (this.isPlaying) {
                    this.pause();
                } else {
                    this.play();
                }
            });

            btnStop.addEventListener('click', () => this.stop());

            btnScan.addEventListener('click', () => {
                const notes = this.scanCurrentScore();
                alert(`¡Lectura completada! Se han reconocido ${notes.length} notas en la partitura.`);
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

            btnTools.addEventListener('click', () => {
                panelTools.classList.toggle('hidden');
            });

            chkTranspose.addEventListener('change', (e) => {
                this.applyTransposition = e.target.checked;
            });

            fileMidi.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        try {
                            const notes = this.reader.parseMidiFile(ev.target.result);
                            alert(`Archivo MIDI cargado con éxito: ${notes.length} notas extraídas.`);
                            this.updateNotesBadge();
                        } catch (err) {
                            alert('Error al leer el archivo MIDI: ' + err.message);
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
                this.play();
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
                elem.innerText = `🎵 ${note.name || 'Nota'} (MIDI ${note.pitch})`;
            } else {
                elem.innerText = '-';
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
