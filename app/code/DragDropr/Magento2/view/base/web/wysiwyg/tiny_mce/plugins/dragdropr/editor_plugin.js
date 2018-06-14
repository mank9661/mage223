var DragDropr;
(function (DragDropr) {
    var tinyMCE;
    (function (tinyMCE) {
        var Plugin = (function () {
            function Plugin() {
                var _this = this;
                /**
                 * @inheritDoc
                 */
                this.toggleButton = function () {
                    _this.toggleEditorButton();
                    _this.toggleHtmlButton();
                };
                /**
                 * @inheritDoc
                 */
                this.execute = function (ui, value) {
                    DragDropr.execute(_this.CONFIG_PATH);
                    return true;
                };
                /**
                 * @inheritDoc
                 */
                this.destruct = function () {
                    if (_this._listener) {
                        DragDropr.getConfig().removeListener(_this._listener);
                        _this._listener.destruct();
                    }
                    if (_this._editor) {
                        var element = _this._editor.getElement();
                        if (element instanceof HTMLTextAreaElement) {
                            delete element.dragdroprPlugin;
                        }
                    }
                    _this._editor = null;
                    _this._listener = null;
                };
            }
            /**
             * @inheritDoc
             */
            Plugin.prototype.getInfo = function () {
                return {
                    longname: 'Mageshops DragDropr Plugin',
                    author: 'Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>',
                    authorurl: 'https://app.dragdropr.com/',
                    infourl: 'https://app.dragdropr.com/',
                    version: '1.0.0'
                };
            };
            /**
             * @inheritDoc
             */
            Plugin.prototype.getEditor = function () {
                return this._editor;
            };
            /**
             * @inheritDoc
             */
            Plugin.prototype.init = function (editor, url) {
                editor.onPreInit.add(initSchema);
                this.CONFIG_PATH = editor.getElement().id;
                this._editor = editor;
                this._listener = new DragDropr.Classes.Listener({
                    func: this.toggleButton,
                    name: this.CONFIG_PATH
                });
                editor.addCommand(Plugin.COMMAND, this.execute);
                editor.addButton(Plugin.NAME, {
                    title: Plugin.TITLE,
                    cmd: Plugin.COMMAND,
                    image: url + Plugin.IMAGE,
                    disabled: true
                });
                editor.onPostRender.add(this.toggleButton);
                editor.onRemove.add(this.destruct);
                DragDropr.getConfig().addListener(this._listener);
                editor.getElement().dragdroprPlugin = this;
                return this;
            };
            /**
             * @inheritDoc
             */
            Plugin.prototype.getConfigValue = function (path) {
                path = "" + this.CONFIG_PATH + DragDropr.Classes.Config.PATH_SEPARATOR + path;
                // if (path == 'cms_page_form_content/active') {
                //     let idInput: HTMLInputElement = document.querySelector('input[name="page_id"]');
                //
                //     if (! isElement(idInput)) {
                //         alert('DragDropr currently can be used only on Existing CMS Pages');
                //         return;
                //     }
                //
                //     let pageId: number = parseInt(idInput.value, 10);
                //     return isNumber(pageId);
                // }
                return true; //DragDropr.getConfig().read(path);
            };
            /**
             * @inheritDoc
             */
            Plugin.prototype.toggleEditorButton = function () {
                var editorButton;
                if (!this._editor || !this._editor.controlManager || !this._editor.controlManager.controls || !(editorButton = this._editor.controlManager.get(Plugin.NAME))) {
                    return;
                }
                editorButton.setDisabled(!this.getConfigValue('active'));
            };
            /**
             * @inheritDoc
             */
            Plugin.prototype.toggleHtmlButton = function () {
                var htmlButton = document.getElementById('dragdropr-' + this.CONFIG_PATH);
                if (!(htmlButton instanceof HTMLButtonElement)) {
                    return;
                }
                if (this.getConfigValue('active')) {
                    htmlButton.disabled = false;
                    htmlButton.classList.remove('disabled');
                }
                else {
                    htmlButton.disabled = true;
                    htmlButton.classList.add('disabled');
                }
            };
            /**
             * tinyMCE plugin name
             * @type {string}
             */
            Plugin.NAME = 'dragdropr';
            /**
             * tinyMCE plugin command name
             * @type {string}
             */
            Plugin.COMMAND = 'mceDragdropr';
            /**
             * tinyMCE plugin command button image
             * @type {string}
             */
            Plugin.IMAGE = '/image/dragdropr-logo.png';
            /**
             * tinyMCE plugin command button title
             * @type {string}
             */
            Plugin.TITLE = 'dragdropr.title';
            return Plugin;
        }());
        var initSchema = function (editor) {
            var validElements = 'body[script|style],a[div]';//*[*]
            var elements = validElements.split(','),
                newRules = [];

            for (var i = 0; i < elements.length; i++) {
                var element = elements[i].substring(0, elements[i].indexOf('[')),
                    children = elements[i].substring(element.length + 1, elements[i].length -1),
                    childrenArray = children.split('|'),
                    newChildren = [];

                for (var j = 0; j < childrenArray.length; j++) {
                    var childElement = childrenArray[j];

                    if (! editor.schema.isValidChild(element, childElement)) {
                        newChildren.push(childElement);
                    }
                }

                if (newChildren.length) {
                    newRules.push('+' + element + '[' + newChildren.join('|') + ']');
                }
            }

            if (newRules) {
                editor.schema.addValidChildren(newRules.join(','));
            }
        };
        tinyMCE.Plugin = Plugin;
    })(tinyMCE = DragDropr.tinyMCE || (DragDropr.tinyMCE = {}));
})(DragDropr || (DragDropr = {}));
(function () {
    if (DragDropr && DragDropr.Classes) {
        tinyMCE.addI18n('en.dragdropr', {
            title: 'DragDropr'
        });
        tinyMCE.PluginManager.add(DragDropr.tinyMCE.Plugin.NAME, DragDropr.tinyMCE.Plugin);
    }
})();
//tinymce.ui.Control
//tinymce.ControlManager 
