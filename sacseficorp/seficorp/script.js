jQuery(document).ready(function($) {
    // ===== SCRIPTS PARA DOCUMENTOS ORIGINALES (documento) =====
    
    // Script para Icono ORIGINAL
    $('#ce_icon_button').click(function(e) {
        e.preventDefault();
        var mediaUploader;

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Seleccionar Imagen',
            button: { text: 'Usar esta imagen' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ce_icon').val(attachment.url);
            $('#ce_icon_preview').html('<img src="' + attachment.url + '" style="max-width:100%; height:auto;" />');
        });

        mediaUploader.open();
    });

    // Agregar Archivos ORIGINAL
    $('#add_file_button').click(function(e) {
        e.preventDefault();
        var index = $('#ce_files_list .ce-file-item').length;
        $('#ce_files_list').append(
            '<div class="ce-file-item" style="margin-bottom:10px;">' +
                '<input type="text" name="ce_files[' + index + '][name]" placeholder="Nombre" style="width:80%; margin-bottom:6px;" />' +
                '<div style="display:flex; gap:10px; align-items:center; margin-bottom:6px;">' +
                '<select name="ce_files[' + index + '][type]" class="ce_type_select" data-index="' + index + '">' +
                    '<option value="doc">Documento</option>' +
                    '<option value="link">Link</option>' +
                '</select>' +
                '<input type="text" class="ce_url_input" name="ce_files[' + index + '][url]" placeholder="URL o #ancla" style="width:60%" />' +
                '</div>' +
                '<button class="button ce_file_button" data-index="' + index + '">Seleccionar Archivo</button>' +
                '<button class="button remove-file" data-index="' + index + '" style="margin-left:10px;">Eliminar</button>' +
            '</div>'
        );
    });

    // Seleccionar Archivo para Archivos ORIGINAL
    $(document).on('click', '.ce_file_button', function(e) {
        e.preventDefault();
        var button = $(this);
        var index = button.data('index');
        var mediaUploader;

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Seleccionar Archivo',
            button: { text: 'Usar este archivo' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="ce_files[' + index + '][url]"]').val(attachment.url);
            button.siblings('a').remove();
            button.after('<a href="' + attachment.url + '" target="_blank" style="margin-left:10px;">Ver archivo</a>');
        });

        mediaUploader.open();
    });

    // Eliminar un archivo ORIGINAL
    $(document).on('click', '.remove-file', function(e) {
        e.preventDefault();
        var button = $(this);
        button.closest('.ce-file-item').remove();
    });

    // ===== SCRIPTS PARA DOCUMENTOS NUEVOS (documento2) =====
    
    // Script para Icono NUEVO
    $('#ce_icon_button_2').click(function(e) {
        e.preventDefault();
        var mediaUploader;

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Seleccionar Imagen',
            button: { text: 'Usar esta imagen' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#ce_icon_2').val(attachment.url);
            $('#ce_icon_preview_2').html('<img src="' + attachment.url + '" style="max-width:100%; height:auto;" />');
        });

        mediaUploader.open();
    });

    // Agregar Archivos NUEVO
    $('#add_file_button_2').click(function(e) {
        e.preventDefault();
        var index = $('#ce_files_list_2 .ce-file-item-2').length;
        $('#ce_files_list_2').append('<div class="ce-file-item-2" style="margin-bottom:10px;">' +
            '<input type="text" name="ce_files_2[' + index + '][name]" placeholder="Nombre del archivo" style="width:80%" />' +
            '<input type="hidden" name="ce_files_2[' + index + '][url]" value="" />' +
            '<button class="button ce_file_button_2" data-index="' + index + '">Seleccionar Archivo</button>' +
            '<button class="button remove-file-2" data-index="' + index + '" style="margin-left:10px;">Eliminar</button>' +
            '</div>');
    });

    // Seleccionar Archivo para Archivos NUEVO
    $(document).on('click', '.ce_file_button_2', function(e) {
        e.preventDefault();
        var button = $(this);
        var index = button.data('index');
        var mediaUploader;

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Seleccionar Archivo',
            button: { text: 'Usar este archivo' },
            multiple: false
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('input[name="ce_files_2[' + index + '][url]"]').val(attachment.url);
            button.siblings('a').remove();
            button.after('<a href="' + attachment.url + '" target="_blank" style="margin-left:10px;">Ver archivo</a>');
        });

        mediaUploader.open();
    });

    // Eliminar un archivo NUEVO
    $(document).on('click', '.remove-file-2', function(e) {
        e.preventDefault();
        var button = $(this);
        button.closest('.ce-file-item-2').remove();
    });

    // ===== MOSTRAR / OCULTAR BOTÓN SEGÚN TIPO (DOCUMENTO O LINK) =====
        function toggleFileButton($item) {
            const type = $item.find('.ce_type_select').val() || 'doc';
            const $btn = $item.find('.ce_file_button');

            if (type === 'link') {
                $btn.hide();
            } else {
                $btn.show();
            }
        }

        // cuando cambia el select
        $(document).on('change', '.ce_type_select', function () {
            toggleFileButton($(this).closest('.ce-file-item'));
        });

        // al cargar la pantalla (items ya existentes)
        $('.ce-file-item').each(function () {
            toggleFileButton($(this));
        });

});
