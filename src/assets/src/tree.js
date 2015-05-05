yii.tree = (function($) {
    var pub = {
        modelClass: undefined,

        openNode: function(event, target) {
            $.post(
                '/tree/open',
                {
                    modelClass: pub.modelClass,
                    modelPk: target.node.id
                }
            );
        },

        closeNode: function(event, target) {
            $.post(
                '/tree/close',
                {
                    modelClass: pub.modelClass,
                    modelPk: target.node.id
                }
            );
        },

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
            var $tree = $(event.target);
            var $prevNode = $tree.jstree('get_prev_dom', target.node, true);
            var $nextNode = $tree.jstree('get_next_dom', target.node, true);

            if ($prevNode) {
                $.post(
                    '/tree/insert-after',
                    {
                        modelClass: pub.modelClass,
                        modelPk: target.node.id,
                        prevModelPk: $prevNode.attr('id')
                    }
                );
            } else if ($nextNode) {
                $.post(
                    '/tree/insert-before',
                    {
                        modelClass: pub.modelClass,
                        modelPk: target.node.id,
                        nextModelPk: $nextNode.attr('id')
                    }
                );
            }

            if (target.old_parent != target.parent) {
                var $newParent = $tree.jstree('get_node', target.parent);
                if (!$newParent.state.opened) {
                    $.post(
                        '/tree/append-to',
                        {
                            modelClass: pub.modelClass,
                            parentPk: target.parent,
                            modelPk: target.node.id
                        }
                    );
                }
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
