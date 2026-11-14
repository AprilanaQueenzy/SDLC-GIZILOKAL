<?php
require_once 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Konsultasi Kesehatan - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #f0f7f4 0%, #e1f0eb 50%, #f4f7f6 100%); 
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; 
            height: 100vh; 
            display: flex; 
            flex-direction: column; 
            overflow: hidden; 
            margin: 0;
        }
        .navbar-custom { background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .main-container { flex: 1; max-width: 950px; width: 100%; margin: 20px auto; display: flex; flex-direction: column; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.08); overflow: hidden; border: 1px solid #e2e8f0; }
        .chat-container { flex: 1; overflow-y: auto; padding: 25px; display: flex; flex-direction: column; gap: 16px; background-color: #fafbfc; }
        .chat-bubble { max-width: 75%; padding: 12px 18px; border-radius: 14px; font-size: 0.95rem; line-height: 1.5; word-wrap: break-word; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
        .chat-bubble.bot { background-color: #ffffff; color: #212529; align-self: flex-start; border: 1px solid #e2e8f0; border-bottom-left-radius: 2px; }
        .chat-bubble.user { background-color: #2e7d32; color: #ffffff; align-self: flex-end; border-bottom-right-radius: 2px; }
        .input-panel { padding: 18px 25px; background-color: #ffffff; border-top: 1px solid #e2e8f0; }
        .input-group .form-control { border-radius: 10px 0 0 10px; border-color: #cbd5e1; padding: 12px 18px; }
        .input-group .form-control:focus { box-shadow: none; border-color: #2e7d32; }
        .input-group .btn { border-radius: 0 10px 10px 0; padding-left: 25px; padding-right: 25px; font-weight: 600; background-color: #2e7d32; border: none; }
        .input-group .btn:hover { background-color: #1b5e20; }
        .typing span { height: 7px; width: 7px; float: left; margin: 0 2px; background-color: #6c757d; border-radius: 50%; display: inline-block; animation: wave 1.3s infinite ease-in-out; }
        .typing span:nth-child(2) { animation-delay: -1.1s; }
        .typing span:nth-child(3) { animation-delay: -0.9s; }
        @keyframes wave { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-4px); } }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand navbar-custom">
        <div class="container-fluid px-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">AI Konsultasi Kesehatan</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">GiziLokal &bull; Standar Kemenkes & WHO</small>
                </div>
            </div>
            <a href="index.php" class="btn btn-outline-success btn-sm fw-semibold">
                <i class="fa-solid fa-house me-1"></i> Kembali ke halaman utama
            </a>
        </div>
    </nav>

    <!-- Main Chat Card Container -->
    <div class="container d-flex flex-column" style="flex: 1; overflow: hidden; padding-bottom: 20px;">
        <div class="main-container">
            
            <!-- Chat Box -->
            <div id="chatBox" class="chat-container">
                <div class="chat-bubble bot">
                    Halo! Saya asisten kesehatan GiziLokal Anda. Ada keluhan ringan atau pertanyaan seputar nutrisi pemulihan yang ingin didiskusikan hari ini?
                </div>
            </div>

            <!-- Input Area -->
            <div class="input-panel">
                <form id="chatForm" class="input-group">
                    <input type="text" id="userInput" class="form-control" placeholder="Ketik pesan atau keluhan kesehatan..." autocomplete="off" required>
                    <button class="btn btn-success" type="submit" id="sendBtn">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const apiKey = "AQ.Ab8RN6LzWBolEBh-H3Hg9nhxPTzFOdM8NtZHh62q4lIljNSKkw"; 
        
        const chatForm = document.getElementById('chatForm');
        const userInput = document.getElementById('userInput');
        const chatBox = document.getElementById('chatBox');
        const sendBtn = document.getElementById('sendBtn');

        let chatHistory = [
            {
                role: "user",
                parts: [{ text: "Bertindaklah sebagai asisten AI Konsultasi Kesehatan untuk platform GiziLokal yang berstandar Kemenkes & WHO. Jawablah pertanyaan seputar keluhan kesehatan ringan, rekomendasi makanan pemulihan (pangan lokal), dan takaran istirahat ideal secara ramah, informatif, dan terstruktur dalam Bahasa Indonesia." }]
            },
            {
                role: "model",
                parts: [{ text: "Baik, saya siap membantu memberikan konsultasi kesehatan ringan, saran makanan pemulihan berbasis pangan lokal, serta panduan istirahat ideal sesuai standar Kemenkes dan WHO." }]
            }
        ];

        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const messageText = userInput.value.trim();
            if (!messageText) return;

            appendMessage(messageText, 'user');
            userInput.value = '';
            userInput.disabled = true;
            sendBtn.disabled = true;

            const typingId = showTypingIndicator();

            try {
                chatHistory.push({ role: "user", parts: [{ text: messageText }] });

                const response = await fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=${apiKey}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ contents: chatHistory })
                });

                const data = await response.json();
                removeTypingIndicator(typingId);

                if (data.candidates && data.candidates[0].content) {
                    const botReply = data.candidates[0].content.parts[0].text;
                    appendMessage(botReply, 'bot');
                    chatHistory.push({ role: "model", parts: [{ text: botReply }] });
                } else {
                    let pesanError = "Batas kuota harian AI tercapai.";
                    if (data.error && data.error.message) {
                        pesanError = data.error.message;
                    }
                    let detikTunggu = 60;
                    const match = pesanError.match(/Please retry in ([\d\.]+)s/);
                    if (match && match[1]) {
                        detikTunggu = Math.ceil(parseFloat(match[1]));
                    }

                    const errorCard = `
                    <div class='p-3 rounded-4 bg-warning bg-opacity-10 border border-warning border-opacity-50 text-start shadow-sm'>
                        <div class='d-flex align-items-center mb-2'>
                            <div class='bg-warning text-dark p-2 rounded-circle me-2 shadow-sm' style='width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 14px;'>
                                <i class='fa-solid fa-hourglass-half fa-spin'></i>
                            </div>
                            <div>
                                <h6 class='fw-bold text-dark mb-0' style='font-size: 0.9rem;'>Kapasitas AI Sedang Penuh</h6>
                            </div>
                        </div>
                        <p class='text-muted small mb-2'>Sistem membatasi jumlah permintaan gratis dalam waktu singkat. Silakan tunggu sekitar <strong>${detikTunggu} detik</strong>.</p>
                        <button type='button' class='btn btn-warning btn-sm w-100 fw-bold text-dark py-1 rounded-pill' onclick='location.reload();'>
                            <i class='fa-solid fa-rotate-right me-1'></i> Muat Ulang Chat
                        </button>
                    </div>`;
                    appendMessage(errorCard, 'bot', true);
                }
            } catch (error) {
                removeTypingIndicator(typingId);
                appendMessage("Gagal terhubung ke server AI. Periksa koneksi internet Anda.", 'bot');
            } finally {
                userInput.disabled = false;
                sendBtn.disabled = false;
                userInput.focus();
            }
        });

        function appendMessage(text, sender, isHtml = false) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('chat-bubble', sender);
            if (isHtml) {
                messageDiv.innerHTML = text;
            } else {
                messageDiv.innerHTML = text.replace(/\n/g, '<br>');
            }
            chatBox.appendChild(messageDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function showTypingIndicator() {
            const id = 'typing-' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.classList.add('chat-bubble', 'bot');
            typingDiv.id = id;
            typingDiv.innerHTML = `<div class="typing"><span></span><span></span><span></span></div>`;
            chatBox.appendChild(typingDiv);
            chatBox.scrollTop = chatBox.scrollHeight;
            return id;
        }

        function removeTypingIndicator(id) {
            const element = document.getElementById(id);
            if (element) element.remove();
        }
    </script>
</body>
</html>