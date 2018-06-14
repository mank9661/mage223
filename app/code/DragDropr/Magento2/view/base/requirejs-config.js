var config = {
    shim: {
        'DragDropr_Magento2/wysiwyg/tiny_mce/plugins/dragdropr/editor_plugin': {
            deps: ['DragDropr_Magento2/js/dragdropr']
        },
        'mage/adminhtml/wysiwyg/tiny_mce/setup': {
            deps: ['DragDropr_Magento2/js/dragdropr']
        }
    }
};
