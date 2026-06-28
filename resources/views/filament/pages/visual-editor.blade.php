@php
    $builder = app(\App\Services\PageBuilderService::class);
    $rawContent = $this->record->content ?? [];
    // Normalize old data key to settings, filter out invalid items
    $content = array_values(array_filter(
        array_map(fn($b) => $builder->normalizeBlock($b), $rawContent),
        fn($b) => is_array($b) && isset($b['type'])
    ));

    // Prepare widget metadata for JS (avoids @json parsing issues with complex closures)
    $widgetsJson = $builder->getWidgetsGrouped()->mapWithKeys(fn($items, $cat) => collect($items)->mapWithKeys(fn($w) => [
        $w['name'] => [
            'title' => $w['title'],
            'defaults' => $w['defaults'],
            'fields' => $w['config'],
        ]
    ])->toArray())->toArray();
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
        .ve-modal-content { position: relative; background: white; border-radius: 0.75rem; max-width: 56rem; width: 95%; max-height: 85vh; overflow-y: auto; padding: 1.5rem; }
        .ve-modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e7eb; }
        .ve-modal-header h3 { font-size: 1.125rem; font-weight: 600; }

        .ve-settings-field { margin-bottom: 1rem; }
        .ve-settings-field label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: #374151; }
        .dark .ve-settings-field label { color: #d1d5db; }
        .ve-settings-field input, .ve-settings-field textarea, .ve-settings-field select { width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.5rem 0.75rem; font-size: 0.875rem; }

        .ve-settings-field input[type="checkbox"] { width: 1rem; height: 1rem; padding: 0; border: 1px solid #d1d5db; border-radius: 0.25rem; cursor: pointer; accent-color: #2563eb; }

        .ve-settings-field input[type="checkbox"]:checked { background-color: #2563eb; border-color: #2563eb; }

        .ve-settings-field input[type="range"] { width: 100%; padding: 0; border: none; }
        .dark .ve-settings-field input, .dark .ve-settings-field textarea, .dark .ve-settings-field select { background: #374151; border-color: #4b5563; color: white; }
        .dark .ve-modal-content { background: #1f2937; color: white; }
        .dark .ve-modal-header { border-color: #374151; }

        /* Tailwind arbitrary height classes used by page builder blocks (missing in admin CSS) */
        .h-\[300px\] { height: 300px; }
        .h-\[400px\] { height: 400px; }
        .h-\[500px\] { height: 500px; }
        .h-\[700px\] { height: 700px; }
        .h-screen { height: 100vh; }
        @media (min-width: 640px) {
            .sm\:h-\[300px\] { height: 300px; }
            .sm\:h-\[500px\] { height: 500px; }
            .sm\:h-\[700px\] { height: 700px; }
        }
        /* Ensure preview container has minimum height even if widget CSS is missing */
        .ve-widget .ve-block-preview { min-height: 60px; }
    </style>

    {{-- Editor root (single x-data scope for toolbar + blocks) --}}
    <div x-data="editor()">
        {{-- Toolbar --}}
        <div class="ve-toolbar">
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
            <button type="button" @click="addBlock()"
                    class="px-3 py-1.5 bg-blue-500 text-white rounded text-sm hover:bg-blue-600">+ Добавить</button>
            <span class="text-gray-300 text-sm ml-auto">Блоков: <span x-text="blockCount">{{ count($content) }}</span></span>
            <a href="{{ $this->record->slug ? url('/page/' . $this->record->slug) : '#' }}" target="_blank"
               class="px-3 py-1.5 bg-gray-600 text-white rounded text-sm hover:bg-gray-500">Просмотр</a>
        </div>

        {{-- Blocks --}}
        <div class="space-y-0">
        @foreach($content as $index => $block)
            <div class="ve-widget"
                 wire:key="block-{{ $index }}"
                 @click="select({{ $index }})"
                 :class="selectedIndex === {{ $index }} ? 'selected' : ''"
                 data-index="{{ $index }}"
                 data-type="{{ $block['type'] }}"
                 data-settings="{{ base64_encode(json_encode($block['settings'] ?? [])) }}">

                <div class="ve-block-label">{{ $builder->getWidgetTitle($block['type']) ?? $block['type'] }}</div>

                <div class="ve-overlay" style="justify-content: flex-end;">
                    <button type="button" class="ve-btn" @click.stop="editSettings({{ $index }})" title="Настройки">⚙</button>
                    <button type="button" class="ve-btn" @click.stop="moveUp({{ $index }})" title="Вверх">↑</button>
                    <button type="button" class="ve-btn" @click.stop="moveDown({{ $index }})" title="Вниз">↓</button>
                    <button type="button" class="ve-btn" @click.stop="duplicate({{ $index }})" title="Дублировать">⧉</button>
                    <button type="button" class="ve-btn danger" @click.stop="remove({{ $index }})" title="Удалить">✕</button>
                </div>

                <div class="ve-block-preview" style="pointer-events: none; user-select: none;">
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
    </div>

    {{-- Settings Modal --}}
    <div class="ve-modal" x-data="settingsModal()" :class="open ? 'open' : ''">
        <div class="ve-modal-backdrop" @click="close()"></div>
        <div class="ve-modal-content" x-show="open" x-cloak>
            <div class="ve-modal-header">
                <h3 x-text="'Настройки: ' + blockTitle"></h3>
                <button type="button" class="ve-modal-close" @click="close()">✕</button>
            </div>

            <div class="space-y-4">
                <template x-for="(field, idx) in fields" :key="idx">
                    <div class="ve-settings-field">
                        <label x-text="field.label" x-show="field.type !== 'boolean'"></label>

                        {{-- Standard fields --}}
                        <input x-show="field.type === 'text' || field.type === 'url'"
                               type="text" x-model="formData[field.key]" :placeholder="field.placeholder ?? ''" :maxlength="field.maxlength ?? ''">

                        <textarea x-show="field.type === 'textarea' || field.type === 'html'"
                                  x-model="formData[field.key]" rows="4" :maxlength="field.maxlength ?? ''"></textarea>

                        <input x-show="field.type === 'number'"
                               type="number" x-model="formData[field.key]"
                               :min="field.min ?? ''" :max="field.max ?? ''" :step="field.step ?? 'any'"
                               style="width: 100px;">

                        {{-- Range slider with percentage display --}}
                        <div x-show="field.type === 'range'" class="flex items-center justify-center gap-2">
                            <input type="range" :min="field.min ?? 0" :max="field.max ?? 100"
                                   x-model="formData[field.key]"
                                   class="accent-blue-500 cursor-pointer"
                                   style="width: 600px; height: 24px;">
                            <input type="number" x-model="formData[field.key]"
                                   :min="field.min ?? 0" :max="field.max ?? 100"
                                   class="text-center border rounded px-1 py-0.5 text-sm"
                                   style="width: 100px;">
                            <span class="text-xs text-gray-500">%</span>
                        </div>

                        <select x-show="field.type === 'select'" x-model="formData[field.key]">
                            <template x-for="(opt, val) in field.options" :key="val">
                                <option :value="val" x-text="opt"></option>
                            </template>
                        </select>

                        <input x-show="field.type === 'color'"
                               type="color" x-model="formData[field.key]" class="h-10 p-1">

                        <label x-show="field.type === 'boolean'"
                               class="inline-flex items-center gap-2 cursor-pointer select-none py-1.5 px-0 bg-transparent border-0"
                               style="width: auto; min-width: 0;">
                            <input type="checkbox" x-model="formData[field.key]"
                                   class="w-4 h-4 rounded cursor-pointer"
                                   style="width: 1rem; height: 1rem; padding: 0; min-width: 1rem;">
                            <span x-text="field.help ?? field.label" class="text-sm text-gray-500 dark:text-gray-400"></span>
                        </label>

                        {{-- Repeater (array of sub-items) --}}
                        <div x-show="field.type === 'repeater'">
                            <template x-for="(item, i) in (formData[field.key] || [])" :key="i">
                                <div class="border border-gray-200 dark:border-gray-600 rounded-md p-3 mb-3 relative">
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-xs text-gray-400 font-mono" x-text="'#' + (i + 1)"></span>
                                        <div class="ml-auto flex gap-1">
                                            <button type="button" class="text-2xl text-gray-500 hover:text-blue-600 disabled:opacity-30 px-2 py-1 leading-none"
                                                    @click="moveRepeaterItem(field.key, i, -1)" :disabled="i === 0" title="Вверх">↑</button>
                                            <button type="button" class="text-2xl text-gray-500 hover:text-blue-600 disabled:opacity-30 px-2 py-1 leading-none"
                                                    @click="moveRepeaterItem(field.key, i, 1)" :disabled="i === (formData[field.key] || []).length - 1" title="Вниз">↓</button>
                                            <button type="button" class="text-xl text-red-500 hover:text-red-700 px-2 py-1"
                                                    @click="removeRepeaterItem(field.key, i)" title="Удалить">✕</button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <template x-for="(sub, si) in (field.fields || [])" :key="si">
                                            <div>
                                                <label class="text-xs text-gray-500 dark:text-gray-400 mb-1" x-text="sub.label"></label>
                                                <input x-show="sub.type === 'text' || sub.type === 'url' || sub.type === 'number'"
                                                       type="text" x-model="formData[field.key][i][sub.key]" :maxlength="sub.maxlength ?? ''"
                                                       class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1.5 text-sm dark:bg-gray-700 dark:text-white"
                                                       :style="sub.width ? 'width:'+sub.width : ''">
                                                <textarea x-show="sub.type === 'textarea'"
                                                          x-model="formData[field.key][i][sub.key]" rows="2"
                                                          class="w-full border border-gray-300 dark:border-gray-600 rounded px-2 py-1.5 text-sm dark:bg-gray-700 dark:text-white"
                                                          :maxlength="sub.maxlength ?? ''"></textarea>

                                                <div x-show="sub.type === 'color'" class="flex items-center gap-2">
                                                    <input type="color" x-model="formData[field.key][i][sub.key]"
                                                           class="w-8 h-8 p-0.5 rounded cursor-pointer border border-gray-300 dark:border-gray-600 bg-white"
                                                           style="min-width: 2rem;">
                                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400"
                                                          x-text="formData[field.key][i][sub.key] || '#000000'"></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <button type="button" class="px-3 py-1.5 bg-gray-500 text-white rounded text-sm hover:bg-gray-600"
                                    @click="addRepeaterItem(field.key, field.fields || [])">+ Добавить слайд</button>
                        </div>
                    </div>
                </template>
                <p x-show="fields.length === 0" class="text-gray-500 text-center py-4">Нет настраиваемых полей</p>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                <button type="button" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded text-sm hover:bg-gray-300" @click="close()">Отмена</button>
                <button type="button" class="px-4 py-2 bg-blue-500 text-white rounded text-sm hover:bg-blue-600" @click="save()">Применить</button>
            </div>
        </div>
    </div>

    {{-- Widget metadata as JSON (safe, no HTML escaping issues) --}}
    <script id="ve-widgets-data" type="application/json">@json($widgetsJson)</script>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Load widget metadata from JSON script tag
            const widgetFields = JSON.parse(document.getElementById('ve-widgets-data').textContent);

            // Helper: parse settings from base64 data-settings attribute
            function parseSettings(el) {
                const raw = el?.dataset?.settings;
                if (!raw) return {};
                try { return JSON.parse(atob(raw)); }
                catch(e) { return {}; }
            }

            Alpine.data('editor', () => ({
                selectedIndex: null,
                selectedType: '',
                blockCount: {{ count($content) }},
                addBlock() {
                    if (!this.selectedType) return;
                    @this.call('addBlock', this.selectedType);
                    this.selectedType = '';
                },
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
                    this.blockTitle = wf.title || type;
                    this.defaults = wf.defaults || {};
                    this.fields = wf.fields || [];

                    const el = document.querySelector(`[data-index="${index}"]`);
                    const currentSettings = parseSettings(el);

                    this.formData = {};
                    Object.keys(this.defaults).forEach(key => {
                        this.formData[key] = (currentSettings[key] !== undefined) ? currentSettings[key] : this.defaults[key];
                    });
                    // Ensure repeater fields exist
                    this.fields.forEach(f => {
                        if (f.type === 'repeater' && !this.formData[f.key]) {
                            this.formData[f.key] = JSON.parse(JSON.stringify(this.defaults[f.key] || []));
                        }
                    });

                    this.open = true;
                },

                addRepeaterItem(key, subFields) {
                    if (!this.formData[key]) this.formData[key] = [];
                    const item = {};
                    subFields.forEach(sf => { item[sf.key] = ''; });
                    this.formData[key].push(item);
                },

                removeRepeaterItem(key, index) {
                    if (this.formData[key]) {
                        this.formData[key].splice(index, 1);
                    }
                },

                moveRepeaterItem(key, index, direction) {
                    const arr = this.formData[key];
                    if (!arr) return;
                    const target = index + direction;
                    if (target < 0 || target >= arr.length) return;
                    [arr[index], arr[target]] = [arr[target], arr[index]];
                    // Trigger Alpine reactivity by reassigning
                    this.formData[key] = [...arr];
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
