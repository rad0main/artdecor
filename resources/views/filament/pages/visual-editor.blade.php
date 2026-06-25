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
        .ve-overlay-label { position: absolute; top: 0; left: 0; right: 0; z-index: 39; display: none; padding: 0.375rem 1rem; color: white; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .ve-widget:hover .ve-overlay-label, .ve-widget.selected .ve-overlay-label { display: block; }
        .ve-btn { display: inline-flex; align-items: center; justify-content: center; width: 2rem; height: 2rem; border-radius: 0.375rem; background: rgba(31,41,55,0.9); color: white; border: none; cursor: pointer; font-size: 0.875rem; transition: background 0.15s; }
        .ve-btn:hover { background: rgba(59,130,246,0.9); }
        .ve-btn.danger:hover { background: rgba(239,68,68,0.9); }
        .ve-empty { border: 2px dashed #6b7280; border-radius: 0.5rem; padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.875rem; }
        .ve-empty:hover { border-color: #60a5fa; color: #60a5fa; }

        .ve-block-label { position: absolute; top: 0; left: 0; padding: 0.25rem 0.5rem; background: rgba(59,130,246,0.9); color: white; font-size: 0.7rem; font-weight: 600; border-radius: 0 0 0.375rem 0; display: none; z-index: 39; }
        .ve-widget:hover .ve-block-label, .ve-widget.selected .ve-block-label { display: block; }

        .ve-modal { display: none; position: fixed; inset: 0; z-index: 100; align-items: center; justify-content: center; }
        .ve-modal.open { display: flex; }
        .ve-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.5); }
        .ve-modal-content { position: relative; background: white; border-radius: 0.75rem; max-width: 48rem; width: 90%; max-height: 85vh; overflow-y: auto; padding: 1.5rem; }
        .ve-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        .ve-modal-header h3 { font-size: 1.125rem; font-weight: 600; }
        .ve-modal-close { width: 2rem; height: 2rem; border-radius: 0.375rem; border: none; background: #f3f4f6; cursor: pointer; display: flex; align-items: center; justify-content: center; }

        .ve-settings-field { margin-bottom: 1rem; }
        .ve-settings-field label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: #374151; }
        .dark .ve-settings-field label { color: #d1d5db; }
        .ve-settings-field input, .ve-settings-field textarea, .ve-settings-field select { width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
        .dark .ve-settings-field input, .dark .ve-settings-field textarea, .dark .ve-settings-field select { background: #374151; border-color: #4b5563; color: white; }

        .dark .ve-modal-content { background: #1f2937; color: white; }
        .dark .ve-modal-header { border-color: #374151; }
        .dark .ve-modal-close { background: #374151; color: white; }
        .dark .ve-empty { border-color: #4b5563; color: #6b7280; }
    </style>

    {{-- Toolbar --}}
    <div class="ve-toolbar" x-data="toolbar()">
        <span class="text-white font-semibold mr-2">Визуальный редактор</span>

        <select x-model="selectedType"
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
                class="px-3 py-1.5 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">
            + Добавить
        </button>

        <span class="text-gray-300 text-sm ml-auto">Блоков: <span x-text="blockCount">{{ count($content) }}</span></span>

        <a href="{{ $this->record->slug ? url('/page/' . $this->record->slug) : '#' }}"
           target="_blank"
           class="px-3 py-1.5 bg-gray-600 text-white rounded text-sm hover:bg-gray-500">
           Просмотр
        </a>
    </div>

    {{-- Blocks --}}
    <div x-data="editor()" class="space-y-0" wire:ignore>
        @foreach($content as $index => $block)
            <div class="ve-widget"
                 @click="select({{ $index }})"
                 :class="selectedIndex === {{ $index }} ? 'selected' : ''"
                 data-index="{{ $index }}"
                 data-type="{{ $block['type'] }}">

                {{-- Block type label --}}
                <div class="ve-block-label">
                    {{ $builder->getWidgetTitle($block['type']) ?? $block['type'] }}
                </div>

                {{-- Overlay toolbar --}}
                <div class="ve-overlay" style="justify-content: flex-end;">
                    <button class="ve-btn" @click.stop="editSettings({{ $index }})" title="Настройки">⚙</button>
                    <button class="ve-btn" @click.stop="moveUp({{ $index }})" title="Вверх">↑</button>
                    <button class="ve-btn" @click.stop="moveDown({{ $index }})" title="Вниз">↓</button>
                    <button class="ve-btn" @click.stop="duplicate({{ $index }})" title="Дублировать">⧉</button>
                    <button class="ve-btn danger" @click.stop="remove({{ $index }})" title="Удалить">✕</button>
                </div>

                {{-- Rendered block --}}
                <div style="pointer-events: none; user-select: none;">
                    {!! $builder->renderBlock($block) !!}
                </div>
            </div>
        @endforeach

        @if(empty($content))
            <div class="ve-empty" @click="addFirstBlock()">
                <p class="text-lg">Страница пуста</p>
                <p class="mt-2">Выберите блок в панели сверху и нажмите «Добавить»</p>
            </div>
        @endif
    </div>

    {{-- Settings Modal with inline form --}}
    <div class="ve-modal" x-data="settingsModal()" :class="open ? 'open' : ''">
        <div class="ve-modal-backdrop" @click="close()"></div>
        <div class="ve-modal-content" x-show="open" x-cloak>
            <div class="ve-modal-header">
                <h3 x-text="'Настройки: ' + blockTitle"></h3>
                <button class="ve-modal-close" @click="close()">✕</button>
            </div>

            <div class="space-y-4">
                <template x-for="(field, key) in fields" :key="key">
                    <div class="ve-settings-field">
                        <label x-text="field.label"></label>
                        <div x-show="field.type === 'text' || field.type === 'url'">
                            <input type="text" x-model="formData[key]" :placeholder="field.placeholder ?? ''">
                        </div>
                        <div x-show="field.type === 'textarea' || field.type === 'html'">
                            <textarea x-model="formData[key]" rows="4"></textarea>
                        </div>
                        <div x-show="field.type === 'number'">
                            <input type="number" x-model="formData[key]">
                        </div>
                        <div x-show="field.type === 'select'">
                            <select x-model="formData[key]">
                                <template x-for="(opt, val) in field.options" :key="val">
                                    <option :value="val" x-text="opt"></option>
                                </template>
                            </select>
                        </div>
                        <div x-show="field.type === 'color'">
                            <input type="color" x-model="formData[key]" class="h-10 p-1">
                        </div>
                        <div x-show="field.type === 'boolean'">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="formData[key]" class="rounded">
                                <span x-text="field.help ?? ''" class="text-sm text-gray-500 dark:text-gray-400"></span>
                            </label>
                        </div>
                    </div>
                </template>
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
            // Widget metadata for inline editing
            const widgetFields = {
                @foreach($builder->getWidgetsGrouped() as $category => $widgets)
                    @foreach($widgets as $w)
                        '{{ $w['name'] }}': {
                            title: '{{ $w['title'] }}',
                            defaults: {{ json_encode($w['defaults']) }},
                            fields: @json($w['config'] ?? [])
                        },
                    @endforeach
                @endforeach
            };

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
                    const type = block?.dataset?.type;
                    if (type && window.editBlockSettings) {
                        window.editBlockSettings(index, type);
                    }
                },
                addFirstBlock() { document.querySelector('.ve-toolbar select')?.focus(); }
            }));

            Alpine.data('settingsModal', () => ({
                open: false,
                blockTitle: '',
                blockType: '',
                blockIndex: -1,
                defaults: {},
                fields: [],
                formData: {},

                init() {
                    window.editBlockSettings = (index, type) => this.edit(index, type);
                },

                edit(index, type) {
                    this.blockIndex = index;
                    this.blockType = type;
                    const wf = widgetFields[type];
                    this.blockTitle = wf?.title || type;
                    this.defaults = wf?.defaults || {};
                    this.fields = wf?.fields || [];

                    // Build form data from current settings
                    @this.call('getBlockSettings', index).then(settings => {
                        const data = settings || this.defaults;
                        this.formData = {};
                        Object.keys(this.defaults).forEach(key => {
                            this.formData[key] = data[key] !== undefined ? data[key] : this.defaults[key];
                        });
                        this.open = true;
                    }).catch(() => {
                        this.formData = {...this.defaults};
                        this.open = true;
                    });
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
