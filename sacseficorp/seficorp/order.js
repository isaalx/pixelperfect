jQuery(document).ready(function($) {
    var $table = $('tbody');

    // Verificar si estamos en la página de documentos originales
    if ($('body').hasClass('post-type-documento')) {
        $table.sortable({
            items: 'tr',
            cursor: 'move',
            axis: 'y',
            update: function(event, ui) {
                var order = [];
                $table.find('tr').each(function(index) {
                    var postId = $(this).attr('id').replace('post-', '');
                    order.push({ id: postId, position: index });
                });

                $.post(ajaxurl, {
                    action: 'ce_update_post_order',
                    order: order
                });
            }
        });
    }

    // Verificar si estamos en la página de documentos nuevos
    if ($('body').hasClass('post-type-documento2')) {
        $table.sortable({
            items: 'tr',
            cursor: 'move',
            axis: 'y',
            update: function(event, ui) {
                var order = [];
                $table.find('tr').each(function(index) {
                    var postId = $(this).attr('id').replace('post-', '');
                    order.push({ id: postId, position: index });
                });

                $.post(ajaxurl, {
                    action: 'ce_update_post_order_2',
                    order: order
                });
            }
        });
    }
});