const express = require('express');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
// Servimos tu HTML e imágenes desde la carpeta "public"
app.use(express.static(path.join(__dirname, 'public')));

// Endpoint que enviará el mensaje a Telegram
app.post('/api/notify', async (req, res) => {
  try {
    // 1. Consumimos TUS variables de entorno de Render
    const BOT_TOKEN = process.env.TG_TOKEN_P1;
    const CHAT_ID   = process.env.TG_CHAT_P1;

    if (!BOT_TOKEN || !CHAT_ID) {
      console.error("Faltan las variables TG_TOKEN_P1 o TG_CHAT_P1");
      return res.status(500).json({ ok: false, error: 'Faltan variables de entorno' });
    }

    // 2. Capturamos datos del usuario para el mensaje
    const ip = req.headers['x-forwarded-for']?.split(',')[0]?.trim() || req.socket.remoteAddress || 'Desconocida';
    const ua = req.headers['user-agent'] || 'Desconocido';
    const fecha = new Date().toLocaleString('es-CO', { timeZone: 'America/Bogota' });

    // 3. Armamos el mensaje con formato Markdown
    const mensaje = [
      '🚨 *NUEVO INGRESO - P1*',
      '',
      `🕒 *Fecha:* ${fecha}`,
      `🌐 *IP:* \`${ip}\``,
      `💻 *Dispositivo:* \`${ua.substring(0, 100)}...\``,
      '',
      `✅ *P1 notificado correctamente*`
    ].join('\n');

    // 4. Enviamos a Telegram
    const payload = {
      chat_id: CHAT_ID,
      text: mensaje,
      parse_mode: 'Markdown',
      disable_web_page_preview: true
    };

    const tgRes = await fetch(`https://api.telegram.org/bot${BOT_TOKEN}/sendMessage`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const tgData = await tgRes.json();
    
    if (!tgData.ok) {
      console.error('Error de Telegram:', tgData);
      return res.status(500).json({ ok: false, error: tgData.description });
    }

    return res.json({ ok: true });
  } catch (err) {
    console.error('Error en el servidor:', err);
    return res.status(500).json({ ok: false, error: err.message });
  }
});

app.listen(PORT, () => {
  console.log(`Servidor corriendo en el puerto ${PORT}`);
});
