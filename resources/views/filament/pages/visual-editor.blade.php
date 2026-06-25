@php
    $builder = app(\App\Services\PageBuilderService::class);
    $content = $this->record->content ?? [];
@endphp

<x-filament-panels::page>
    <style>
        .ve-toolbar { position: sticky; top: 0; z-index: 50; background: #1f2937; padding: 0.75rem 1.5rem; display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; border-radius: 0.5rem; margin-bottom: 1.5rem; }
        .ve-toolbar select, .ve-toolbar button { font-size: 0.875rem; }
        .ve-widget { position: relative; transition: all 0.2s; border: 2px solid transparent; border-radius: 0.375rem; }
        .ve-widget:hover { border-color: #60a5fa; }
        .ve-widget.selected { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.3); }
        .ve-overlay { position: absolute; top: 0; left: 0; right: 0; display: none; gap: 0.25rem; padding: 0.375rem; z-index: 40; }
        .ve-widget:hover .ve-overlay, .ve-widget.selected .ve-overlay { display: flex; background: linear-gradient(to bottom, rgba(31,41,55,0.95), transparent); }
        .ve-btn { display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 0.375rem; background: rgba(31,41,55,0.9); color: white; border: none; cursor: pointer; font-size: 0.875rem; transition: background 0.15s; }
        .ve-btn:hover { background: rgba(59,130,246,0.9); }
        .ve-btn.danger:hover { background: rgba(239,68,68,0.9); }
        .ve-empty { border: 2px dashed #6b7280; border-radius: 0.5rem; padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.875rem; }

        .ve-block-label { position: absolute; top: 0; left: 0; padding: 0.25rem 0.5rem; background: rgba(59,130,246,0.9); color: white; font-size: 0.7rem; font-weight: 600; border-radius: 0 0 0.375rem 0; display: none; z-index: 39; }
        .ve-widget:hover .ve-block-label, .ve-widget.selected .ve-block-label { display: block; }

        .ve-modal { display: none; position: fixed; inset: 0; z-index: 100; align-items: center; justify-content: center; }
        .ve-modal.open { display: flex; }
        .ve-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
        .ve-modal-content { position: relative; background: white; border-radius: 0.75rem; max-width: 48rem; width: 90%; max-height: 85vh; overflow-y: auto; padding: 1.5rem; }
        .ve-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        .ve-modal-header h3 { font-size: 1.125rem; font-weight: 600; }

        .ve-settings-field { margin-bottom: 1rem; }
        .ve-settings-field label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: #374151; }
        .dark .ve-settings-field label { color: #d1d5db; }
        .ve-settings-field input, .ve-settings-field textarea, .ve-settings-field select { width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .dark .ve-settings-field input, .dark .ve-settings-field textarea, .dark .ve-settings-field select { background: #374151; border-color: #4b5563; color: white; }
        .dark .ve-modal-content { background: #1f2937; color: white; }
        .dark .ve-modal-header { border-color: #374151; }
    </style>

    {{-- Toolbar --}}
    <div class="ve-toolbar" x-data="toolbar()">
        <span class="text-white font-semibold mr-2">Визуальный редактор</span>
        <select x-ref="typeSelect" x-model="selectedType"
                class="rounded border-gray-300 text-sm dark:bg-gray-700 dark:text-white">
            <option value="">— Добавить блок —</option>
            @foreach($builder->getWidgetsGrouped() as $category => $widgets)
                <optgroup label="{{ $category }}">
                    @foreach($widgets as $w)
                        <option value="{{ $w['name'] }}">{{ $w['title'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
        <button @click="addBlock()"
                class="px-3 py-1.5 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">+ Добавить</button>
        <span class="text-gray-300 text-sm ml-auto">Блоков: <span x-text="blockCount">{{ count($content) }}</span></span>
        <a href="{{ $this->record->slug ? url('/page/' . $this->record->slug) : '#' }}" target="_blank"
           class="px-3 py-1.5 bg-gray-600 text-white rounded text-sm hover:bg-gray-500">Просмотр</a>
    </div>

    {{-- Blocks --}}
    <div x-data="editor()" class="space-y-0" wire:ignore>
        @foreach($content as $index => $block)
            <div class="ve-widget"
                 @click="select({{ $index }})"
                 :class="selectedIndex === {{ $index }} ? 'selected' : ''"
                 data-index="{{ $index }}"
                 data-type="{{ $block['type'] }}"
                 data-settings="{{ base64_encode(json_encode($block['settings'] ?? [])) }}">

                <div class="ve-block-label">{{ $builder->getWidgetTitle($block['type']) ?? $block['type'] }}</div>

                <div class="ve-overlay" style="justify-content: flex-end;">
                    <button class="ve-btn" @click.stop="editSettings({{ $index }})" title="Настройки">⚙</button>
                    <button class="ve-btn" @click.stop="moveUp({{ $index }})" title="Вверх">↑</button>
                    <button class="ve-btn" @click.stop="moveDown({{ $index }})" title="Вниз">↓</button>
                    <button class="ve-btn" @click.stop="duplicate({{ $index }})" title="Дублировать">⧉</button>
                    <button class="ve-btn danger" @click.stop="remove({{ $index }})" title="Удалить">✕</button>
                </div>

                <div style="pointer-events: none; user-select: none;">
                    {!! $builder->renderBlock($block) !!}
                </div>
            </div>
        @endforeach

        @if(empty($content))
            <div class="ve-empty" @click="$refs.typeSelect.focus()">
                <p class="text-lg">Страница пуста</p>
                <p class="mt-2">Выберите блок в панели сверху и нажмите «Добавить»</p>
            </div>
        @endif
    </div>

    {{-- Settings Modal --}}
    <div class="ve-modal" x-data="settingsModal()" :class="open ? 'open' : ''">
        <div class="ve-modal-backdrop" @click="close()"></div>
        <div class="ve-modal-content" x-show="open" x-cloak>
            <div class="ve-modal-header">
                <h3 x-text="'Настройки: ' + blockTitle"></h3>
                <button class="ve-modal-close" @click="close()">✕</button>
            </div>

            <div class="space-y-4">
                <template x-for="(field, idx) in fields" :key="idx">
                    <div class="ve-settings-field">
                        <label x-text="field.label"></label>

                        <input x-show="field.type === 'text' || field.type === 'url'"
                               type="text" x-model="formData[field.key]" :placeholder="field.placeholder ?? ''">

                        <textarea x-show="field.type === 'textarea' || field.type === 'html'"
                                  x-model="formData[field.key]" rows="4"></textarea>

                        <input x-show="field.type === 'number'"
                               type="number" x-model="formData[field.key]">

                        <select x-show="field.type === 'select'" x-model="formData[field.key]">
                            <template x-for="(opt, val) in field.options" :key="val">
                                <option :value="val" x-text="opt"></option>
                            </template>
                        </select>

                        <input x-show="field.type === 'color'"
                               type="color" x-model="formData[field.key]" class="h-10 p-1">

                        <label x-show="field.type === 'boolean'" class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="formData[field.key]" class="rounded">
                            <span x-text="field.help ?? ''" class="text-sm text-gray-500 dark:text-gray-400"></span>
                        </label>
                    </div>
                </template>
                <p x-show="fields.length === 0" class="text-gray-500 text-center py-4">Нет настраиваемых полей</p>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded text-sm hover:bg-gray-300" @click="close()">Отмена</button>
                <button class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600" @click="save()">Применить</button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Build widget metadata from PHP — fields indexed by widget type
            const widgetFields = {
                @foreach($builder->getWidgetsGrouped() as $category => $widgets)
                    @foreach($widgets as $w)
                        @php $safeDefaults = json_encode($w['defaults']); $safeConfig = json_encode($w['config']); @endphp
                        "{{ $w['name'] }}": {
                            title: {{ json_encode($w['title']) }},
                            defaults: {!! $safeDefaults !!},
                            fields: {!! $safeConfig !!}
                        },
                    @endforeach
                @endforeach
            };

            // Helper: parse base64-encoded JSON from data-settings
            function parseSettings(el) {
                const raw = el?.dataset?.settings;
                if (!raw) return {};
                try {
                    return JSON.parse(atob(raw));
                } catch(e) {
                    return {};
                }
            }

            Alpine.data('toolbar', () => ({
                selectedType: '',
                addBlock() {
                    if (!this.selectedType) return;
                    @this.call('addBlock', this.selectedType);
                    this.selectedType = '';
                }
            }));

            Alpine.data('editor', () => ({
                selectedIndex: null,
                blockCount: {{ count($content) }},
                select(index) {
                    this.selectedIndex = this.selectedIndex === index ? null : index;
                },
                moveUp(index) { @this.call('moveBlock', index, 'up'); },
                moveDown(index) { @this.call('moveBlock', index, 'down'); },
                duplicate(index) { @this.call('duplicateBlock', index); },
                remove(index) {
                    if (confirm('Удалить этот блок?')) {
                        @this.call('deleteBlock', index);
                    }
                },
                editSettings(index) {
                    const block = document.querySelector(`[data-index="${index}"]`);
                    if (block) window.dispatchEvent(new CustomEvent('ve-edit-block', { detail: { index, type: block.dataset.type } }));
                }
            }));

            Alpine.data('settingsModal', () => ({
                open: false,
                blockTitle: '',
                blockIndex: -1,
                defaults: {},
                fields: [],
                formData: {},

                init() {
                    window.addEventListener('ve-edit-block', (e) => this.edit(e.detail.index, e.detail.type));
                },

                edit(index, type) {
                    const wf = widgetFields[type];
                    if (!wf) { alert('Неизвестный тип блока: ' + type); return; }

                    this.blockIndex = index;
                    this.blockTitle = wf.title;
                    this.defaults = wf.defaults || {};
                    this.fields = wf.fields || [];

                    // Read current settings from data-settings attribute
                    const el = document.querySelector(`[data-index="${index}"]`);
                    const currentSettings = parseSettings(el);

                    // Merge: use current if exists, otherwise default
                    this.formData = {};
                    Object.keys(this.defaults).forEach(key => {
                        this.formData[key] = (currentSettings[key] !== undefined) ? currentSettings[key] : this.defaults[key];
                    });

                    this.open = true;
                },

                save() {
                    @this.call('updateBlockSettings', this.blockIndex, this.formData);
                    this.close();
                },

                close() {
                    this.open = false;
                    this.fields = [];
                    this.formData = {};
                }
            }));
        });
    </script>
    @endpush
</x-filament-panels::page>
