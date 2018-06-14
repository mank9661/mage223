(function() {
var __extends = (this && this.__extends) || (function () {
    var extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
        function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __assign = (this && this.__assign) || Object.assign || function(t) {
    for (var s, i = 1, n = arguments.length; i < n; i++) {
        s = arguments[i];
        for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
            t[p] = s[p];
    }
    return t;
};
var DragDropr = this.DragDropr || (DragDropr = {});
(function (DragDropr) {
    /**
     * Check whether given argument is number
     * @param x
     */
    function isNumber(x) {
        return typeof x === 'number' && !isNaN(x);
    }
    DragDropr.isNumber = isNumber;
    /**
     * Check whether given argument is number
     * @param x
     */
    function isString(x) {
        return typeof x === 'string';
    }
    DragDropr.isString = isString;
    /**
     * Check whether given argument is object
     * @param x
     */
    function isObject(x) {
        return !isFunction(x) && x instanceof Object;
    }
    DragDropr.isObject = isObject;
    /**
     * Check whether given argument is void (undefined)
     * @param x
     */
    function isVoid(x) {
        return x === void 0;
    }
    DragDropr.isVoid = isVoid;
    /**
     * Check whether given argument is function
     * @param x
     */
    function isFunction(x) {
        return typeof x === typeof Function;
    }
    DragDropr.isFunction = isFunction;
    /**
     * Check whether given argument is array
     * @param x
     */
    function isArray(x) {
        return Array.isArray(x);
    }
    DragDropr.isArray = isArray;
    /**
     * Check whether given argument is dom element
     * @param x
     */
    function isElement(x) {
        return x instanceof Element;
    }
    DragDropr.isElement = isElement;
    var Classes;
    (function (Classes) {
        var Listener = (function () {
            /**
             * @param config Object containing listener function and event name
             */
            function Listener(config) {
                var _this = this;
                /**
                 * @inheritDoc
                 */
                this.destruct = function () {
                    _this._func = null;
                    _this._eventName = null;
                };
                this._eventName = isString(config.name) ? config.name : null;
                this._func = isFunction(config.func) ? config.func : null;
            }
            /**
             * @inheritDoc
             */
            Listener.prototype.hasEventName = function () {
                return isString(this._eventName);
            };
            /**
             * @inheritDoc
             */
            Listener.prototype.getEventName = function () {
                return this._eventName;
            };
            /**
             * @inheritDoc
             */
            Listener.prototype.hasFunction = function () {
                return isFunction(this._func);
            };
            /**
             * @inheritDoc
             */
            Listener.prototype.getFunction = function () {
                return this._func;
            };
            /**
             * @inheritDoc
             */
            Listener.prototype.isValid = function (eventName) {
                if (this.hasEventName() && this.getEventName() !== eventName) {
                    return false;
                }
                return this.hasFunction();
            };
            /**
             * @inheritDoc
             */
            Listener.prototype.execute = function () {
                var args = [];
                for (var _i = 0; _i < arguments.length; _i++) {
                    args[_i] = arguments[_i];
                }
                if (this.hasFunction()) {
                    this.getFunction().apply(void 0, args);
                }
                return this;
            };
            return Listener;
        }());
        Classes.Listener = Listener;
        var BaseClass = (function () {
            function BaseClass() {
                /**
                 * Listeners
                 * @type {Array}
                 * @protected
                 */
                this._listeners = [];
            }
            /**
             * @inheritDoc
             */
            BaseClass.prototype.dispatch = function (eventName) {
                var args = [];
                for (var _i = 1; _i < arguments.length; _i++) {
                    args[_i - 1] = arguments[_i];
                }
                for (var _a = 0, _b = this.getListeners(); _a < _b.length; _a++) {
                    var listener = _b[_a];
                    if (listener.isValid(eventName)) {
                        listener.execute.apply(listener, args);
                    }
                }
                return this;
            };
            /**
             * @inheritDoc
             */
            BaseClass.prototype.addListener = function (listener) {
                if (listener instanceof Listener) {
                    this._listeners.push(listener);
                }
                return this;
            };
            /**
             * @inheritDoc
             */
            BaseClass.prototype.getListeners = function () {
                return this._listeners;
            };
            /**
             * @inheritDoc
             */
            BaseClass.prototype.removeListener = function (listener) {
                if (listener instanceof Listener) {
                    var index = this._listeners.indexOf(listener);
                    if (index !== -1) {
                        this._listeners.splice(index, 1);
                    }
                }
                return this;
            };
            return BaseClass;
        }());
        var Config = (function (_super) {
            __extends(Config, _super);
            /**
             * @param config Config data source
             */
            function Config(config) {
                var _this = _super.call(this) || this;
                _this.setDataSource(config);
                return _this;
            }
            /**
             * @inheritDoc
             */
            Config.prototype.setDataSource = function (config) {
                var dataSource = {};
                if (isObject(config)) {
                    dataSource = __assign({}, config);
                }
                this._dataSource = dataSource;
                this.dispatch();
                for (var _i = 0, _a = this.getListeners(); _i < _a.length; _i++) {
                    var listener = _a[_i];
                    if (listener.hasEventName() && this.read(listener.getEventName()) !== void 0) {
                        listener.execute();
                    }
                }
                return this;
            };
            /**
             * @inheritDoc
             */
            Config.prototype.getDataSource = function () {
                return this._dataSource;
            };
            /**
             * @inheritDoc
             */
            Config.prototype.write = function (path, value) {
                var parts = path.split(Config.PATH_SEPARATOR);
                var lastKey = parts.pop();
                var config = parts.length > 0 ? this.read(parts.join(Config.PATH_SEPARATOR), true) : this.getDataSource();
                parts.push(lastKey);
                var eventName = parts.join(Config.PATH_SEPARATOR);
                if (isObject(config)) {
                    if (config instanceof Config) {
                        config.write(lastKey, value);
                    }
                    else {
                        config[lastKey] = value;
                    }
                    this.dispatch(eventName);
                }
                return this;
            };
            /**
             * @inheritDoc
             */
            Config.prototype.read = function (path, generate) {
                if (!isString(path)) {
                    return void 0;
                }
                var parts = path.split(Config.PATH_SEPARATOR);
                var lastKey = parts.pop();
                var config = this.getDataSource();
                for (var _i = 0, parts_1 = parts; _i < parts_1.length; _i++) {
                    var part = parts_1[_i];
                    if (isVoid(config[part]) && generate) {
                        config[part] = {};
                    }
                    else if (!isObject(config[part])) {
                        return void 0;
                    }
                    if (config instanceof Config) {
                        config = config.read(part, generate);
                    }
                    else {
                        config = config[part];
                    }
                }
                if (isVoid(config[lastKey]) && generate) {
                    config[lastKey] = {};
                }
                return config[lastKey];
            };
            /**
             * Config data source depth separator
             * @type {string}
             */
            Config.PATH_SEPARATOR = '.';
            return Config;
        }(BaseClass));
        Classes.Config = Config;
    })(Classes = DragDropr.Classes || (DragDropr.Classes = {}));
    var config = new DragDropr.Classes.Config();
    function getConfig() {
        return config;
    }
    DragDropr.getConfig = getConfig;
    var currentWindow = null,
        wrapper = null;
    window.addEventListener('message', function (event) {
        if (!isObject(event.data) || !isObject(event.data.dragDroprPayload)) {
            return;
        }

        var integrationData = DragDropr.getConfig().read('integration.data'),
            skip = false,
            triggerChangeEvent = false;

        switch (event.data.dragDroprPayload.event) {
            case 'ready':
                event.source.postMessage({
                    integrationPayload: integrationData
                }, event.origin);
                break;
            case 'save':
                if (!isObject(event.data.integrationPayload) || event.data.integrationPayload.apiKey !== DragDropr.getConfig().read('apiKey')) {
                    return;
                }

                wrapper.innerWrapper.removeChild(wrapper.innerWrapper.iframe);
                wrapper.innerWrapper.iframe = null;

                switch (event.data.integrationPayload.entityType) {
                    case 'cms_page':
                    case 'catalog_category':
                        skip = true;

                        if (event.data.integrationPayload.identifier) {
                            window.location.reload();
                        } else {
                            if (!event.data.dragDroprPayload.additionalData.storeId) {
                                event.data.dragDroprPayload.additionalData.storeId = '0';
                            }

                            window.location = getUrl(
                                DragDropr.getConfig().read(event.data.integrationPayload.entityType + '.new_entity'),
                                event.data.dragDroprPayload.additionalData
                            );
                        }
                        break;
                }

                if (! skip) {
                    var element = document.getElementById(event.data.integrationPayload.configPath);
                    if (element instanceof HTMLTextAreaElement) {
                        if (event.data.dragDroprPayload.pageId) {
                            var html = document.createElement('div'),
                                idInput;
                            html.innerHTML = event.data.dragDroprPayload.html;
                            idInput = html.querySelector('#dragdropr-editor-page-id');

                            if (! idInput) {
                                idInput = document.createElement('input');
                                idInput.id = 'dragdropr-editor-page-id';
                                idInput.type = 'hidden';
                                idInput.style.display = 'none';
                                html.appendChild(idInput);
                            }

                            if (idInput.value !== event.data.dragDroprPayload.pageId) {
                                idInput.value = event.data.dragDroprPayload.pageId;
                            }

                            event.data.dragDroprPayload.html = html.innerHTML;
                        }

                        if (DragDropr.tinyMCE && DragDropr.tinyMCE.Plugin && element.dragdroprPlugin instanceof DragDropr.tinyMCE.Plugin) {
                            var editor = element.dragdroprPlugin.getEditor();
                            if (editor && !editor.destroyed) {
                                editor.setContent(event.data.dragDroprPayload.html);
                            }
                            else {
                                element.value = event.data.dragDroprPayload.html;
                                triggerChangeEvent = true;
                            }
                        }
                        else {
                            element.value = event.data.dragDroprPayload.html;
                            triggerChangeEvent = true;
                        }

                        if (triggerChangeEvent) {
                            var changeEvent = document.createEvent('HTMLEvents');
                            changeEvent.initEvent('change', true, true);
                            element.dispatchEvent(changeEvent);
                        }
                    }

                    wrapper.onclick();
                }
                break;
        }
        if (event.data.dragDroprPayload.close && currentWindow) {
            currentWindow.close();
        }
    });
    function checkIntegration() {
        if (!DragDropr.getConfig().read('integration.active')) {
            var integrationUrl = DragDropr.getConfig().read('integration.url');
            if (isString(integrationUrl)) {
                if (currentWindow && !currentWindow.closed) {
                    currentWindow.focus();
                }
                else {
                    currentWindow = window.open(integrationUrl, '_blank');
                }
                return true;
            }
        }
        return false;
    }

    function createIframe() {
        var iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.hide = function () {
            this.style.visibility = 'hidden';
            this.style.display = 'none';
        };
        return iframe;
    }
    function createInnerWrapper() {
        var innerWrapper = document.createElement('div');
        innerWrapper.style.position = 'fixed';
        innerWrapper.style.top = 0;
        innerWrapper.style.right = '5%';
        innerWrapper.style.bottom = 0;
        innerWrapper.style.left = '5%';
        innerWrapper.iframe = createIframe();
        innerWrapper.appendChild(innerWrapper.iframe);
        return innerWrapper;
    }
    function createWrapper() {
        var wrapper = document.createElement('div');
        wrapper.style.position = 'fixed';
        wrapper.style.top = 0;
        wrapper.style.right = 0;
        wrapper.style.bottom = 0;
        wrapper.style.left = 0;
        wrapper.style.zIndex = 9999;
        wrapper.style.background = 'rgba(255,255,255,0.7)';
        wrapper.style.visibility = 'hidden';
        wrapper.style.display = 'none';
        wrapper.onclick = function () {
            document.body.style.overflow = document.body.originalOverflow;
            this.style.visibility = 'hidden';
            this.style.display = 'none';
        };
        wrapper.show = function (url, configPath) {
            if (! this.innerWrapper.iframe || url !== this.innerWrapper.iframe.src || configPath !== this.innerWrapper.iframe.configPath) {
                if (this.innerWrapper.iframe) {
                    this.innerWrapper.removeChild(this.innerWrapper.iframe);
                }

                this.innerWrapper.iframe = createIframe();
                this.innerWrapper.appendChild(this.innerWrapper.iframe);
                this.innerWrapper.iframe.src = url;
                this.innerWrapper.iframe.configPath = configPath;
            }

            document.body.originalOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
            this.innerWrapper.iframe.visibility = 'visible';
            this.innerWrapper.iframe.display = 'initial';
            this.style.visibility = 'visible';
            this.style.display = 'initial';
        };
        wrapper.innerWrapper = createInnerWrapper();
        wrapper.appendChild(wrapper.innerWrapper);
        return wrapper;
    }
    function getUrl(url, urlParams) {
        url = decodeURIComponent(url);

        if (typeof urlParams === typeof {}) {
            for (var param in urlParams) {
                url = url.replace("${" + param + "}", urlParams[param] ? urlParams[param] : '');
            }
        }

        return url.replace(new RegExp('([^:]/)/+', 'g'), '$1')
            .replace('/?', '?');
    }
    wrapper = createWrapper();
    var interval = setInterval(function () {
        if (document.body !== null) {
            clearInterval(interval);
            document.body.appendChild(wrapper);
        }
    }, 50);

    function execute(configPath) {
        if (checkIntegration() || !configPath || !isString(configPath)) {
            return;
        }
        var entityType;
        switch (configPath) {
            case 'page_content':
            case 'cms_page_form_content':
                entityType = 'cms_page';
                break;
            case 'group_4description_editor':
            case 'group_4description':
            case 'category_form_description_editor':
            case 'category_form_description':
                entityType = 'catalog_category';
                break;
            default:
                entityType = 'other';
                break;
        }

        var urlParams = {
            storeUrl: DragDropr.getConfig().read('storeUrl')
        }, url = 'https://app.dragdropr.com/login', identifier, idInput, parentIdInput, storeIdInput;
        switch (entityType) {
            case 'cms_page':
                idInput = document.querySelector('input[name="page_id"]');
                url = DragDropr.getConfig().read('cms_page.endpoint');//'https://app.dragdropr.com/magento-2/pages/${identifier}/?domain=${storeUrl}';
                break;
            case 'catalog_category':
                idInput = document.querySelector('input[name="path"]');
                url = DragDropr.getConfig().read('catalog_category.endpoint');//'https://app.dragdropr.com/magento-2/categories/${identifier}/?domain=${storeUrl}';
                parentIdInput = document.querySelector('input[name="parent"]');
                storeIdInput = document.querySelector('input[name="store_id"]');

                if (! idInput) {
                    idInput = document.querySelector('input[name="general[path]"]');
                }

                if (storeIdInput) {
                    storeIdInput = document.querySelector('input[name="store_switcher"]');
                }

                if (parentIdInput && parentIdInput.value) {
                    urlParams['parentId'] = parseInt(parentIdInput.value, 10);
                }

                if (storeIdInput && storeIdInput.value) {
                    urlParams['storeId'] = parseInt(storeIdInput.value, 10);
                }
                break;
            case 'other':
                var element = document.getElementById(configPath);
                var html = document.createElement('div');
                html.innerHTML = element.value;
                idInput = html.querySelector('#dragdropr-editor-page-id');
                url = DragDropr.getConfig().read('default.endpoint');//'https://app.dragdropr.com/magento-2/editor/pages/?domain=${storeUrl}';
                break;
        }
        //validate url??
        identifier = null;

        if (idInput instanceof HTMLInputElement) {
            switch (entityType) {
                case 'catalog_category':
                    identifier = parseInt(idInput.value.split('/').pop(), 10);
                    break;
                case 'cms_page':
                    identifier = parseInt(idInput.value, 10);
                    break;
                case 'other':
                    identifier = idInput.value;
                    break;
            }
        }

        if (!isNumber(identifier)) {
            switch (entityType) {
                case 'cms_page':
                case 'catalog_category':
                    identifier = '';
                    break;
            }
        }

        urlParams['identifier'] = identifier;
        url = getUrl(url, urlParams);

        if (url.indexOf('?') === -1) {
            url = url + '?integrationType=' + DragDropr.getConfig().read('type');
        } else {
            url = url + '&integrationType=' + DragDropr.getConfig().read('type');
        }

        DragDropr.getConfig().write('integration.data', {
            type: DragDropr.getConfig().read('type'),
            apiKey: DragDropr.getConfig().read('apiKey'),
            entityType: entityType,
            configPath: configPath,
            identifier: identifier
        });
        wrapper.show(url, configPath);
    }
    DragDropr.execute = execute;
    var event = document.createEvent('Event');
    event.initEvent('DragDropr.Config', true, true);
    document.dispatchEvent(event);
})(DragDropr || (DragDropr = {}));
this.DragDropr = DragDropr;
return DragDropr;
}.call(this));
