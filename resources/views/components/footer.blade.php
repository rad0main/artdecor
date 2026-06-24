<footer class="bg-[var(--k-color-secondary)] text-white mt-16">
    <div class="max-w-page mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Контакты --}}
            <div>
                <h4 class="font-heading font-bold text-lg mb-4">Контакты</h4>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li>{{ \App\Models\Setting::get('contacts.phone') }}</li>
                    <li>{{ \App\Models\Setting::get('contacts.email') }}</li>
                    <li>{{ \App\Models\Setting::get('contacts.address') }}</li>
                    <li>{{ \App\Models\Setting::get('contacts.work_hours') }}</li>
                </ul>
            </div>

            {{-- Разделы --}}
            <div>
                <h4 class="font-heading font-bold text-lg mb-4">Разделы</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('catalog') }}" class="text-gray-300 hover:text-white transition-colors">Каталог изображений</a></li>
                    <li><a href="{{ route('works') }}" class="text-gray-300 hover:text-white transition-colors">Наши работы</a></li>
                    <li><a href="{{ route('primerka') }}" class="text-gray-300 hover:text-white transition-colors">Онлайн примерка</a></li>
                    <li><a href="{{ route('services') }}" class="text-gray-300 hover:text-white transition-colors">Услуги</a></li>
                    <li><a href="{{ route('contacts') }}" class="text-gray-300 hover:text-white transition-colors">Контакты</a></li>
                </ul>
            </div>

            {{-- Соцсети --}}
            <div>
                <h4 class="font-heading font-bold text-lg mb-4">Мы в сети</h4>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center hover:bg-[var(--k-color-primary)] transition-colors" aria-label="VK">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M15.684 0H8.316C3.718 0 0 3.718 0 8.316v7.368C0 20.282 3.718 24 8.316 24h7.368C20.282 24 24 20.282 24 15.684V8.316C24 3.718 20.282 0 15.684 0zm3.554 16.728h-1.896c-.504 0-.66-.384-1.56-1.296-.78-.78-1.128-.876-1.332-.876-.264 0-.372.108-.372.648v1.104c0 .348-.12.528-1.008.528-1.488 0-3.144-.912-4.296-2.604-1.644-2.112-2.052-3.504-2.052-3.816 0-.168.072-.324.54-.324h1.896c.408 0 .564.18.732.612.792 2.016 2.112 3.792 2.652 3.792.204 0 .3-.108.3-.636v-2.484c-.072-1.104-.648-1.2-.648-1.596 0-.192.156-.36.348-.36h2.988c.36 0 .48.192.48.612v3.3c0 .36.156.48.252.48.204 0 .372-.12.732-.48.72-.816 1.26-1.944 1.26-1.944.072-.168.192-.312.372-.312h1.896c.552 0 .648.312.528.648-.288.972-2.136 3.456-2.136 3.456-.168.276-.216.408 0 .672.156.216.672.648 1.008 1.044.468.516.828.972 1.02 1.332.192.36.072.54-.42.54z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center hover:bg-[var(--k-color-primary)] transition-colors" aria-label="Telegram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.248c-.182 1.936-.968 6.642-1.368 8.812-.21 1.146-.623 1.53-1.023 1.566-.87.076-1.53-.582-2.373-1.14-1.318-.873-2.063-1.416-3.343-2.268-1.479-.985-.52-1.526.323-2.41.222-.233 4.071-3.734 4.148-4.05.01-.04.018-.188-.07-.266s-.218-.055-.312-.032c-.133.033-2.249 1.43-6.347 4.194-.6.412-1.144.613-1.631.602-.537-.012-1.57-.303-2.337-.552-.942-.306-1.69-.468-1.625-.988.034-.27.398-.547 1.095-.83 4.286-1.868 7.146-3.1 8.58-3.694 4.087-1.695 4.937-1.99 5.49-2 .122-.002.396.028.572.174.148.123.19.289.211.452.02.163.046.535.025.826z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Копирайт --}}
    <div class="border-t border-gray-700">
        <div class="max-w-page mx-auto px-4 py-4 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} ArtDecor. Все права защищены.
        </div>
    </div>
</footer>
