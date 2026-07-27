@props(['bahasas', 'fields' => [], 'translations' => []])

<div class="mt-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold mb-4" style="color: #1b456f;">Terjemahan</h3>

    @foreach($bahasas as $bahasa)
        @if($bahasa->is_default === 'yes')
            @continue
        @endif

        @php
            $trans = $translations[$bahasa->id] ?? null;
        @endphp
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center gap-2 mb-3">
                @if($bahasa->flag_icon)
                    <span class="text-lg">{{ $bahasa->flag_icon == 'ID' ? '🇮🇩' : '🌐' }}</span>
                @endif
                <h4 class="font-medium text-gray-700">{{ $bahasa->nama }} ({{ $bahasa->kode }})</h4>
            </div>

            @foreach($fields as $field)
                @php
                    $fieldName = $field['name'];
                    $fieldType = $field['type'] ?? 'text';
                    $fieldLabel = $field['label'];
                    $useTinymce = isset($field['tinymce']) && $field['tinymce'];
                    $oldValue = $trans ? $trans->$fieldName : '';
                    $inputId = 'trans_' . $bahasa->id . '_' . $fieldName;
                @endphp

                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-600 mb-1">{{ $fieldLabel }}</label>

                    @if($useTinymce)
                        <textarea name="translations[{{ $bahasa->id }}][{{ $fieldName }}]"
                                  id="{{ $inputId }}"
                                  data-rich-text="true"
                                  rows="{{ $field['rows'] ?? 5 }}">{{ old("translations.{$bahasa->id}.{$fieldName}", $oldValue) }}</textarea>
                    @elseif($fieldType === 'textarea')
                        <textarea name="translations[{{ $bahasa->id }}][{{ $fieldName }}]"
                                  class="input"
                                  rows="{{ $field['rows'] ?? 3 }}">{{ old("translations.{$bahasa->id}.{$fieldName}", $oldValue) }}</textarea>
                    @else
                        <input type="{{ $fieldType }}"
                               name="translations[{{ $bahasa->id }}][{{ $fieldName }}]"
                               class="input"
                               value="{{ old("translations.{$bahasa->id}.{$fieldName}", $oldValue) }}">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($bahasas as $bahasa)
        @if($bahasa->is_default === 'yes')
            @continue
        @endif
        @foreach($fields as $field)
            @if(isset($field['tinymce']) && $field['tinymce'])
                @php $inputId = 'trans_' . $bahasa->id . '_' . $field['name']; @endphp
                if (document.getElementById('{{ $inputId }}')) {
                    tinymce.init({
                        selector: '#{{ $inputId }}',
                        license_key: 'gpl',
                        skin_url: '/tinymce/skins/ui/oxide',
                        content_css: '/tinymce/skins/content/default/content.min.css',
                        height: 300,
                        menubar: true,
                        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
                        images_upload_url: '{{ route("admin.upload") }}',
                        images_upload_handler: function(blobInfo, success, failure) {
                            var formData = new FormData();
                            formData.append('file', blobInfo.blob(), blobInfo.filename());
                            fetch('{{ route("admin.upload") }}', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                                body: formData
                            })
                            .then(function(res) { return res.json(); })
                            .then(function(data) { success(data.location); })
                            .catch(function() { failure('Upload failed'); });
                        },
                        relative_urls: false,
                        remove_script_host: false,
                        convert_urls: true,
                        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }'
                    });
                }
            @endif
        @endforeach
    @endforeach
});
</script>
