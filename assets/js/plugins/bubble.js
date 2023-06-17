export default {
    install: (app) => {
        app.config.globalProperties.$bubble = function (eventName, ...args) {
            let component = this;
            do {
                component.$emit(eventName, ...args);
                component = component.$parent;
            } while (component);
        };
    },
};
