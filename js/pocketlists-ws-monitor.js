(function(window, document) {
    'use strict';

    /**
     * WebSocket Connection Monitor
     * Tracks WebSocket connections with specific URL parameter and updates UI status
     * @version 1.0.0
     */
    class WebSocketMonitor {
      constructor(options = {}) {
        this.WS_MONITOR_PARAM = options.monitorParam || 'pocketlists';
        this.STATUS_ONLINE_COLOR = options.onlineColor || 'var(--green)';
        this.STATUS_OFFLINE_COLOR = options.offlineColor || 'var(--orange)';
        this.STATUS_SELECTOR = options.statusSelector || '.js-pl-ws-status';
        
        this.originalWebSocket = window.WebSocket;
        this.init();
      }
  
      init() {
        this.monitorWebSockets();
        this.createStatusElementIfMissing();
      }
  
      monitorWebSockets() {
        const self = this;
        
        window.WebSocket = function(url, protocols) {
          const socket = new self.originalWebSocket(url, protocols);
  
          if (new URL(url).searchParams.has(self.WS_MONITOR_PARAM)) {
            socket.addEventListener('open', () => self.updateStatus(true));
            socket.addEventListener('close', () => self.updateStatus(false));
            socket.addEventListener('error', () => self.updateStatus(false));
          }
  
          return socket;
        };
      }
  
      updateStatus(isOnline) {
        const statusElement = document.querySelector(this.STATUS_SELECTOR);
        if (!statusElement) return;
        
        statusElement.style.background = isOnline 
          ? this.STATUS_ONLINE_COLOR 
          : this.STATUS_OFFLINE_COLOR;
        
        statusElement.classList.toggle('ws-online', isOnline);
        statusElement.classList.toggle('ws-offline', !isOnline);
      }
  
      createStatusElementIfMissing() {
        if (!document.querySelector(this.STATUS_SELECTOR)) {
          const statusElement = document.createElement('div');
          statusElement.className = 'ws-status js-pl-ws-status';
          document.body.appendChild(statusElement);
        }
      }
  
      destroy() {
        window.WebSocket = this.originalWebSocket;
      }
    }
  
    // Expose only what's needed (optional)
    // window.WebSocketMonitor = WebSocketMonitor;
  
  })(window, document);