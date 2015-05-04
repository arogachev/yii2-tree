yii.tree = (function($) {
    var pub = {
        modelClass: undefined,

        createNode: function(event, target) {
            $.post(
                '/tree/append-to',
                {
                    modelClass: pub.modelClass,
                    parentPk: target.parent
                },
                function(data) {
                    $(event.target).jstree('set_id', target.node, data.pk);
                }
            );
        },

        renameNode: function(event, target) {
            if (target.text != target.old) {
                $.post(
                    '/tree/rename',
                    {
                        modelClass: pub.modelClass,
                        modelPk: target.node.id,
                        name: target.text
                    }
                );
            }
        },

        moveNode: function(event, target) {
            var tree = $(event.target);
            var prevPk = tree.jstree('get_prev_dom', target.node).attr('id');
            var nextPk = tree.jstree('get_next_dom', target.node).attr('id');

            if (target.old_parent != target.parent) {
                $.post(
                    '/tree/append-to',
                    {
                        modelClass: pub.modelClass,
                        parentPk: target.parent,
                        modelPk: target.node.id
                    }
                );

                return;
            }

            if (prevPk) {
                $.post(
                    '/tree/insert-after',
                    {
                        modelClass: pub.modelClass,
                        modelPk: target.node.id,
                        prevModelPk: prevPk
                    }
                );
            } else if (nextPk) {
                $.post(
                    '/tree/insert-before',
                    {
                        modelClass: pub.modelClass,
                        modelPk: target.node.id,
                        nextModelPk: nextPk
                    }
                );
            }
        },

        deleteNode: function(event, target) {
            $.post(
                '/tree/delete',
                {
                    modelClass: pub.modelClass,
                    modelPk: target.node.id
                }
            );
        }
    };

    return pub;
})(jQuery);
