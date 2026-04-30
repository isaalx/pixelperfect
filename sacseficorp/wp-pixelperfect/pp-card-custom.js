(function () {
    var targetStateMap = new WeakMap();

    function activateTarget(target, contentHtml) {
        if (targetStateMap.has(target)) {
            return;
        }

        targetStateMap.set(target, {
            innerHTML: target.innerHTML,
            styleAttr: target.getAttribute('style')
        });

        target.innerHTML = contentHtml;
        target.classList.add('pp-card-menu-active');
        target.style.backgroundColor = '#000D54';
        target.style.color = '#ffffff';
        target.style.padding = '40px';
        target.style.borderRadius = '10px';
    }

    function restoreTarget(target) {
        var state = targetStateMap.get(target);
        if (!state) {
            return;
        }

        target.innerHTML = state.innerHTML;

        if (state.styleAttr === null) {
            target.removeAttribute('style');
        } else {
            target.setAttribute('style', state.styleAttr);
        }

        target.classList.remove('pp-card-menu-active');
        targetStateMap.delete(target);
    }

    function setupCardMenus() {
        var configs = window.ppCardMenuConfigs || [];
        if (!configs.length) {
            return;
        }

        configs.forEach(function (config) {
            if (!config || !config.selector || !config.contentId) {
                return;
            }

            var template = document.getElementById(config.contentId);
            if (!template) {
                return;
            }

            var targets = document.querySelectorAll(config.selector);
            if (!targets.length) {
                return;
            }

            var contentHtml = template.innerHTML;

            targets.forEach(function (target) {
                target.addEventListener('pointerenter', function () {
                    activateTarget(target, contentHtml);
                });

                target.addEventListener('pointerleave', function () {
                    restoreTarget(target);
                });
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupCardMenus);
    } else {
        setupCardMenus();
    }
})();
