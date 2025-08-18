(function () {
    if (window.__iframeResizerHandlerAdded) return;

    window.__iframeResizerHandlerAdded = true;

    window.addEventListener('message', function (e) {
        if (e.data.type === 'pl-wigdet-resize' && e.data.widgetId) {
            const iframe = document.getElementById(e.data.widgetId);
            if (iframe) {
                iframe.style.height = e.data.height + 'px';
            }
        }
    });

    document.addEventListener('click', () => {
        const iframes = document.querySelectorAll('iframe.pl-widget');
        iframes.forEach(iframe => {
            iframe.contentWindow.postMessage(
                {
                    type: 'parentClick',
                    widgetId: iframe.id
                },
                '*'
            );
        });
    });
})();
