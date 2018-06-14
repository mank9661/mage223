define([
    'DragDropr_Magento2/js/dragdropr'
], function () {
    function configSetup() {
        config = {
            'first': {
                'value': 'first',
                'second': {
                    'value': 'second',
                    'third': {
                        'value': 'third',
                        'fourth': {
                            'value': 'fourth',
                            'fifth': 'fifth'
                        }
                    }
                }
            }
        };
        correctPathArrays = [
            {
                pathArray: ['first'],
                value: config.first
            },
            {
                pathArray: ['first', 'value'],
                value: config.first.value
            },
            {
                pathArray: ['first', 'second'],
                value: config.first.second
            },
            {
                pathArray: ['first', 'second', 'value'],
                value: config.first.second.value
            },
            {
                pathArray: ['first', 'second', 'third'],
                value: config.first.second.third
            },
            {
                pathArray: ['first', 'second', 'third', 'value'],
                value: config.first.second.third.value
            },
            {
                pathArray: ['first', 'second', 'third', 'fourth'],
                value: config.first.second.third.fourth
            },
            {
                pathArray: ['first', 'second', 'third', 'fourth', 'value'],
                value: config.first.second.third.fourth.value
            },
            {
                pathArray: ['first', 'second', 'third', 'fourth', 'fifth'],
                value: config.first.second.third.fourth.fifth
            }
        ];
        incorrectPathArrays = [
            {
                pathArray: ['test']
            },
            {
                pathArray: ['first', 'first']
            },
            {
                pathArray: ['first', 'second', 'second']
            },
            {
                pathArray: ['first', 'second', 'third', 'third']
            },
            {
                pathArray: ['first', 'second', 'third', 'fourth', 'fourth']
            },
            {
                pathArray: ['first', 'second', 'third', 'fourth', 'fifth', 'fifth']
            }
        ];
    }

    describe('DragDropr_Magento2/js/dragdropr', function () {
        it('DragDropr Namespace', function () {
            expect(DragDropr).toEqual(jasmine.any(Object));
        });

        describe('DragDropr', function () {
            it('DragDropr.isNumber', function () {
                expect(DragDropr.isNumber).toEqual(jasmine.any(Function));
                expect(DragDropr.isNumber(1)).toBe(true);
                expect(DragDropr.isNumber(NaN)).toBe(false);
                expect(DragDropr.isNumber('')).toBe(false);
                expect(DragDropr.isNumber(false)).toBe(false);
                expect(DragDropr.isNumber(true)).toBe(false);
                expect(DragDropr.isNumber({})).toBe(false);
                expect(DragDropr.isNumber(void 0)).toBe(false);
                expect(DragDropr.isNumber(undefined)).toBe(false);
                expect(DragDropr.isNumber(null)).toBe(false);
                expect(DragDropr.isNumber(DragDropr.isNumber)).toBe(false);
            });

            it('DragDropr.isString', function () {
                expect(DragDropr.isString).toEqual(jasmine.any(Function));
                expect(DragDropr.isString(1)).toBe(false);
                expect(DragDropr.isNumber(NaN)).toBe(false);
                expect(DragDropr.isString('')).toBe(true);
                expect(DragDropr.isString(false)).toBe(false);
                expect(DragDropr.isString(true)).toBe(false);
                expect(DragDropr.isString({})).toBe(false);
                expect(DragDropr.isString(void 0)).toBe(false);
                expect(DragDropr.isString(undefined)).toBe(false);
                expect(DragDropr.isString(null)).toBe(false);
                expect(DragDropr.isString(DragDropr.isString)).toBe(false);
            });

            it('DragDropr.isObject', function () {
                expect(DragDropr.isObject).toEqual(jasmine.any(Function));
                expect(DragDropr.isObject(1)).toBe(false);
                expect(DragDropr.isNumber(NaN)).toBe(false);
                expect(DragDropr.isObject('')).toBe(false);
                expect(DragDropr.isObject(false)).toBe(false);
                expect(DragDropr.isObject(true)).toBe(false);
                expect(DragDropr.isObject({})).toBe(true);
                expect(DragDropr.isObject(void 0)).toBe(false);
                expect(DragDropr.isObject(undefined)).toBe(false);
                expect(DragDropr.isObject(null)).toBe(false);
                expect(DragDropr.isObject(DragDropr.isString)).toBe(false);
            });

            it('DragDropr.isVoid', function () {
                expect(DragDropr.isVoid).toEqual(jasmine.any(Function));
                expect(DragDropr.isVoid(1)).toBe(false);
                expect(DragDropr.isNumber(NaN)).toBe(false);
                expect(DragDropr.isVoid('')).toBe(false);
                expect(DragDropr.isVoid(false)).toBe(false);
                expect(DragDropr.isVoid(true)).toBe(false);
                expect(DragDropr.isVoid({})).toBe(false);
                expect(DragDropr.isVoid(void 0)).toBe(true);
                expect(DragDropr.isVoid(undefined)).toBe(true);
                expect(DragDropr.isVoid(null)).toBe(false);
                expect(DragDropr.isVoid(DragDropr.isVoid)).toBe(false);
            });

            it('DragDropr.isFunction', function () {
                expect(DragDropr.isFunction).toEqual(jasmine.any(Function));
                expect(DragDropr.isFunction(1)).toBe(false);
                expect(DragDropr.isNumber(NaN)).toBe(false);
                expect(DragDropr.isFunction('')).toBe(false);
                expect(DragDropr.isFunction(false)).toBe(false);
                expect(DragDropr.isFunction(true)).toBe(false);
                expect(DragDropr.isFunction({})).toBe(false);
                expect(DragDropr.isFunction(void 0)).toBe(false);
                expect(DragDropr.isFunction(undefined)).toBe(false);
                expect(DragDropr.isFunction(null)).toBe(false);
                expect(DragDropr.isFunction(DragDropr.isFunction)).toBe(true);
            });

            it('DragDropr.Classes', function () {
                expect(DragDropr.isObject(DragDropr.Classes)).toBe(true);
            });

            describe('Dragdropr.Classes', function () {
                it('DragDropr.Classes.Config', function () {
                    expect(DragDropr.isFunction(DragDropr.Classes.Config)).toBe(true);
                    expect(DragDropr.Classes.Config.PATH_SEPARATOR).toBe('.');
                });

                describe('DragDropr.Classes.Config', function () {
                    beforeEach(configSetup);

                    it('DragDropr.Classes.Config Set and Get Data Source', function () {
                        configObj = new DragDropr.Classes.Config();
                        configObj.setDataSource(config);
                        expect(configObj.getDataSource()).not.toBe(config);
                        expect(configObj.getDataSource()).toEqual(config);
                    });

                    it('DragDropr.Classes.Config Constructor Data Source', function () {
                        configObj = new DragDropr.Classes.Config(config);
                        expect(configObj.getDataSource()).not.toBe(config);
                        expect(configObj.getDataSource()).toEqual(config);
                    });

                    it('DragDropr.Classes.Config Read Empty Config Value', function () {
                        configObj = new DragDropr.Classes.Config();
                        incorrectPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(void 0);
                        });
                        correctPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(void 0);
                        });
                    });

                    it('DragDropr.Classes.Config Read Full Config Value', function () {
                        configObj = new DragDropr.Classes.Config(config);
                        incorrectPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(void 0);
                        });
                        correctPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(element.value);
                        });
                    });

                    it('DragDropr.Classes.Config Write Config Value', function () {
                        configObj = new DragDropr.Classes.Config();
                        correctPathArrays.forEach(function (element) {
                            configObj.write(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR), element.value);
                        });
                        incorrectPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(void 0);
                        });
                        correctPathArrays.forEach(function (element) {
                            expect(configObj.read(element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR))).toBe(element.value);
                            expect(configObj.getDataSource()).toEqual(config);
                        });
                        expect(configObj.getDataSource()).toEqual(config);
                    });
                });

                it('DragDropr.Classes.Listener', function () {
                    expect(DragDropr.isFunction(DragDropr.Classes.Listener)).toBe(true);
                });

                describe('DragDropr.Classes.Listener', function () {
                    beforeEach(function () {
                        emptyConfig = {};
                        nameConfig = {
                            name: 'nameConfig'
                        };
                        funcConfig = {
                            func: jasmine.createSpy('funcConfig.func')
                        };
                        fullConfig = {
                            name: 'fullConfig',
                            func: jasmine.createSpy('fullConfig.func')
                        };
                        testArguments = [1,2,3,4,5];
                    });

                    it('DragDropr.Classes.Listener Empty Config', function() {
                        listener = new DragDropr.Classes.Listener(emptyConfig);
                        expect(listener).toEqual(jasmine.any(DragDropr.Classes.Listener));
                        expect(listener.hasEventName()).toBe(false);
                        expect(listener.getEventName()).toBe(null);
                        expect(listener.hasFunction()).toBe(false);
                        expect(listener.getFunction()).toBe(null);
                        expect(listener.isValid(listener.getEventName())).toBe(false);
                        expect(listener.isValid()).toBe(false);
                        expect(listener.execute()).toBe(listener);
                    });

                    it('DragDropr.Classes.Listener Name Config', function() {
                        listener = new DragDropr.Classes.Listener(nameConfig);
                        expect(listener).toEqual(jasmine.any(DragDropr.Classes.Listener));
                        expect(listener.hasEventName()).toBe(true);
                        expect(listener.getEventName()).toBe(nameConfig.name);
                        expect(listener.hasFunction()).toBe(false);
                        expect(listener.getFunction()).toBe(null);
                        expect(listener.isValid()).toBe(false);
                        expect(listener.isValid(listener.getEventName())).toBe(false);
                        expect(listener.execute()).toBe(listener);
                    });

                    it('DragDropr.Classes.Listener Function Config', function() {
                        listener = new DragDropr.Classes.Listener(funcConfig);
                        expect(listener).toEqual(jasmine.any(DragDropr.Classes.Listener));
                        expect(listener.hasEventName()).toBe(false);
                        expect(listener.getEventName()).toBe(null);
                        expect(listener.hasFunction()).toBe(true);
                        expect(listener.getFunction()).toBe(funcConfig.func);
                        expect(listener.isValid()).toBe(true);
                        expect(listener.isValid('fullConfig')).toBe(true);
                        expect(listener.execute()).toBe(listener);
                        expect(funcConfig.func.calls.count()).toBe(1);
                        expect(funcConfig.func.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: window,
                            args: [],
                            returnValue: void 0
                        }));
                        expect(listener.execute.apply(listener, testArguments)).toBe(listener);
                        expect(funcConfig.func.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: window,
                            args: testArguments,
                            returnValue: void 0
                        }));
                        expect(funcConfig.func.calls.count()).toBe(2);
                    });

                    it('DragDropr.Classes.Listener Full Config', function() {
                        listener = new DragDropr.Classes.Listener(fullConfig);
                        expect(listener).toEqual(jasmine.any(DragDropr.Classes.Listener));
                        expect(listener.hasEventName()).toBe(true);
                        expect(listener.getEventName()).toBe(fullConfig.name);
                        expect(listener.hasFunction()).toBe(true);
                        expect(listener.getFunction()).toBe(fullConfig.func);
                        expect(listener.isValid()).toBe(false);
                        expect(listener.isValid('fullConfig')).toBe(true);
                        expect(listener.execute()).toBe(listener);
                        expect(fullConfig.func.calls.count()).toEqual(1);
                        expect(fullConfig.func.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: window,
                            args: [],
                            returnValue: void 0
                        }));
                        expect(listener.execute.apply(listener, testArguments)).toBe(listener);
                        expect(fullConfig.func.calls.mostRecent()).toEqual(jasmine.objectContaining({
                            object: window,
                            args: testArguments,
                            returnValue: void 0
                        }));
                        expect(fullConfig.func.calls.count()).toEqual(2);
                    });
                });

                describe('DragDropr.Classes.Interfaces', function () {
                    it('DragDropr.Classes.Interfaces ListenableInterface', function () {
                        configObj = new DragDropr.Classes.Config();
                        listener = new DragDropr.Classes.Listener({
                            name: void 0,
                            fnc: jasmine.createSpy('empty')
                        });
                        expect(configObj.getListeners()).toEqual([]);
                        expect(configObj.addListener(listener)).toBe(configObj);
                        expect(configObj.getListeners()).toEqual([listener]);
                        expect(configObj.removeListener(listener)).toBe(configObj);
                        expect(configObj.getListeners()).toEqual([]);
                    });

                    describe('DragDropr.Classes.Interfaces ListenableInterface', function () {
                        beforeEach(function () {
                            configSetup();
                            correctPathListeners = [];
                            configObj = new DragDropr.Classes.Config(config);
                            correctPathArrays.forEach(function (element) {
                                listenerName = element.pathArray.join(DragDropr.Classes.Config.PATH_SEPARATOR);
                                correctPathListeners.push(
                                    new DragDropr.Classes.Listener({
                                        name: listenerName,
                                        func: jasmine.createSpy(listenerName)
                                    })
                                );
                            });
                        });

                        it('DragDropr.Classes.Interfaces ListenableInterface Add/Remove', function () {
                            expect(configObj.getListeners()).toEqual([]);
                            correctPathListeners.forEach(function (listener) {
                                configObj.addListener(listener);
                            });
                            expect(configObj.getListeners().length).toBe(correctPathArrays.length);
                            expect(configObj.getListeners()).toEqual(correctPathListeners);
                            listeners = configObj.getListeners().slice();
                            while (listener = listeners.pop()) {
                                configObj.removeListener(listener);
                                expect(configObj.getListeners()).toEqual(listeners);
                            }
                            expect(listeners).toEqual([]);
                            expect(configObj.getListeners()).toEqual(listeners);
                        });

                        it('DragDropr.Classes.Interfaces ListenableInterface Dispatch', function () {
                            expect(configObj.getListeners()).toEqual([]);
                            correctPathListeners.forEach(function (listener) {
                                configObj.addListener(listener);
                            });
                            callCounts = {};
                            i = 100;
                            listeners = configObj.getListeners();

                            while (i--) {
                                index = parseInt(Math.random() * correctPathListeners.length, 10) % correctPathListeners.length;
                                eventName = listeners[index].getEventName();
                                configObj.dispatch(eventName);

                                if (DragDropr.isNumber(callCounts[eventName])) {
                                    callCounts[eventName]++;
                                } else {
                                    callCounts[eventName] = 1;
                                }
                            }

                            correctPathListeners.forEach(function (listener) {
                                expect(listener.getFunction().calls.count()).toBe(callCounts[listener.getEventName()]);
                            });
                        });
                    });
                });
            });

            it('DragDropr.getConfig', function () {
                expect(DragDropr.isFunction(DragDropr.getConfig)).toBe(true);
                expect(DragDropr.getConfig()).toEqual(jasmine.any(DragDropr.Classes.Config));
            });
        });
    });
});
