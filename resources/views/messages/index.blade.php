@extends(request()->boolean('panel') ? 'layouts.chat' : 'layouts.app')

@section('content')
    @unless(request()->boolean('panel'))
    <div class="hidden md:block bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6">
        <h1 class="font-bold text-slate-900 dark:text-white">Pesan</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Obrolan terbuka di panel kanan bawah. Tutup panel untuk kembali ke beranda.</p>
        <a href="{{ route('feed') }}" class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold mt-3 inline-block">Kembali ke beranda</a>
    </div>
    @endunless
    <div id="message-panel" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden flex flex-col {{ request()->boolean('panel') ? 'h-full' : 'h-[calc(100dvh-11rem)] min-h-[400px] md:fixed md:bottom-5 md:right-5 md:z-50 md:w-[min(760px,calc(100vw-2.5rem))] md:h-[min(620px,calc(100dvh-2.5rem))] md:flex-row rounded-3xl' }}" aria-label="Panel pesan">

        <!-- Left: Conversations & Contacts List -->
        <div class="w-full {{ request()->boolean('panel') ? '' : 'md:w-72 md:shrink-0' }} border-r border-slate-200/80 dark:border-slate-800 flex flex-col h-full bg-slate-50/50 dark:bg-slate-900/50 {{ $activeUser ? (request()->boolean('panel') ? 'hidden' : 'hidden md:flex') : 'flex' }}">
            <div class="p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 dark:text-white">Pesan Obrolan</h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Komunikasi langsung</p>
                </div>
                @unless(request()->boolean('panel'))<a href="{{ route('feed') }}" aria-label="Tutup pesan" class="hidden md:inline-flex text-slate-500 hover:text-indigo-600 text-xl leading-none">&times;</a>@endunless
            </div>

            <!-- Conversations Scrollable Area -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60 no-scrollbar">
                @forelse ($conversations as $partner)
                    <a href="{{ route('messages.show', $partner->username) }}{{ request()->boolean('panel') ? '?panel=1' : '' }}"
                        class="flex items-center gap-3 p-3.5 hover:bg-white dark:hover:bg-slate-800/60 transition {{ $activeUser && $activeUser->id === $partner->id ? 'bg-white dark:bg-slate-800 font-bold' : '' }}">
                        <div class="relative shrink-0">
                            <img src="{{ $partner->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700" alt="{{ $partner->name }}">
                            @if ($partner->unread_messages_count > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-indigo-600 text-white text-[9px] font-bold flex items-center justify-center">
                                    {{ $partner->unread_messages_count }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                <span class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $partner->name }}</span>
                                @if ($partner->last_message)
                                    <span class="text-[10px] text-slate-400">{{ $partner->last_message->created_at->format('H:i') }}</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                {{ $partner->last_message ? $partner->last_message->content : 'Mulai percakapan baru' }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-xs text-slate-400">
                        Belum ada obrolan aktif. Pilih pengguna di bawah untuk mulai kirim pesan!
                    </div>
                @endforelse

                <!-- Suggestions / Start new chat -->
                <div class="p-3 border-t border-slate-200/80 dark:border-slate-800">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2 px-1">Mulai Obrolan Baru</span>
                    <div class="space-y-1.5">
                        @foreach ($suggestedUsers as $sUser)
                            @if (!$activeUser || $activeUser->id !== $sUser->id)
                                <a href="{{ route('messages.show', $sUser->username) }}{{ request()->boolean('panel') ? '?panel=1' : '' }}"
                                    class="flex items-center justify-between p-2 rounded-xl hover:bg-white dark:hover:bg-slate-800 text-xs transition">
                                    <div class="flex items-center gap-2 truncate">
                                        <img src="{{ $sUser->avatar_url }}" class="w-6 h-6 rounded-full object-cover shrink-0" alt="">
                                        <span class="font-medium text-slate-700 dark:text-slate-300 truncate">{{ $sUser->name }}</span>
                                    </div>
                                    <span class="text-[10px] text-indigo-500 font-bold shrink-0">Chat &rarr;</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Active Chat Window -->
        <div class="flex-1 min-h-0 flex flex-col h-full bg-white dark:bg-slate-900 {{ !$activeUser ? (request()->boolean('panel') ? 'hidden' : 'hidden md:flex') : 'flex' }}">
            @if ($activeUser)
                <!-- Chat Header -->
                <div class="p-3.5 sm:p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Mobile Back to Contacts List -->
                        <a href="{{ route('messages.index') }}{{ request()->boolean('panel') ? '?panel=1' : '' }}" class="{{ request()->boolean('panel') ? '' : 'md:hidden' }} p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>

                        <a href="{{ route('profile.show', $activeUser->username) }}" target="_top" class="flex items-center gap-3 group min-w-0">
                            <div class="relative shrink-0">
                                <img src="{{ $activeUser->avatar_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700 group-hover:scale-105 transition" alt="{{ $activeUser->name }}">
                            </div>
                            <div class="min-w-0">
                                <span class="block text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $activeUser->name }}</span>
                                <span class="block text-xs text-slate-400 dark:text-slate-500 truncate">@<span>{{ $activeUser->username }}</span></span>
                            </div>
                        </a>
                    </div>

                    <a href="{{ route('profile.show', $activeUser->username) }}" target="_top"
                        class="px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                        Lihat Profil
                    </a>
                </div>

                <!-- Messages List Box -->
                <div id="messages-box" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50/50 dark:bg-slate-950/40">
                    @forelse ($messages as $msg)
                        @php $isMe = $msg->sender_id === Auth::id(); @endphp
                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}" data-id="{{ $msg->id }}">
                            <div class="max-w-[80%] sm:max-w-md {{ $isMe ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-sm shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700 rounded-2xl rounded-tl-sm shadow-sm' }} p-3 text-xs sm:text-sm">
                                <p class="break-words whitespace-pre-line leading-relaxed">{{ $msg->content }}</p>
                                <span class="block text-[10px] text-right mt-1 {{ $isMe ? 'text-indigo-200' : 'text-slate-400 dark:text-slate-500' }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div id="no-messages-placeholder" class="text-center py-12 text-xs text-slate-400">
                            Belum ada pesan sebelumnya. Kirim pesan pertama untuk memulai percakapan!
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Footer -->
                <div class="p-3 sm:p-4 border-t border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <form id="chat-form" action="{{ route('messages.store', $activeUser->username) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" id="chat-input" name="content" autocomplete="off" required
                            placeholder="Ketik pesan untuk {{ $activeUser->name }}..."
                            class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">

                        <button type="submit" id="btn-send-chat"
                            class="p-2.5 sm:px-5 sm:py-2.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm shadow-indigo-500/30 transition shrink-0 flex items-center gap-1.5">
                            <span class="hidden sm:inline">Kirim</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Chat Script: Instant Send & Real-time Auto Polling -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const messagesBox = document.getElementById('messages-box');
                        const chatForm = document.getElementById('chat-form');
                        const chatInput = document.getElementById('chat-input');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const partnerUsername = @json($activeUser->username);

                        // Scroll to bottom on load
                        function scrollToBottom() {
                            if (messagesBox) {
                                messagesBox.scrollTop = messagesBox.scrollHeight;
                            }
                        }
                        scrollToBottom();

                        // Get latest message ID
                        let lastMessageId = {{ $messages->max('id') ?? 0 }};

                        // Append a message bubble to the chat box
                        function appendMessageBubble(msg) {
                            if (messagesBox.querySelector(`[data-id="${msg.id}"]`)) return;
                            lastMessageId = Math.max(lastMessageId, msg.id);
                            const placeholder = document.getElementById('no-messages-placeholder');
                            if (placeholder) placeholder.remove();

                            const isMe = msg.is_me;
                            const div = document.createElement('div');
                            div.className = `flex ${isMe ? 'justify-end' : 'justify-start'}`;
                            div.setAttribute('data-id', msg.id);

                            const bubble = document.createElement('div');
                            bubble.className = `max-w-[80%] sm:max-w-md p-3 text-xs sm:text-sm ${isMe ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl rounded-tr-sm shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700 rounded-2xl rounded-tl-sm shadow-sm'}`;
                            const text = document.createElement('p');
                            text.className = 'break-words whitespace-pre-line leading-relaxed';
                            text.textContent = msg.content;
                            const time = document.createElement('span');
                            time.className = `block text-[10px] text-right mt-1 ${isMe ? 'text-indigo-200' : 'text-slate-400 dark:text-slate-500'}`;
                            time.textContent = msg.created_at_human;
                            bubble.append(text, time);
                            div.appendChild(bubble);
                            messagesBox.appendChild(div);
                            scrollToBottom();
                        }

                        // Send message asynchronously
                        chatForm.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const content = chatInput.value.trim();
                            if (!content) return;

                            try {
                                const response = await fetch(`/messages/${partnerUsername}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ content: content })
                                });

                                if (response.ok) {
                                    const data = await response.json();
                                    if (data.success) {
                                        appendMessageBubble(data.message);
                                        chatInput.value = '';
                                    }
                                } else {
                                    throw new Error('Pesan gagal dikirim.');
                                }
                            } catch (err) {
                                alert('Pesan gagal dikirim. Silakan coba lagi.');
                            }
                        });

                        // Poll for new incoming messages every 2.5 seconds
                        setInterval(async () => {
                            const afterId = lastMessageId;
                            try {
                                const response = await fetch(`/messages/${partnerUsername}/poll?after_id=${afterId}`, {
                                    headers: { 'Accept': 'application/json' }
                                });
                                if (response.ok) {
                                    const data = await response.json();
                                    if (data.success && data.messages.length > 0) {
                                        data.messages.forEach(msg => appendMessageBubble(msg));
                                    }
                                }
                            } catch (err) {
                                // silent network fail
                            }
                        }, 2500);
                    });
                </script>
            @else
                <!-- No Active Chat Selected -->
                <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Pilih Obrolan</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 max-w-xs">Pilih percakapan dari daftar di sebelah kiri atau mulai obrolan baru dengan anggota komunitas.</p>
                </div>
            @endif
        </div>

    </div>
@endsection
