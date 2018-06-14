define([
    'tinymce',
    'DragDropr_Magento2/wysiwyg/tiny_mce/plugins/dragdropr/editor_plugin'
], function () {
    describe('DragDropr_Magento2/wysiwyg/tiny_mce/plugins/dragdropr/editor_plugin', function () {
        it('DragDropr Namespace', function () {
            expect(DragDropr).toEqual(jasmine.any(Object));
        });

        describe('DragDropr', function () {
            it('DragDropr.tinyMCE', function () {
                expect(DragDropr.tinyMCE).toEqual(jasmine.any(Object));
            });

            describe('DragDropr.tinyMCE', function () {
                beforeEach(function () {
                    textareaMock = document.createElement('textarea');
                    textareaMock.id = 'text-area-mock';
                    editorMock = {
                        url: 'http://test.com/',
                        commands: {},
                        buttons: {},
                        getElement: function () {
                            return textareaMock;
                        },
                        addCommand: function (command, func) {
                            this.commands[command] = func;
                        },
                        addButton: function (name, options) {
                            this.buttons[name] = options;
                        },
                        onPostRender: {
                            functions: [],
                            add: function (func) {
                                this.functions.push(func);
                            }
                        },
                        onRemove: {
                            functions: [],
                            add: function (func) {
                                this.functions.push(func);
                            }
                        }
                    };
                    spyOn(editorMock, 'addCommand').and.callThrough();
                    spyOn(editorMock, 'addButton').and.callThrough();
                    spyOn(editorMock.onPostRender, 'add').and.callThrough();
                    spyOn(editorMock.onRemove, 'add').and.callThrough();
                });

                it('DragDropr.tinyMCE.Plugin', function () {
                    expect(DragDropr.isFunction(DragDropr.tinyMCE.Plugin)).toBe(true);
                });

                describe('DragDropr.tinyMCE.Plugin', function () {
                    beforeEach(function () {
                        elementPath = ['dragdropr', 'tinyMCE', 'Plugin', 'editorMock', 'getElement'].join(DragDropr.Classes.Config.PATH_SEPARATOR);
                        pluginMock = new DragDropr.tinyMCE.Plugin();
                        spyOn(pluginMock, 'execute').and.callThrough();
                        spyOn(pluginMock, 'toggleButton').and.callThrough();
                        spyOn(pluginMock, 'destruct').and.callThrough();
                        DragDropr.getConfig().setDataSource({})
                            .write(
                                editorMock.getElement().id + DragDropr.Classes.Config.PATH_SEPARATOR + elementPath,
                                editorMock.getElement().id + DragDropr.Classes.Config.PATH_SEPARATOR + elementPath
                            );
                    });

                    it('DragDropr.tinyMCE.Plugin Uninitialized', function () {
                        expect(pluginMock.getEditor()).toBe(void 0);
                        expect(pluginMock.getInfo()).toEqual(jasmine.any(Object));
                    });

                    it('DragDropr.tinyMCE.Plugin Initialization', function () {
                        pluginMock.init(editorMock, editorMock.url);
                        expect(pluginMock.getEditor()).toBe(editorMock);
                        expect(pluginMock.CONFIG_PATH).toBe(pluginMock.getEditor().getElement().id);
                        expect(editorMock.addCommand.calls.count()).toBe(1);
                        expect(editorMock.addCommand.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: editorMock,
                            args: [DragDropr.tinyMCE.Plugin.COMMAND, pluginMock.execute],
                            returnValue: void 0
                        }));
                        expect(editorMock.addButton.calls.count()).toBe(1);
                        expect(editorMock.addButton.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: editorMock,
                            args: [
                                DragDropr.tinyMCE.Plugin.NAME,
                                {
                                    title: DragDropr.tinyMCE.Plugin.TITLE,
                                    cmd: DragDropr.tinyMCE.Plugin.COMMAND,
                                    image: editorMock.url + '' + DragDropr.tinyMCE.Plugin.IMAGE,
                                    disabled: true
                                }
                            ],
                            returnValue: void 0
                        }));
                        expect(editorMock.onPostRender.add.calls.count()).toBe(1);
                        expect(editorMock.onPostRender.add.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: editorMock.onPostRender,
                            args: [pluginMock.toggleButton],
                            returnValue: void 0
                        }));
                        expect(editorMock.onRemove.add.calls.count()).toBe(1);
                        expect(editorMock.onRemove.add.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: editorMock.onRemove,
                            args: [pluginMock.destruct],
                            returnValue: void 0
                        }));
                        expect(pluginMock.getEditor().getElement().dragdroprPlugin).toBe(pluginMock);
                    });

                    xit('DragDropr.tinyMCE.Plugin Read Config Value', function () {
                        pluginMock.init(editorMock, editorMock.url);
                        expect(pluginMock.getConfigValue(elementPath)).toBe(pluginMock.CONFIG_PATH + DragDropr.Classes.Config.PATH_SEPARATOR + elementPath);
                    });
                });
            });
        });
    });
});
