{{-- Floating Toolbar - pojok kanan bawah --}}
<div id="floating-toolbar" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3" style="position: fixed !important; bottom: 24px !important; right: 24px !important; z-index: 50 !important;">

    {{-- Action Buttons (muncul saat toolbar dibuka) --}}
    <div id="toolbar-actions" class="flex flex-col items-end gap-3 transition-all duration-300 origin-bottom"
         style="opacity:0; transform: scale(0.8) translateY(10px); pointer-events:none;">

        {{-- Label + Tombol WhatsApp --}}
        <div class="flex items-center gap-2">
            <span class="bg-gray-900/90 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow whitespace-nowrap backdrop-blur-sm">
                Hubungi via WhatsApp
            </span>
            <a href="https://wa.me/11111" target="_blank"
               class="flex items-center justify-center w-12 h-12 rounded-full bg-[#25D366] text-white shadow-lg hover:shadow-[#25D366]/50 hover:scale-110 transition-all duration-200"
               title="WhatsApp">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
            </a>
        </div>

        {{-- Label + Tombol AI Chat --}}
        <div class="flex items-center gap-2">
            <span class="bg-gray-900/90 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow whitespace-nowrap backdrop-blur-sm">
                Chat dengan AI
            </span>
            <button onclick="openAIChat()"
                    class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 text-white shadow-lg hover:shadow-violet-500/50 hover:scale-110 transition-all duration-200"
                    title="AI Chat">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Tombol Toggle Utama --}}
    <button id="toolbar-toggle"
            onclick="toggleToolbar()"
            class="flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 text-white shadow-lg shadow-violet-500/30 hover:shadow-violet-500/60 hover:scale-110 transition-all duration-300"
            title="Bantuan">
        {{-- Icon: headset (pusat bantuan) --}}
        <svg id="toolbar-icon-default" class="w-7 h-7 transition-all duration-300" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
            <!-- Kepala headset -->
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 11a9 9 0 0118 0"/>
            <!-- Telinga kiri -->
            <rect x="2" y="11" width="3" height="5" rx="1.5" fill="currentColor" stroke="none"/>
            <!-- Telinga kanan -->
            <rect x="19" y="11" width="3" height="5" rx="1.5" fill="currentColor" stroke="none"/>
            <!-- Kabel mic -->
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 16v1a4 4 0 01-4 4h-2"/>
            <!-- Mic bulat -->
            <circle cx="13" cy="21" r="1" fill="currentColor" stroke="none"/>
        </svg>
        {{-- Icon close (X) --}}
        <svg id="toolbar-icon-close" class="w-7 h-7 transition-all duration-300 hidden" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

{{-- AI Chatbox Modal --}}
<div id="ai-chatbox" class="fixed bottom-24 right-6 z-50 w-80 sm:w-96 hidden" style="max-height: calc(100vh - 7rem);">
    <div class="bg-gray-900 border border-white/10 rounded-2xl shadow-2xl shadow-black/50 flex flex-col overflow-hidden" style="height: min(480px, calc(100vh - 7rem))">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-violet-600/20 to-indigo-600/20 border-b border-white/10">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white text-sm font-semibold leading-tight">Clipfluence AI</p>
                    <p class="text-green-400 text-xs flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                        Online
                    </p>
                </div>
            </div>
            <button onclick="closeAIChat()" class="text-gray-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-thin scrollbar-thumb-white/10">
            {{-- Pesan selamat datang --}}
            <div class="flex gap-2.5">
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div class="bg-white/10 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%]">
                    <p class="text-white text-sm leading-relaxed">Halo! Saya AI Assistant Clipfluence. Ada yang bisa saya bantu? 👋</p>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="p-3 border-t border-white/10 bg-gray-900/50">
            <div class="flex items-end gap-2">
                <textarea id="chat-input"
                          rows="1"
                          placeholder="Ketik pesan..."
                          class="flex-1 bg-white/10 text-white placeholder-gray-400 text-sm rounded-xl px-3.5 py-2.5 resize-none outline-none focus:ring-1 focus:ring-violet-500 border border-white/10 focus:border-violet-500 transition-all max-h-24 scrollbar-thin"
                          onkeydown="handleChatKey(event)"
                          oninput="autoResizeTextarea(this)"></textarea>
                <button id="chat-send-btn"
                        onclick="sendChatMessage()"
                        class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 text-white flex items-center justify-center hover:opacity-90 transition-opacity disabled:opacity-40"
                        title="Kirim">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let toolbarOpen = false;
    let chatHistory = [];

    // ── Toolbar toggle ──────────────────────────────────────────────
    function toggleToolbar() {
        toolbarOpen = !toolbarOpen;
        const actions = document.getElementById('toolbar-actions');
        const iconDefault = document.getElementById('toolbar-icon-default');
        const iconClose = document.getElementById('toolbar-icon-close');

        if (toolbarOpen) {
            actions.style.opacity = '1';
            actions.style.transform = 'scale(1) translateY(0)';
            actions.style.pointerEvents = 'auto';
            iconDefault.classList.add('hidden');
            iconClose.classList.remove('hidden');
        } else {
            actions.style.opacity = '0';
            actions.style.transform = 'scale(0.8) translateY(10px)';
            actions.style.pointerEvents = 'none';
            iconDefault.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    // ── AI Chatbox ──────────────────────────────────────────────────
    function openAIChat() {
        document.getElementById('ai-chatbox').classList.remove('hidden');
        document.getElementById('chat-input').focus();
    }

    function closeAIChat() {
        document.getElementById('ai-chatbox').classList.add('hidden');
    }

    function handleChatKey(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendChatMessage();
        }
    }

    function autoResizeTextarea(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 96) + 'px';
    }

    function appendMessage(role, text) {
        const container = document.getElementById('chat-messages');
        const isUser = role === 'user';

        const wrapper = document.createElement('div');
        wrapper.className = isUser
            ? 'flex gap-2.5 justify-end'
            : 'flex gap-2.5';

        if (!isUser) {
            wrapper.innerHTML = `
                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <div class="bg-white/10 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%]">
                    <p class="text-white text-sm leading-relaxed">${escapeHtml(text)}</p>
                </div>`;
        } else {
            wrapper.innerHTML = `
                <div class="bg-gradient-to-br from-violet-600 to-indigo-600 rounded-2xl rounded-tr-sm px-3.5 py-2.5 max-w-[85%]">
                    <p class="text-white text-sm leading-relaxed">${escapeHtml(text)}</p>
                </div>`;
        }

        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
        return wrapper;
    }

    function appendTypingIndicator() {
        const container = document.getElementById('chat-messages');
        const wrapper = document.createElement('div');
        wrapper.id = 'typing-indicator';
        wrapper.className = 'flex gap-2.5';
        wrapper.innerHTML = `
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
            </div>
            <div class="bg-white/10 rounded-2xl rounded-tl-sm px-4 py-3">
                <div class="flex gap-1 items-center h-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:150ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:300ms"></span>
                </div>
            </div>`;
        container.appendChild(wrapper);
        container.scrollTop = container.scrollHeight;
    }

    function removeTypingIndicator() {
        const el = document.getElementById('typing-indicator');
        if (el) el.remove();
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/\n/g, '<br>');
    }

    async function sendChatMessage() {
        const input = document.getElementById('chat-input');
        const sendBtn = document.getElementById('chat-send-btn');
        const message = input.value.trim();
        if (!message) return;

        // Tampilkan pesan user
        appendMessage('user', message);
        chatHistory.push({ role: 'user', content: message });

        // Reset input
        input.value = '';
        input.style.height = 'auto';
        sendBtn.disabled = true;

        // Typing indicator
        appendTypingIndicator();

        try {
            const response = await fetch('/ai/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ message, history: chatHistory.slice(-10) })
            });

            removeTypingIndicator();

            if (!response.ok) throw new Error('Gagal menghubungi AI');

            const data = await response.json();
            const reply = data.reply || 'Maaf, saya tidak bisa merespons saat ini.';

            appendMessage('assistant', reply);
            chatHistory.push({ role: 'assistant', content: reply });

        } catch (err) {
            removeTypingIndicator();
            console.error('Chat error:', err);
            appendMessage('assistant', 'Maaf, terjadi kesalahan. Silakan coba lagi atau hubungi kami via WhatsApp.');
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    }
</script>
